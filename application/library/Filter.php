<?php
class Filter {

    public function _int($input) {
        return intval($input);
    }

    public function _string($input) {
        return ''.$input;
    }

    public function _trim($input, $charList = null) {
        if ($charList === null) return trim($input);
        else return trim($input, $charList);
    }

    public function _range($input, $low, $high) {
        if ($low !== 'x' && $input < intval($low)) return intval($low);
        if ($high !== 'x' && $input > intval($high)) return intval($high);
        return $input;
    }

    public function _enum($input) {
        $args = func_get_args();
        array_shift($args);
        if (!in_array($input, $args)) return false;
        if (!in_array($input, $args)) return $args[0];

        return $input;
    }

    public function _htmlEncode($input) {
        return htmlspecialchars($input);
    }

    public function _toUtf8($str){
        $gbk_str  = @iconv("UTF-8","GBK//IGNORE",$str);
         //utf-8编码的页面转成gb2312 发现iconv在转换字符 "-" 到gb2312时会出错，如果没有ignore参数，所有该字符后面的字符串都无法被保存。不管怎么样，这个 "-" 都无法转换成功，无法输出  解决方法很简单，就是在需要转成的编码后加 "//IGNORE"  也就是iconv函数第二个参数后
        $utf8_str = @iconv("GBK","UTF-8//IGNORE",$gbk_str);
        if($str == $utf8_str) //is utf-8 
            return $str;
        $change_to_utf8 =  @iconv("GBK","UTF-8//IGNORE",$str);
        if("" != $change_to_utf8)
            return $change_to_utf8;
        return $str;
    } 

    //执行效率比iconv差太多, 一般情况下用 iconv，只有当遇到无法确定原编码是何种编码，或者iconv转化后无法正常显示时才用mb_convert_encoding 函数
    public function _convertEncoding($input, $to, $from = '') {
        if (empty($input)) return $input;

        $auto = array('ASCII', 'UTF-8', 'GBK', 'GB2312', 'JIS');
        $encoding = mb_detect_encoding($input, $auto, true);
        if ($encoding === strtoupper($to)) return $input;

        if ($from === '') {
            return mb_convert_encoding($input, $to, $auto);
        } else {
            return mb_convert_encoding($input, $to, $from);
        }
    }
}

