<?php
namespace controller;

use \helper\HtmlBrick;

class NewsController extends \Controller
{
    protected $newsHeadFields  =  ['aid','allowcomment','author',
                                        'dateline','catid','title',
                                        'uid','pic','username','summary'];
    protected $cateFields  =  ['catname','catid','displayorder','articles'];
    public function get($request, $response, $args) {
        $newsid  =  $args['newsid'];
        $news  =  $this->pdo->select($this->newsHeadFields)
                        ->from('lgb_portal_article_title')
                        ->where('aid', '=', $newsid)
                        ->execute()->fetch();
        $page  =  isset($args['page']) ? intval($args['page']) : 1;
        $content  =  $this->pdo->select(['content'])->from('lgb_portal_article_content')
                          ->where('aid', '=',$newsid)
                          ->where('pageorder', '=',$page)
                          ->execute()->fetch();
        if(! $news) {
            return $response->withStatus(404);
        }
        $news['content']  =  isset($content['content']) ? HtmlBrick::pat($content['content']) : null;
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
        $list   =  $this->pdo->select($this->newsHeadFields)
                        ->from('lgb_portal_article_title')
                        ->where('catid','=',$catid)
                        ->where('status', '=', 0)
                        ->limit($limit, $offset)
                        ->execute()
                        ->fetchAll();
        $subcates  =  $this->pdo->select($this->cateFields)
                           ->from('lgb_portal_category')
                           ->where('upid', '=', $catid)
                           ->orderBy('displayorder','ASC')
                           ->execute()
                           ->fetchAll();
        return $response->withJson(['list'=>$list, 'subcates'=>$subcates]);
    }
}
