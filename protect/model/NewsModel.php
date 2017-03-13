<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

class NewsModel extends Model
{
    protected $table  =  'portal_article_title';

    public function hasManyContents()
    {
        return $this->hasMany('NewsContentModel', 'aid','aid');
    }
}
