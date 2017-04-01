<?php
$app->get( '/',function($r,$rr) {
        return $rr->withJson([]);
        });
$app->get( '/news/{newsid}[/{page}]','\controller\NewsController:get');
$app->get( '/cate/{catid}[/{page}]','\controller\NewsController:cate');
$app->get( '/forum[/{fid}[/{page}]]','\controller\ForumController:forum');
$app->get( '/forumlist/{fid}[/{page}]','\controller\ForumController:threadlist');
$app->get( '/thread/{tid}[/{page}]','\controller\ThreadController:get');
$app->get( '/comment/{newsid}','\controller\NewsController:comment');
$app->post( '/comment/{newsid:[0-9]+}[/{reply_comment_id:[0-9]+}]','\controller\NewsController:createComment');
$app->post( '/login','\controller\LoginController:login');
$app->post( '/thread/{fid}','\controller\ThreadController:createThread');
$app->post( '/reply/{tid}','\controller\ThreadController:createPost');
//发表日志
$app->post('/blog', '\controller\BlogController:createBlog');
