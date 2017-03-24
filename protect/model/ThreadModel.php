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


    /**
      * 取某一论坛版块的帖子列表
      * @param int 版块id
      * @param int 数据页码,第几页的数据
      * @return array 
      *
      **/
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

    /**
      * 检查当前用户是否可以在本本块中发帖
      * 可以是返回TRUE 不可以时返回错误描述
      * @param int 用户id
      * @return bool|string 通过验证时返回TRUE，未通过时返回错误描述 
      *
      **/
    public function checkRightOnCreate($uid,) {
    }
    
}
