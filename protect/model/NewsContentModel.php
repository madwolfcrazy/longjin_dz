<?php

namespace model;

use Illuminate\Database\Eloquent\Model;


class NewsContentModel extends Model
{
    protected $table = 'portal_article_content';


    public function belongsToNews()
    {
        return $this->belongsTo('NewsModel', 'aid', 'aid');
    }
}
