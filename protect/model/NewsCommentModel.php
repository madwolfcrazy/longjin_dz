<?php
namespace model;

use Illuminate\Database\Eloquent\Model;

class NewsCommentModel extends Model
{
    protected $table  =  'portal_comment';
    protected $fillable = ['uid','username','message','postip','dateline','idtype','id'];
    public $timestamps = FALSE;
    public $primaryKey  =  'cid';
    /**
      * @param array 
      * @return new model instance 
      *
      **/
    public static function create(array $attributes = []){
        $illegalWords  =  IllegalWordModel::select()->get();
        foreach($illegalWords as $item) {
            $replacement  =  $item->replacement == '{BANNED}' ? '' : $item->replacement;
            $attributes['message']  =  str_replace($item->find, $replacement,
                                        $attributes['message']);
        }
        return parent::create($attributes);
    }
}
