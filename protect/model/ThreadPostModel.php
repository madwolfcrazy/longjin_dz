<?php

namespace model;

use Illuminate\Database\Eloquent\Model;
use \models\ForumPostTableid;

class ThreadPostModel extends Model
{
    protected $table  =  'forum_post';
    protected $fillable = ['fid', 'tid', 'first', 'author', 'authorid', 'subject', 'message', ];
    public $timestamps = FALSE;
    public $primaryKey  =  'pid';
    protected static $perpage = 20;
    protected static $fields  =  ['pid','tid','first','author','subject','dateline','message','useip'];
    protected static $Tfields  =  ['tid','subject','author','views','authorid','dateline','lastpost','lastposter','replies','heats','displayorder','typeid'];

    public static function boot() {
        parent::boot();
        static::creating(
                function($data) {
                    $pidModel  =  ForumPostTableid::create();
                    $data->pid = $pidModel->pid;
                    exit('hhh');
                    return true;
                }
        );
    }

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
