<?php
class TestModel
{
    public $sys_server_region = 'default';


    public function __construct()
    {
        $this->db = new Database_Mysql();
    }
    public function test($params)
    {
/*
 *
 *
 * 构造一个config对象
 *
 *
 *
 */
        $serverConfig = Yaf_Registry::get('config')->application->iniconfig;
        $config = new Yaf_Config_Ini($serverConfig);
        var_dump($config->get("ini.Logger"));


/*
 *
 *
 * 获取一个已注册对象
 *
 *
 *
*/
        $php_config = Yaf_Registry::get('phpconfig');
        //return $php_config["mysql"];





        Yaf_Loader::import(APPLICATION_PATH."/application/library/redislib/lib/Predis.php");
        不符合命名规则，所以需要手动加载   Warning: Yaf_Loader::autoload(): Failed opening script /home/qinyinbo/devspace/lai/library/Predis/Clients.php: No such file or directory

        $instanceConfig['host'] = "127.0.0.1";
        $instanceConfig['port'] = "6379";
        $instanceConfig['password'] = "123456";
        $this->redis_model = new Predis_Clients($instanceConfig);



/*
 *
 * mysql
 *
 *
*/
        $this->db->db_mysql($php_config['mysql'], "default");
        $this->db->connect();
        $sql = 'SELECT * FROM ' . $this->db->table_name('user') . ' WHERE status=0';
        $query = $this->db->query($sql);
        $data = $this->db->fetch_all($query);


/*
 *
 * mongodb

*/
        $m_client = Database_MongoClient($php_config["mongo"]["default"]);
        $coll_obj = new MyMongoCollection($m_client, "mongo_dbname", "mongo_collname");
        // $cursor  = $coll_obj->find(array('loc' => array('$within' => array('$center' => array(array($lon, $lat), $radius) ) ) ) );
        $fields = array('qid', 'name', 'avatar', 'tel', 'loc', 'sex');
        $cursor  = $coll_obj->find(array('loc' => array('$near' => array($lon, $lat))), $fields)->limit($user_num);
        $users = iterator_to_array($cursor);
        @iconv('gbk', 'UTF-8//IGNORE', "utf8str");

        return $path;





/*
 *
 * redis
 *
 *
*/

        $config = Yaf_Registry::get('config');
        $redis_host = $config->application->m_redis_host;
        $redis_port = $config->application->m_redis_port;
        $time_out = $config->application->m_redis_timeout;
        $pass = $config->application->m_redis_pwd;
        $config = array( 'host' => $redis_host,'port' => $redis_port,'timeout'=>$time_out,'pass'=>$pass );
        $redis = RedisCache::getCache($config);
        $keys = array();
        foreach( $words as $word ){
            $key = self::$zhida_key_prefix.base64_encode($word);
            $keys[] = $key;
        }
        $info = $redis->mget($keys);
        if( !empty( $info ) ){
            return $info;
        }

/*
 *
 *
 * config toarray
*/

        $config = Yaf_Registry::get('config');
        $config->application->toarray());
        $region  = $this->_getRegion();


        return $region;
    }
    public function _getRegion()
    {/*{{{*/
        $hostname_arr = explode('.', gethostname());
        $server_region = empty($hostname_arr[2]) ? 'default' : $hostname_arr[2];
        return $server_region;
    }/*}}}*/
}
