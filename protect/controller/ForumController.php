<?php

namespace controller;

class ForumController extends \Controller
{
    protected $forumFields  = ['fid','fup','type','name','threads','posts','todayposts','lastpost','simple'];
    protected $threadFields = ['tid','fid','author','heats','lastpost','lastposter','subject','views','replies'];
    /**
      *
      *
      **/
    public function forum($request, $response, $args) 
    {
        $fid  =  intval($args['fid']);
        $forum = $this->pdo->select($this->forumFields)
                       ->from('lgb_forum_forum')
                       ->where('fid', '=', $fid)
                       ->where('status', '!=', 0)
                       ->execute()
                       ->fetch();
        $subforums  =  $this->pdo->select($this->forumFields)
                            ->from('lgb_forum_forum')
                            ->where('fup','=',$fid)
                            ->where('status', '!=', 0)
                            ->orderBy('displayorder','ASC')
                            ->execute()
                            ->fetchAll();

        return $response->withJson(
                    ['forum'=>$forum,'subforums'=>$subforums]
                );
    }

    public function threadlist($request, $response, $args) {
        $fid  =  intval($args['fid']);
        $page =  isset($args['page']) ? intval($args['page']) : 1;
        $perpage  =  20;
        $offset   =  ($page-1) * $perpage;
        $threads  =  $this->pdo->select($this->threadFields)
                          ->from('lgb_forum_thread')
                          ->where('displayorder','>',-1)
                          ->orderBy('displayorder','DESC')
                          ->orderBy('lastpost','DESC')
                          ->limit($perpage, $offset)
                          ->execute()
                          ->fetchAll();
        return  $response->withJson(
                    [
                        'threads'=>$threads,
                    ]
                );
    }
}
