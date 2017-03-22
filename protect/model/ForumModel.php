<?php
namespace model;

use Illuminate\Database\Eloquent\Model;

class ForumModel extends Model
{
    protected $table    =  'forum_forum';
    public $timestamps  =  FALSE;
    public $primaryKey  =  'fid';
}
