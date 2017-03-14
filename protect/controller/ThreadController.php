<?php

namespace controller;

use \model\ThreadModel;

class ThreadController extends \Controller
{
    protected $threadFields = [];
    public function get($request, $response, $args) 
    {
        $tid  = intval($args['tid']);
        $page =  isset($args['page']) ? intval($args['page']) : 1;
        $perpage  = 20;
        $offset   =  ($page - 1) * $perpage;
        $threadInfo  =  ThreadModel::select()
                          ->where([['tid','=',$tid],['invisible', '=', '0'],['first', '=', '1']])
                          ->first();
        $replies  =  ThreadModel::select()
                          ->where([['tid','=',$tid],['invisible','=','0']])
                          ->orderBy('dateline','ASC')
                          ->offset($offset)
                          ->limit($perpage)
                          ->get();
        $pages =  ThreadModel::paginationNum($tid);

        return $response->withJson(['replies'=>$replies, 'threadInfo'=>$threadInfo,'pages'=>$pages]);
    }

}
