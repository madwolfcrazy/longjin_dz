<?php

namespace model;
use Illuminate\Database\Eloquent\Model;

class ForumAttachmentModel extends Model
{
    protected $table = "forum_attachment";

    /**
      * 通过tid取得所有附件
      * @param tid
      * @return array 附件列表
      *
      **/
    public function getByTid($tid, $URL_pre='http://localhost/') {
        $items  =  self::where('tid','=',$tid)->get();
        $data   = [];
        foreach($items as $item) {
            $temp  =  self::select()->from('forum_attachment_'.$item['tableid'])
                ->where('aid', '=', $item['aid'])
                ->first();
            $temp['attachment']  =  $URL_pre.'data/attachment/forum/'.$temp['attachment'];
            $data[$item['aid']] = $temp;
        }
        return $data;
    }

    /**
      * 通过pid取得附件
      * 并拼接附件URL地址
      * @param int 回帖id
      * @param string 附件前置地址
      * @return array 附件列表
      *
      **/
    public static function getByPid($pid, $URL_pre = 'http://localhost/') {
        $items  =  self::where('pid','=',$pid)->get();
        $data   = [];
        foreach($items as $item) {
            $temp  =  self::select()->from('forum_attachment_'.$item['tableid'])
                ->where('aid', '=', $item['aid'])
                ->first();
            $temp['attachment']  =  $URL_pre.'data/attachment/forum/'.$temp['attachment'];
            $data[$item['aid']] = $temp;
        }
        return $data;
    }
}
