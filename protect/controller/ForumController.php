<?php

namespace controller;

use \model\ThreadModel;
use \model\ForumModel;

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
        $forum = ForumModel::select($this->forumFields)
                       ->where([['fid', '=', $fid],['status', '!=', 0]])
                       ->first();
        $subforums  =  ForumModel::select($this->forumFields)
                            ->where([['fup','=',$fid],['status', '!=', 0]])
                            ->orderBy('displayorder','ASC')
                            ->get();
        $threadlist  =  [];
        if( in_array($forum['type'], array('sub', 'forum'))) {
            $threadlist  =  ThreadModel::getlist($fid,1);
        }

        return $response->withJson(
                    ['forum'=>$forum,'subforums'=>$subforums,'threadlist'=>$threadlist]
                );
    }

    /**
      *
      *
      **/
    public function threadlist($request, $response, $args) {
        $fid  =  intval($args['fid']);
        $page =  isset($args['page']) ? intval($args['page']) : 1;
        $perpage  =  20;
        $offset   =  ($page-1) * $perpage;
        /*
        $threads  =  $this->pdo->select($this->threadFields)
                          ->from('lgb_forum_thread')
                          ->where('displayorder','>',-1)
                          ->orderBy('displayorder','DESC')
                          ->orderBy('lastpost','DESC')
                          ->limit($perpage, $offset)
                          ->execute()
                          ->fetchAll();
                          */
        $threads = ThreadModel::getList($fid,$page);
        return  $response->withJson(
                    [
                        'threads'=>$threads,
                    ]
                );
    }
}
