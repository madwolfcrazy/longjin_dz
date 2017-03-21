<?php
namespace model;

use Illuminate\Database\Eloquent\Model;

class NewsCommentModel extends Model
{
    protected $table  =  'portal_comment';
    protected $fillable = ['uid','username','message','postip','dateline','idtype','id'];
    public $timestamps = FALSE;
    public $primaryKey  =  'cid';
}
