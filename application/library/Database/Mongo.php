<?php
class Database_MongoClient {

    private $_conn = NULL;

    public function __construct($config)
    {/*{{{*/
        $this->_connect($config);
    }/*}}}*/

    private function _connect($config)
    {/*{{{*/
        $starttime = microtime();
        if(isset($config['appname'])){
            list($type, $replicaSet ) = explode('_', $config['appname']);
            $servers = Qconf::getAllHost("dba/mdb/". $config['appname']);
            $options = array(
                'replicaSet' => $replicaSet,
                'username' => $config['user'],
                'password' => $config['password'],
                'readPreference' => MongoClient::RP_SECONDARY_PREFERRED,
            );
            $mongo_servers = 'mongodb://'. implode(',', $servers);
        }else{
            $mongo_servers = 'mongodb://'.$config['host'].':'.$config['port'];
            $options = array(
                'username' => $config['user'],
                'password' => $config['password'],
            );
        } 
        $conn = new MongoClient($mongo_servers, $options);
        $this->_conn = $conn;
        return $conn;
    }/*}}}*/

    public function getConn()
    {/*{{{*/
        return $this->_conn;
    }/*}}}*/
}

class MyMongoCollection {

    private $_coll = NULL;

    public function __construct($client, $db, $coll)
    {/*{{{*/
        try{
            $this->_coll = $client->selectCollection($db, $coll);
        }catch(Exception $e){
        
        }
    }/*}}}*/

    public function __call($name, $arguments)
    {
        if(! $this->_coll) {
            return false;
        }

        try {
            $data = call_user_func_array(array(
                    $this->_coll,
                    $name
            ), $arguments);
        } catch(Exception $e) {
            $data = $e->getCode();
        }

        return $data;
    }

}
