<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

class ThreadModel extends Model
{
    protected $table = 'forum_thread';
    protected $fillable = ['fid','typeid','author','authorid','subject','dateline','lastpost','lastposter'];
    public $timestamps = FALSE;
    public $primaryKey  =  'tid';
    protected static $perpage = 20;
    protected static $fields  =  ['pid','tid','first','author','subject','dateline','message','useip'];
    protected static $Tfields  =  ['tid','subject','author','views','authorid','dateline','lastpost','lastposter','replies','heats','displayorder','typeid'];

    public static function getList($fid,$page=1) {
        $offset   =  ($page-1) * static::$perpage;
        $threads  =  self::select(self::$Tfields)->from('forum_thread')->where([['fid','=',$fid],['displayorder','>','-1']])
                  ->orderBy('displayorder','DESC')
                  ->orderBy('lastpost','DESC')
                  ->offset($offset)
                  ->limit(self::$perpage)
                  ->get();
        return $threads;
    }
    
}
