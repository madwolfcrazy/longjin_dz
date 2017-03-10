<?php
namespace controller;

use \helper\HtmlBrick;

class NewsController extends \Controller
{
    public function get($request, $response, $args) {
        $newsid  =  $args['newsid'];
        $news  =  $this->pdo->select(['aid','allowcomment','author',
                                        'dateline','catid','title',
                                        'uid','pic','username'])
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
}
