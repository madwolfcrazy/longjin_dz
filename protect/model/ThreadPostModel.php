<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

class ThreadPostModel extends Model
{
    protected $table  =  'forum_post';
    protected $fillable = ['fid', 'tid', 'first', 'author', 'authorid', 'subject', 'message', 'pid'];
    public $timestamps = FALSE;
    protected static $perpage = 20;
    protected static $fields  =  ['pid','tid','first','author','subject','dateline','message','useip'];
    protected static $Tfields  =  ['pid','tid','subject','author','views','authorid','dateline','lastpost','lastposter','replies','heats','displayorder','typeid'];


    public static function getList($fid,$page=1) {
        $offset   =  ($page-1) * static::$perpage;
        $threads  =  self::select(self::$Tfields)->where([['fid','=',$fid],['displayorder','>','-1']])
                  ->orderBy('displayorder','DESC')
                  ->orderBy('lastpost','DESC')
                  ->offset($offset)
                  ->limit(self::$perpage)
                  ->get();
        return $threads;
    }

    public static function create(array $attributes = []){
        if(!isset($attributes['pid'])) {
            $pid  =  ForumPostTableid::create();
            $attributes['pid']  =  $pid->getAttribute('pid');
            /*
            $model = new static($attributes);
            $model->save();
            return $model;
            */
            return parent::create($attributes);
        }
    }

    public function parserHidenTag($uid = 0) {
        if(preg_match('/\[hide\]/i',$this->message) !== FALSE) {
            // 有回复可见内容
            // 判断当前用户是否回复
            if(
                self::select(self::$Tfields)
                    ->where(['tid'=>$this->tid,'authorid'=>$uid])
                    ->count() > 0
              ){
                $this->message  =  preg_replace('/\[hide(.*)?\](.*)?(\[\/hide\])/i',"<div class=\"replied-view\"><div>以下内容回复可见：</div><p>$2</p></div>",$this->message);
            }else{
                $this->message  =  preg_replace('/\[hide(.*)?\](.*)?(\[\/hide\])/i',"<div class=\"replied-can-view\"><div>以下内容回复可见</div></div>",$this->message);
            }
        }
    }
}
