<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 17:46
 */

namespace common\utils;


class annotationUtil
{

    public static function getDocComment($str, $tag = '')
    {

        if (empty($tag)) {
            return $str;
        }

        $matches = array();
        preg_match("/" . $tag . "(.*)(\\r\\n|\\r|\\n)/U", $str, $matches);

        if (isset($matches[1])) {
            return trim($matches[1]);
        }
        return '';

    }

}