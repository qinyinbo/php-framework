<?php

function getConfigFuncs() {
    return "bb";
}
$ph = array(
    'c' => getConfigFuncs()
);
$php_config = array();
$php_config["mysql"] = array(
    'default' =>array(
        'dbhost' => '127.0.0.1:3306',
        'dbuser' => 'qinyinbo',
        'dbpw' => '123456',
        'dbname' => 'qinyinbo',
        'dbdriver' => 'mysql',
        'dbprefix' => '', 
        'tablepre' => 'qinyinbo_',
        'pconnect' => FALSE,
        'db_debug' => TRUE,
        'cache_on' => FALSE,
        'cachedir' => '', 
        'dbcharset' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '', 
        'autoinit' => TRUE,
        'stricton' => FALSE,
        'port' => 3306,
    ),

);
$php_config["mongo"] = array(
    'default' =>array(
        'appname' => 'pw_555',
        'user' => 'mongouser',
        'password' => '123456',
    ),
    'defaults' =>array(
        'host'      => '127.0.0.1',
        'port'      => 22223,
        'user'  => 'mongouser',
        'password'  => '123456',
    ),


);
