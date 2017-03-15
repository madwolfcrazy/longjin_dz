<?php

namespace model;
use Illuminate\Database\Eloquent\Model;

class ForumAttachmentModel extends Model
{
    protected $table = "forum_attachment";

    /**
      *
      *
      **/
    public function getByTid($tid) {
        $items  =  self::where('tid','=',$tid)->get();
        $data   = [];
        foreach($items as $item) {
            $temp  =  self::select()->from('forum_attachment_'.$item['tableid'])
                ->where('aid', '=', $item['aid'])
                ->first();
            $temp['attachment']  =  'http://60.217.228.172/DZX2.5/data/attachment/forum/'.$temp['attachment'];
            $data[$item['aid']] = $temp;
        }
        return $data;
    }

    /**
      *
      *
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
