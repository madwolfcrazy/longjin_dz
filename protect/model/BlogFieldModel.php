<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

class BlogFieldModel extends Model
{
    protected $table  =  'home_blogfield';
    protected $fillable = ['blogid','uid','message','postip'];
    public $timestamps = FALSE;
    public $primaryKey  =  'blogid';

    public function hasOneBlog()
    {
        return $this->hasMany('BlogModel', 'blogid','blogid');
    }
}
