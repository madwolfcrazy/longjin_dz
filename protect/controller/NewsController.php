<?php
namespace controller;

use \helper\HtmlBrick;
use \model\NewsModel;
use \model\NewsContentModel;

class NewsController extends \Controller
{
    protected $newsHeadFields  =  ['aid','allowcomment','author',
                                        'dateline','catid','title','click1',
                                        'uid','pic','username','summary'];
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
}
