<?php

namespace controller;

use \model\ThreadModel;
use \model\ThreadPostModel;
use \model\ForumAttachmentModel;
use \model\ForumPostTableid;

class ThreadController extends \Controller
{
    protected $threadFields = [];
    public function get($request, $response, $args) 
    {
        $jwt_scope  =  ($this->ci->get('jwt'));
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
        include '../vendor/comsenz/discuzX/src/function_discuzcode.php';
        //
        $URL_pre  =  $this->ci->get('settings')['url_pre'];
        foreach($replies as &$reply) {
            $reply->message  = str_replace(["\r","\n"], '',  \discuzx\discuzcode($reply->message));
            $reply->parserHidenTag($jwt_scope->user_id);
            $reply['attachments']  =  ForumAttachmentModel::getByPid($reply['pid'],$URL_pre);
        }
        $replyNum  =  ThreadPostModel::where([['tid','=',$tid],['status','>',-1],['first','=',0]])
                          ->count();


        return $response->withJson(['replies'=>$replies, 'threadInfo'=>$threadInfo,'replyNum'=>$replyNum]);
    }

    /**
      *
      **/
    public function createThread($request, $response, $args)
    {
        $body   =  $request->getParsedBody()['content'];
        $title  =  $request->getParsedBody()['title'];
        $fid    = intval($args['fid']);
        $jwt_scope  =  ($this->ci->get('jwt'));
        //forum_thread {fid,typeid,author,authorid,subject,dateline,lastpost,lastposter}
        //forum_thread_post {fid, tid, first, author, authorid, subject, message, }
        $threadTitle  =  ['fid'=>$fid,
                            'typeid'=>0,
                            'author'=>$jwt_scope->username,
                            'authorid'=>$jwt_scope->user_id,
                            'dateline'=>time(),
                            'lastpost'=>time(),
                            'lastposter'=>$jwt_scope->username,
                            'subject'=>$title,
                        ];
        $newThread   =  ThreadModel::create($threadTitle);
        $threadPost  =  [
                            'fid'=>$fid,
                            'tid'=>$newThread->tid,
                            'first'=>1,
                            'author'=>$jwt_scope->username,
                            'authorid'=>$jwt_scope->user_id,
                            'subject'=>$title,
                            'message'=>$body,
                        ];
        $newThreadPost  =  ThreadPostModel::create($threadPost);
        $forumLastInfo  =  "{$newThread->tid}\t{$newThread->subject}\t{$newThread->lastpost}\t{$jwt_scope->username}";
        $forumModel     =  ForumModel::find($fid);
        $forumModel->lastpost  =  $forumLastInfo;
        $forumModel->save();
        return $response->withJson(['tid'=>$newThread->tid]);
    }

}
