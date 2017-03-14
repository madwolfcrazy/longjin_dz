<?php

namespace controller;

use \model\ThreadModel;
use \model\ThreadPostModel;
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
        $fid  =  isset($args['fid']) ? intval($args['fid']) : 0;
        $page  =  isset($args['page']) ? intval($args['page']) : 1;
        $perpage  =  20;
        $forum = ForumModel::select($this->forumFields)
                       ->where([['fid', '=', $fid],['type','!=','group'],['status', '=', 1]])
                       ->first();
        $subforums  =  ForumModel::select($this->forumFields)
                            ->where([['fup','=',$fid],['status', '=', 1]])
                            ->orderBy('displayorder','ASC')
                            ->get();
        $threadlist  =  [];
        if( in_array($forum['type'], array('sub', 'forum'))) {
            $threadlist  =  ThreadModel::getlist($fid,$page);
        }

        foreach($subforums as &$f) {
            if($f->type == 'group') {
                $f->subforums  =  ForumModel::select($this->forumFields)
                                        ->where([['fup','=',$f->fid],['status','!=',0]])
                                        ->orderBy('displayorder', 'ASC')
                                        ->get();
            }
        }

        $paginationNum  =   ThreadModel::where([['fid','=',$fid],['displayorder','>',-1]])
                                        ->count();

        return $response->withJson(
                    ['forum'=>$forum,'subforums'=>$subforums,'threadlist'=>$threadlist,'threadcount'=>$paginationNum]
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
        $threads = ThreadPostModel::getList($fid,$page);
        return  $response->withJson(
                    [
                        'threads'=>$threads,
                    ]
                );
    }
}
