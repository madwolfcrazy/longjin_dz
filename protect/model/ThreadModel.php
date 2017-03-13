<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

class ThreadModel extends Model
{
    protected $table = 'forum_post';
    protected static $perpage = 20;
    protected static $fields  =  ['author','authorid','subject','dateline','fid','views','heats','lastposter','lastpost'];

    public static function getList($fid,$page=1) {
        $offset   =  ($page-1) * static::$perpage;
        $threads  =  self::select(self::$fields)->where([['fid','=',$fid],['displayorder','>',-1]])
                  ->orderBy('displayorder','DESC')
                  ->orderBy('lastpost','DESC')
                  ->offset($offset)
                  ->limit(self::$perpage)
                  ->get();
        return $threads;
    }
}
