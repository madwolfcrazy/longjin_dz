<?php
/***
  *
  *
  *
  **/
namespace helper;

class HtmlBrick {

    static $allow_tags  =  array(
            'p'=>array('align'),
            'div'=>array('style'),
            'span'=>array('style'),
            'img'=>array('style','width','src','height','class'),
            'b'=>array(),
            'strong'=>array(),
            );
    static $current_tag = null;
    /**
      *
      *
      **/
    public static function pat($html) {

        $html =  preg_replace_callback(
                '/<([\w\d:]+)(.*?)>/i',
                "self::replaceStartTag",
                $html);
        $html =  preg_replace_callback(
                '/<\/([\w\d:]+)>/i',
                "self::replaceEndTag",
                $html);
        $html  =  preg_replace('/(<p(.*?)>)[ \x{3000}]+/ui','\1', $html);
        //
        $html  =  preg_replace('/<\!(.*?)(\-\->)/si','',$html);
        $html  =  preg_replace('/<\?(.*?)(\-\->)/si','',$html);
        //replace empty span
        $html  =  str_replace('<span>','',$html);
        $html  =  str_replace('</span>','',$html);
        return $html;
    }

    /**
      * replace start tag
      * 
      **/
    private static function replaceStartTag($matched) {

        $allow_tags_  =  array_keys(self::$allow_tags);
        $tag  =  strtolower($matched[1]);
        if( ! in_array($tag, $allow_tags_) ) {
            return '';
        }else{
            self::$current_tag  =  $tag;
            //$attr  =  ' '.preg_replace_callback(
            //                '/(\w+)=[\'"]([\(\)\w\d\-:;\. ]+?)["\']/i',
            //                'self::replaceAttribute',
            //                $matched[2]).'>';
            //$attr  =  preg_replace('/ +/',' ',$attr);
            //return '<'.$matched[1].$attr;
            preg_match_all('/(\w+)=[\'"]([\(\)\/\w\d\-:;\. ]+?)["\']/i',$matched[2],$attrs);
            $attrs_ = self::takeCareAttr($attrs, $tag);
            return '<'.$tag.' '.$attrs_.'>';
        }
    }
    
    /**
      * processing attr
      *
      **/
    private static function takeCareAttr($matched, $tag) {
        $attrs  =  array();
        foreach($matched[0] as $k=>$item) {
            $attr_name  =  strtolower($matched[1][$k]);
            if(in_array($attr_name, self::$allow_tags[$tag]) ) {
                $attr_val  =  $matched[2][$k];
                //
                if($attr_name == 'src' and strpos( $matched[2][$k],'http://') !== 0) {
                    $this_attr  =  $matched[1][$k].'="http://www.lgbzj.com/'.$matched[2][$k].'"';
                    $attr_val  =  'http://www.lgbzj.com/'.$matched[2][$k];
                }
                $attrs[$attr_name]  =  $attr_val;
            }
        }
        //
        if($tag == 'p') {
            if(isset($attrs['style'])) {
                if(! preg_match('/text\-indent:[ \w\d]+;/',$attrs['style'])) {
                    //
                    $attrs['style'] = 'text-indent:2em;'.$attrs['style'];
                }
            }else{
                $attrs['style'] = 'text-indent:2em;';
            }
            //
            if(isset($attrs['align']) and strtolower($attrs['align']) == 'center') {
                $attrs['style']  =  preg_replace('/text\-indent:[ \w\d]+;*/i','',$attrs['style']);
            }
        }
        if($tag == 'img') {
            if(isset($attrs['style'])) {
                $attrs['style'] = $attrs['style'].'width:90%;margin:0 auto;';
            }else{
                $attrs['style'] = 'width:90%;margin:0 auto;';
            }
        }
        //
        $s = ' ';
        foreach($attrs as $n=>$v) {
            if($v != '') {
                $s .= $n.'="'.$v.'" ';
            }
        }
        return trim($s);
    } 

    /**
      * replace attribute
      *
      **/
    private static function replaceAttribute($matched) {
        //print_r($matched);
        //print_r(self::$allow_tags[self::$current_tag]);
        if( ! in_array($matched[1], self::$allow_tags[self::$current_tag])) {
            return '';
        }else{
            return $matched[0];
        }
    }

    /**
      * replace end tag
      *
      **/
    private static function replaceEndTag($matched) {
        $allow_tags_  =  array_keys(self::$allow_tags);
        if( ! in_array($matched[1], $allow_tags_) ) {
            return '';
        }else{
            return $matched[0];
        }
    }
}
