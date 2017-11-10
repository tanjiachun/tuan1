<?php
if(!defined("InMall")) {
    exit("Access Invalid!");
}

class db_mysql
{
	var $tablepre;
	var $version = '';
	var $querynum = 0;
	var $slaveid = 0;
	var $curlink;
	var $link = array();
	var $config = array();
	var $sqldebug = array();

	function db_mysql($config = array()) {
		if(!empty($config)) {
			$this->set_config($config);
		}
	}

	function set_config($config) {
		$this->config = &$config;
		$this->tablepre = $config['tablepre'];
	}

	function connect() {
		if(empty($this->config)) {
			$this->halt('config_db_not_found');
		}
		$this->link = $this->_dbconnect(
			$this->config['dbhost'],
			$this->config['dbuser'],
			$this->config['dbpw'],
			$this->config['dbcharset'],
			$this->config['dbname'],
			$this->config['pconnect']
		);
		$this->curlink = $this->link;
	}

	function _dbconnect($dbhost, $dbuser, $dbpw, $dbcharset, $dbname, $pconnect) {
		if($pconnect) {
			$link = @mysql_pconnect($dbhost, $dbuser, $dbpw, MYSQL_CLIENT_COMPRESS);
		} else {
			$link = @mysql_connect($dbhost, $dbuser, $dbpw, 1, MYSQL_CLIENT_COMPRESS);
		}
		if(!$link) {
			$this->halt('notconnect');
		} else {
			$this->curlink = $link;
			if($this->version() > '4.1') {
				$dbcharset = $dbcharset ? $dbcharset : $this->config['dbcharset'];
				$serverset = $dbcharset ? 'character_set_connection='.$dbcharset.', character_set_results='.$dbcharset.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysql_query("SET $serverset", $link);
			}
			$dbname && @mysql_select_db($dbname, $link);
		}
		return $link;
	}

	function table_name($tablename) {
		return $this->tablepre.$tablename;
	}

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->curlink);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function fetch_first($sql) {
		$res = $this->query($sql);
		$ret = $this->fetch_array($res);
		$this->free_result($res);
		return $ret ? $ret : array();
	}

	function result_first($sql) {
		$res = $this->query($sql);
		$ret = $this->result($res, 0);
		$this->free_result($res);
		return $ret;
	}

	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
		'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->curlink))) {
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->connect();
				return $this->query($sql, 'RETRY'.$type);
			}
			if($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('query_error', $sql);
			}
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

	function close() {
		return mysql_close($this->curlink);
	}

	function halt($message = '', $sql = '') {
		if($message == 'notconnect') {
			$title = '无法连接到数据库服务器';
		} elseif($message == 'security_error') {
			$title = '查询语句安全威胁';
		} elseif($message == 'query_error') {
			$title = '查询语句错误';
		} elseif($message == 'config_db_not_found') {
			$title = '数据库配置错误，请仔细检查 config_global.php 文件';
		}
		$dberrno = $this->errno();
		$dberror = str_replace($this->tablepre,'',$this->error());
		$sql = htmlspecialchars(str_replace($this->tablepre,'',$sql));
		$msg = '[Type] '.$title.'<br/>';
		$msg .= $dberrno ? '['.$dberrno.'] '.$dberror.'<br/>' : '';
		$msg .= $sql ? '[Query] '.$sql.'<br/>' : '';
		echo $msg;
		exit();
	}
}

class DB
{

	function table($table) {
		return DB::_execute('table_name', $table);
	}

	function delete($table, $condition, $limit = 0, $unbuffered = true) {
		if(empty($condition)) {
			return false;
		} elseif(is_array($condition)) {
			$where = DB::implode_field_value($condition, ' AND ');
		} else {
			$where = $condition;
		}
		$sql = "DELETE FROM ".DB::table($table)." WHERE $where ".($limit > 0 ? "LIMIT $limit" : '');
		return DB::query($sql, ($unbuffered ? 'UNBUFFERED' : ''));
	}

	function insert($table, $data, $return_insert_id = false, $replace = false, $silent = false) {
		$sql = DB::implode_field_value($data);
		$cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';
		$table = DB::table($table);
		$silent = $silent ? 'SILENT' : '';
		$return = DB::query("$cmd $table SET $sql", $silent);
		return $return_insert_id ? DB::insert_id() : $return;
	}

	function update($table, $data, $condition, $unbuffered = false, $low_priority = false) {
		$sql = DB::implode_field_value($data);
		$cmd = "UPDATE ".($low_priority ? 'LOW_PRIORITY' : '');
		$table = DB::table($table);
		$where = '';
		if(empty($condition)) {
			$where = '1';
		} elseif(is_array($condition)) {
			$where = DB::implode_field_value($condition, ' AND ');
		} else {
			$where = $condition;
		}
		$res = DB::query("$cmd $table SET $sql WHERE $where", $unbuffered ? 'UNBUFFERED' : '');
		return $res;
	}
	
	public static function quote($str, $noarray = false) {
		if(is_string($str)) {
			return '\'' . mysql_escape_string($str) . '\'';
		}
		if(is_int($str) or is_float($str)) {
			return '\'' . $str . '\'';
		}
		if(is_array($str)) {
			if($noarray === false) {
				foreach($str as &$v) {
					$v = DB::quote($v, true);
				}
				return $str;
			} else {
				return '\'\'';
			}
		}
		if(is_bool($str)) {
			return $str ? '1' : '0';
		}
		return '\'\'';
	}

	public static function quote_field($field) {
		if(is_array($field)) {
			foreach($field as $k => $v) {
				$field[$k] = DB::quote_field($v);
			}
		} else {
			if(strpos($field, '`') !== false) {
				$field = str_replace('`', '', $field);
			}
			$field = '`' . $field . '`';
		}
		return $field;
	}

	function implode_field_value($array, $glue = ',') {
		$sql = $comma = '';
		$glue = ' ' . trim($glue) . ' ';
		foreach ($array as $k => $v) {
			$sql .= $comma . DB::quote_field($k) . '=' . DB::quote($v);
			$comma = $glue;
		}
		return $sql;
	}

	function insert_id() {
		return DB::_execute('insert_id');
	}

	function fetch($resourceid, $type = MYSQL_ASSOC) {
		return DB::_execute('fetch_array', $resourceid, $type);
	}

	function fetch_first($sql) {
		DB::checkquery($sql);
		return DB::_execute('fetch_first', $sql);
	}

	function result($resourceid, $row = 0) {
		return DB::_execute('result', $resourceid, $row);
	}

	function result_first($sql) {
		DB::checkquery($sql);
		return DB::_execute('result_first', $sql);
	}

	function query($sql, $type = '') {
		DB::checkquery($sql);
		return DB::_execute('query', $sql, $type);
	}

	function num_rows($resourceid) {
		return DB::_execute('num_rows', $resourceid);
	}

	function affected_rows() {
		return DB::_execute('affected_rows');
	}

	function free_result($query) {
		return DB::_execute('free_result', $query);
	}

	function error() {
		return DB::_execute('error');
	}

	function errno() {
		return DB::_execute('errno');
	}

	function _execute($cmd , $arg1 = '', $arg2 = '') {
		static $db;
		if(empty($db)) $db = & DB::object();
		$res = $db->$cmd($arg1, $arg2);
		return $res;
	}

	function &object($dbclass = 'db_mysql') {
		static $db;
		if(empty($db)) $db = new $dbclass();
		return $db;
	}

	function checkquery($sql) {		
		$checkcmd = array('SEL'=>1, 'UPD'=>1, 'INS'=>1, 'REP'=>1, 'DEL'=>1);
		if($GLOBALS['config']['security']['querysafe']['status']) {
			$check = 1;
			$cmd = strtoupper(substr(trim($sql), 0, 3));
			if(isset($checkcmd[$cmd])) {
				$check = DB::_do_query_safe($sql);
			} elseif(substr($cmd, 0, 2) === '/*') {
				$check = -1;
			}
			
			if ($check < 1) {
				DB::_execute('halt', 'security_error', $sql);
			}
		}
		return true;
	}

	function _do_query_safe($sql) {
		$sql = str_replace(array('\\\\', '\\\'', '\\"', '\'\''), '', $sql);
		$mark = $clean = '';
		if(strpos($sql, '/') === false && strpos($sql, '#') === false && strpos($sql, '-- ') === false && strpos($sql, '@') === false && strpos($sql, '`') === false) {
			$clean = preg_replace("/'(.+?)'/s", '', $sql);
		} else {
			$len = strlen($sql);
			$mark = $clean = '';
			for ($i = 0; $i <$len; $i++) {
				$str = $sql[$i];
				switch ($str) {
					case '`':
						if(!$mark) {
							$mark = '`';
							$clean .= $str;
						} elseif ($mark == '`') {
							$mark = '';
						}
						break;
					case '\'':
						if (!$mark) {
							$mark = '\'';
							$clean .= $str;
						} elseif ($mark == '\'') {
							$mark = '';
						}
						break;
					case '/':
						if (empty($mark) && $sql[$i + 1] == '*') {
							$mark = '/*';
							$clean .= $mark;
							$i++;
						} elseif ($mark == '/*' && $sql[$i - 1] == '*') {
							$mark = '';
							$clean .= '*';
						}
						break;
					case '#':
						if (empty($mark)) {
							$mark = $str;
							$clean .= $str;
						}
						break;
					case "\n":
						if ($mark == '#' || $mark == '--') {
							$mark = '';
						}
						break;
					case '-':
						if (empty($mark) && substr($sql, $i, 3) == '-- ') {
							$mark = '-- ';
							$clean .= $mark;
						}
						break;

					default:

						break;
				}
				$clean .= $mark ? '' : $str;
			}
		}
		if(strpos($clean, '@') !== false) {
			return '-3';
		}
		$clean = preg_replace("/[^a-z0-9_\-\(\)#\*\/\"]+/is", "", strtolower($clean));
		if($GLOBALS['config']['security']['querysafe']['afullnote']) {
			$clean = str_replace('/**/','',$clean);
		}
		if(is_array($GLOBALS['config']['security']['querysafe']['dfunction'])) {
			foreach($GLOBALS['config']['security']['querysafe']['dfunction'] as $fun) {
				if(strpos($clean, $fun.'(') !== false) return '-1';
			}
		}
		if(is_array($GLOBALS['config']['security']['querysafe']['daction'])) {
			foreach($GLOBALS['config']['security']['querysafe']['daction'] as $action) {
				if(strpos($clean,$action) !== false) return '-3';
			}
		}
		if($GLOBALS['config']['security']['querysafe']['dlikehex'] && strpos($clean, 'like0x')) {
			return '-2';
		}
		if(is_array($GLOBALS['config']['security']['querysafe']['dnote'])) {
			foreach($GLOBALS['config']['security']['querysafe']['dnote'] as $note) {
				if(strpos($clean,$note) !== false) return '-4';
			}
		}
		return 1;
	}
}

?>
