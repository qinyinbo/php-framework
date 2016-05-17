<?php

class Database_Mysql
{
    var $tablepre;
    var $version = '';
    var $drivertype = 'mysql';
    var $querynum = 0;
    var $slaveid = 0;
    var $curlink;
    var $link = array();
    var $config = array();
    var $sqldebug = array();
    var $map = array();

    function db_mysql($config = array(), $serverid = 1) {
        $this->serverid = $serverid;
        if(!empty($config)) {
            $this->set_config($config);
        }
    }

    function set_config($config, $serverid = 1) {
        $this->config = &$config;
        $this->tablepre = $config[$this->serverid]['tablepre'];
        if(!empty($this->config['map'])) {
            $this->map = $this->config['map'];
            for($i = 1; $i <= 100; $i++) {
                if(isset($this->map['forum_thread'])) {
                    $this->map['forum_thread_'.$i] = $this->map['forum_thread'];
                }
                if(isset($this->map['forum_post'])) {
                    $this->map['forum_post_'.$i] = $this->map['forum_post'];
                }
                if(isset($this->map['forum_attachment']) && $i <= 10) {
                    $this->map['forum_attachment_'.($i-1)] = $this->map['forum_attachment'];
                }
            }
            if(isset($this->map['common_member'])) {
                $this->map['common_member_archive'] =
                    $this->map['common_member_count'] = $this->map['common_member_count_archive'] =
                    $this->map['common_member_status'] = $this->map['common_member_status_archive'] =
                    $this->map['common_member_profile'] = $this->map['common_member_profile_archive'] =
                    $this->map['common_member_field_forum'] = $this->map['common_member_field_forum_archive'] =
                    $this->map['common_member_field_home'] = $this->map['common_member_field_home_archive'] =
                    $this->map['common_member_validate'] = $this->map['common_member_verify'] =
                    $this->map['common_member_verify_info'] = $this->map['common_member'];
            }
        }
    }

    function connect($serverid = 1) {

        if(empty($this->config) || empty($this->config[$this->serverid])) {
            $this->halt('config_db_not_found');
        }

        $this->link[$this->serverid] = $this->_dbconnect(
            $this->config[$this->serverid]['dbhost'],
            $this->config[$this->serverid]['dbuser'],
            $this->config[$this->serverid]['dbpw'],
            $this->config[$this->serverid]['dbcharset'],
            $this->config[$this->serverid]['dbname'],
            $this->config[$this->serverid]['pconnect']
        );
        $this->curlink = $this->link[$this->serverid];
    }

    function _dbconnect($dbhost, $dbuser, $dbpw, $dbcharset, $dbname, $pconnect, $halt = true) {

        if($pconnect) {
            $link = @mysql_pconnect($dbhost, $dbuser, $dbpw, MYSQL_CLIENT_COMPRESS);
        } else {
            $link = @mysql_connect($dbhost, $dbuser, $dbpw, 1);
        }
        if(!$link) {
            $halt && $this->halt('notconnect', $this->errno());
        } else {
            $this->curlink = $link;
            if($this->version() > '4.1') {
                $dbcharset = $dbcharset ? $dbcharset : $this->config[$this->serverid]['dbcharset'];
                $serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
                $serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
                $serverset && mysql_query("SET $serverset", $link);
            }
            $dbname && @mysql_select_db($dbname, $link);
        }
        return $link;
    }

    function table_name($tablename) {
        if(!empty($this->map) && !empty($this->map[$tablename])) {
            $id = $this->map[$tablename];
            if(!$this->link[$id]) {
                $this->connect($id);
            }
            $this->curlink = $this->link[$id];
        } else {
            $this->curlink = $this->link[$this->serverid];
        }
        return $this->tablepre.$tablename;
    }

    function select_db($dbname) {
        return mysql_select_db($dbname, $this->curlink);
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC) {
        if($result_type == 'MYSQL_ASSOC') $result_type = MYSQL_ASSOC;
        return mysql_fetch_array($query, $result_type);
    }

    function fetch_all($query, $result_type = MYSQL_ASSOC) {
        if($result_type == 'MYSQL_ASSOC') $result_type = MYSQL_ASSOC;
        $result = array();
        while($row = mysql_fetch_array($query, $result_type)) {
            $result[] = $row;
        }
        return $result;
    }

    function fetch_first($sql) {
        return $this->fetch_array($this->query($sql));
    }

    function result_first($sql) {
        return $this->result($this->query($sql), 0);
    }

    public function query($sql, $silent = false, $unbuffered = false) {;
        if(defined('DEBUG_LEVEL') && DEBUG_LEVEL) {
            $starttime = microtime(true);
        }

        if('UNBUFFERED' === $silent) {
            $silent = false;
            $unbuffered = true;
        } elseif('SILENT' === $silent) {
            $silent = true;
            $unbuffered = false;
        }

        $func = $unbuffered ? 'mysql_unbuffered_query' : 'mysql_query';

        if(!($query = $func($sql, $this->curlink))) {
            if(in_array($this->errno(), array(2006, 2013)) && substr($silent, 0, 5) != 'RETRY') {
                $this->connect($this->serverid);
                return $this->query($sql, 'RETRY'.$silent);
            }
            if(!$silent) {
                $this->halt($this->error(), $this->errno(), $sql);
            }
        }

        if(defined('DEBUG_LEVEL') && DEBUG_LEVEL) {
            $this->sqldebug[] = array($sql, number_format((microtime(true) - $starttime), 6), debug_backtrace(), $this->curlink);
        }

        $this->querynum++;
        return $query;
    }

    function affected_rows() {
        return mysql_affected_rows($this->curlink);
    }

    function error() {
        return (($this->curlink) ? mysql_error($this->curlink) : mysql_error());
    }

    function errno() {
        return intval(($this->curlink) ? mysql_errno($this->curlink) : mysql_errno());
    }

    function result($query, $row = 0) {
        $query = @mysql_result($query, $row);
        return $query;
    }

    function num_rows($query) {
        $query = mysql_num_rows($query);
        return $query;
    }

    function num_fields($query) {
        return mysql_num_fields($query);
    }

    function free_result($query) {
        return mysql_free_result($query);
    }

    function insert_id() {
        return ($id = mysql_insert_id($this->curlink)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }

    function fetch_row($query) {
        $query = mysql_fetch_row($query);
        return $query;
    }

    function fetch_fields($query) {
        return mysql_fetch_field($query);
    }

    function version() {
        if(empty($this->version)) {
            $this->version = mysql_get_server_info($this->curlink);
        }
        return $this->version;
    }

    function escape_string($str) {
        return mysql_escape_string($str);
    }

    function close() {
        return mysql_close($this->curlink);
    }

    function halt($message = '', $code = 0, $sql = '') {
        throw new Data_Base_DbException($message, $code, $sql);
    }

}

class Data_Base_DbException extends Exception{

    public $sql;

    public function __construct($message, $code = 0, $sql = '') {
        $this->sql = $sql;
        parent::__construct($message, $code);
    }

    public function getSql() {
        return $this->sql;
    }
}
