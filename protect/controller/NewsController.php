<?php
namespace controller;

use \helper\HtmlBrick;
use \model\NewsModel;
use \model\NewsContentModel;
use \model\CategoryModel;
use \model\NewsCommentModel;
use Firebase\JWT\JWT;

class NewsController extends \Controller
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
    public function create($request, $response, $args) {
        $newsid =  intval($args['newsid']);
        $page   =  isset($args['page']) ? intval($args['page']) : 1;
        $limit  =  20;
        $offset =  ($page - 1) * $limit;
        $commentBody  =  $request->getParsedBody()['content'];
        $jwt_scope  =  ($this->ci->get('jwt'));
        if( is_object($jwt_scope)  && in_array("comment_create", $jwt_scope->scope)) {
            var_dump('Save comment');
        }else{
            return $response->withJson(['require_high_privilege'=>true]);
        }
    }
}
