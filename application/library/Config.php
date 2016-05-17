<?php
class Config
{
    public static $configFile = '';
    public static function getConfig($property,$configFile='')
    {/*{{{*/
        if(empty($configFile)){
            return '';
        }
        self::$configFile = $configFile;
        static $configs = array();
        if(array_key_exists($property,$configs))
        {   
            return $configs[$property];
        }else
        {   
            $configs = self::getConfigVars();
            $config = isset($configs[$property]) ? $configs[$property] : '';
            return $config;
        }   
        return '';
    }/*}}}*/

    static public function getConfigVars()
    {/*{{{*/
        include (self::$configFile);
        return get_defined_vars();
    }/*}}}*/

}
