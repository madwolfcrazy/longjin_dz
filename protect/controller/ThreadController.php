<?php

namespace controller;

class ThreadController extends \Controller
{
    protected $threadFields = [];
    public function get($request, $response, $args) 
    {
        $tid  = intval($args['tid']);
        $page =  isset($args['page']) ? intval($args['page']) : 1;
        $perpage  = 20;
        $offset   =  ($page - 1) * $perpage;
        $threadInfo  =  $this->pdo->select()
                          ->from('lgb_forum_post')
                          ->where('tid','=',$tid)
                          ->where('invisible', '=', '0')
                          ->where('first', '=', '1')
                          ->execute()
                          ->fetch();
        $replies  =  $this->pdo->select()
                          ->from('lgb_forum_post')
                          ->where('tid','=',$tid)
                          ->where('invisible','=','0')
                          ->where('first','=','0')
                          ->orderBy('dateline','ASC')
                          ->limit($perpage, $offset)
                          ->execute()
                          ->fetchAll();

        return $response->withJson(['replies'=>$replies, 'threadInfo'=>$threadInfo]);
    }

}
