<?php

namespace controller;

use \model\ThreadModel;
use \model\ThreadPostModel;
use \model\ForumAttachmentModel;

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
                          ->where([['tid','=',$tid],['status', '>', -1]])
                          ->first();
        $replies  =  ThreadPostModel::select()
                          ->where([['tid','=',$tid],['invisible','>',-1]])
                          ->orderBy('position','ASC')
                          ->offset($offset)
                          ->limit($perpage)
                          ->get();
        //
        $URL_pre  =  $this->ci->get('settings')['url_pre'];
        foreach($replies as &$reply) {
            $reply['attachments']  =  ForumAttachmentModel::getByPid($reply['pid'],$URL_pre);
        }
        $replyNum  =  ThreadPostModel::where([['tid','=',$tid],['status','>',-1],['first','=',0]])
                          ->count();


        return $response->withJson(['replies'=>$replies, 'threadInfo'=>$threadInfo,'replyNum'=>$replyNum]);
    }

}
