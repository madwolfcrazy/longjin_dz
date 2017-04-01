<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    protected $table  =  'home_blog';
    protected $fillable = ['uid','username','subject','classid','catid','dateline'];
    public $timestamps = FALSE;
    public $primaryKey  =  'blogid';

    public function hasOneContent()
    {
        return $this->hasMany('BlogFieldModel', 'blogid','blogid');
    }
}
