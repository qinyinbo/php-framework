<?php
Class RedisCache
{
    private static $cache = array();

    public static function getCache( $redisConfig )
    {

        if(empty($dest)){
            $dest = 'REDIS';
        }

        $host    = $redisConfig['host'];
        $port    = $redisConfig['port'];
        $timeout = $redisConfig['timeout'];
        $pass = $redisConfig['pass'];
        $key = md5("$host:$port:$timeout");
        if(isset(self::$cache[$key])){
            $redis = self::$cache[$key];
            try{
                if($redis->ping() == '+PONG'){
                    return $redis;
                }
            }catch(RedisException $e){
                self::$cache[$key] = new Redis();
                self::$cache[$key]->connect($host,$port,$timeout);
                if( !empty( $pass ) ){
                    self::$cache[$key] ->auth($pass);
                }
                return  self::$cache[$key];
            }
        }

        self::$cache[$key] = new Redis();
        self::$cache[$key] ->connect($host,$port,$timeout);
        if(!empty($pass)){
            self::$cache[$key] ->auth($pass);
        }
        return  self::$cache[$key];
    }
}

