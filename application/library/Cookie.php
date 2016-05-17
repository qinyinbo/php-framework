<?php
/**
*cookie 管理类
*
**/

class Cookie {
	static	private $__prefix = 'fix_com_';
	static	private $__expire = 86400;
	static	private $__path = '/';
	static	private $__domain = '';
    // 判断Cookie是否存在
    static function is_set($name) {
        return isset($_COOKIE[Cookie::$__prefix.$name]);
    }

    // 获取某个Cookie值
    static function get($name) {
        $value   = $_COOKIE[Cookie::$__prefix.$name];
        $value   =  unserialize(base64_decode($value));
        return $value;
    }

    // 设置某个Cookie值
    static function set($name,$value,$expire='',$path='',$domain='') {
        if($expire=='') {
            $expire = Cookie::$__expire;
        }
        if(empty($path)) {
            $path = Cookie::$__path;
        }
        if(empty($domain)) {
            $domain =   Cookie::$__domain;
        }
        $expire =   !empty($expire)?    time()+$expire   :  0;
        $value   =  base64_encode(serialize($value));
        setcookie(Cookie::$__prefix.$name, $value,$expire,$path,$domain);
        $_COOKIE[Cookie::$__prefix.$name]  =   $value;
    }

    // 删除某个Cookie值
    static function delete($name) {
        Cookie::set($name,'',-3600);
        unset($_COOKIE[Cookie::$__prefix.$name]);
    }

    // 清空Cookie值
    static function clear() {
        unset($_COOKIE);
    }
}
