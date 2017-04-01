<?php
namespace controller;

use \helper\HtmlBrick;
use \model\BlogModel;
use \model\BlogFieldModel;
use Firebase\JWT\JWT;

class BlogController extends \Controller
{
    protected $newsHeadFields  =  ['aid','allowcomment','author',
                                        'dateline','catid','title','click1',
                                        'uid','pic','username','summary','contents'];
    protected $cateFields  =  ['catname','catid','displayorder','articles'];
    public function get($request, $response, $args) {
        $newsid  =  $args['newsid'];
        $news  =  NewsModel::select($this->newsHeadFields)
                        ->where('aid', '=', $newsid)
                        ->first();
        $page  =  isset($args['page']) ? intval($args['page']) : 1;

        $content  =  NewsContentModel::select(['content'])
                          ->where([['aid', '=',$newsid],['pageorder', '=',$page]])
                          ->first();

        if(! $news) {
            return $response->withStatus(404);
        }
        $news['content']  =  isset($content->content) ? HtmlBrick::pat($content->content) : null;
        return $response->withJson(['news'=>$news]);
    }

    /**
      *
      *
      **/
    public function cate($request, $response, $args) {
        $catid  = $args['catid'];
        $page   =  isset($args['page']) ? intval($args['page']) : 1;
        $limit  =  20;
        $offset =  ($page - 1) * $limit;
        $list   =  NewsModel::select($this->newsHeadFields)
                        ->where([['catid','=',$catid],['status', '=', 0]])
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        $subcates  =  CategoryModel::select($this->cateFields)
                           ->where('upid', '=', $catid)
                           ->orderBy('displayorder','ASC')
                           ->get();
        $categoryInfo  =  CategoryModel::select($this->cateFields)
                               ->where('catid', '=', $catid)
                               ->first();
        return $response->withJson(['list'=>$list, 'subcates'=>$subcates,'categoryInfo'=>$categoryInfo]);
    }

    /**
      *
      *
      **/
    public function comment($request, $response, $args) {
        $newsid =  $args['newsid'];
        $page   =  isset($args['page']) ? intval($args['page']) : 1;
        $limit  =  20;
        $offset =  ($page - 1) * $limit;
        $comments  =  NewsCommentModel::select()
                        ->where([['id', '=', $newsid],['idtype','=','aid']])
                        ->orderBy('dateline','DESC')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        return $response->withJson(['comments'=>$comments]);
    }

    /**
      *
      *
      **/
    public function createBlog($request, $response, $args) {
        $POST       =  $request->getParsedBody();
        $blogBody   =  $POST['content'];
        $blogTitle  =  $POST['title'];
        $classid    =  isset($POST['classid']) ? $POST['classid'] : 0;
        $catid      =  isset($POST['catid']) ? $POST['catid'] : 0;
        $jwt_scope  =  ($this->ci->get('jwt'));

        if( ($jwt_scope)  && in_array("comment_create", $jwt_scope->scope)
                && $jwt_scope->user_id > 0
                ) {
            $BM  =  BlogModel::create([
                    'uid'=>$jwt_scope->user_id,
                    'username'=>$jwt_scope->username,
                    'subject'=>$blogTitle,
                    'dateline'=>time(),
                    'catid'=>$catid,
                    'classid'=>$classid,
            ]);
            $BFM  =  BlogFieldModel::create( [
                        'blogid'=>$BM->blogid,
                        'uid'=>$jwt_scope->user_id,
                        'message'=>$blogBody,
                        'postip'=>$request->getAttribute('ip_address'),
                    ]);
            return $response->withJson(['status'=>'ok','blog_id'=>$BM->blogid]);
        }else{
            return $response->withJson(['require_high_privilege'=>true]);
        }
    }
}
