<?php

class NewsController extends \Controller
{
    public function get($request, $response, $args) {
        $newsid  =  $args['newsid'];
        $news  =  $this->pdo->select(['aid','allowcomment','author','dateline','catid','title','uid','pic','username'])->from('lgb_portal_article_title')->where('aid', '=', $newsid)->execute()->fetch();
        if(! $news) {
            return $response->withStatus(404);
        }
        return $response->withJson(['news'=>$news]);
    }
}
