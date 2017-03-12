<?php

namespace model;

class ThreadModel extends \Model
{
    protected static $perpage = 20;
    public static function getList($fid,$page=1) {
        $offset   =  ($page-1) * static::$perpage;
        $threads  =  self::$pdo->select()
                          ->from('lgb_forum_thread')
                          ->where('displayorder','>',-1)
                          ->orderBy('displayorder','DESC')
                          ->orderBy('lastpost','DESC')
                          ->limit($this->perpage, $offset)
                          ->execute()
                          ->fetchAll();
        return $threads;
    }
}
