<?php
namespace LaneWeChat\Core;

	class mysql{
		var $link;
		function connect($dbhost , $dbuser , $dbpw , $dbname=''){
			if(!$this->link=@mysql_connect($dbhost,$dbuser,$dbpw)){
					$this->msg('Can not connect to MySQL Server');
			}
			if($this->version()>'4.1'){
					@mysql_query("SET NAMES utf8");
			}
			if($dbname){
				if(!mysql_select_db($dbname,$this->link)){
					$this->msg('Can not access database');
				}
			}
		}

		function select_db($dbname){
			return mysql_select_db($dbname,$this->link);
		}

		function query($sql,$type='',$query_time=''){
			$func=$type=='UNBUFFERED'&&@function_exists('mysql_unbuffered_query')?'mysql_unbuffered_query':'mysql_query';
			if($query_time){
				//require '';
				$timestamp_before = $this->mictime();
				$query=$this->query($sql,$type);
				$timestamp_later = $this->mictime();
				$querytime=$timestamp_later-$timestamp_before;
				if($querytime){
					$fp = fopen('query_time.log','a');
					@flock($fp, LOCK_EX);
					@fwrite($fp, "$sql\t$querytime\n");
					@fclose($fp);
				}
			}else{
				if(!$query=$func($sql,$this->link)){
					if(in_array($this->errno(),array(2006,2013))){
						$this->close();
						require '../config.inc.php';
						$this->connect($dbhost,$dbuser,$dbpw,$dbname);
						$query=$this->query($sql,$type='');
					}else{
						$this->msg('MySQL Query Error',$sql);
					}
				}
				return $query;
			}
		}

		function fetch_array($query,$result_type=MYSQL_ASSOC){ //$result_type=MYSQL_ASSOC || MYSQL_NUM || MYSQL_BOTH
			return mysql_fetch_array($query,$result_type);
		}
		
		function result_first($sql) {
			$query = $this->query ( $sql );
			return $this->result ( $query, 0 );
		}

		function fetch_first($sql) {
			$query = $this->query ( $sql );
			return $this->fetch_array ( $query );
		}
		
		function fetch_all($sql) {
			$arr = array ();
			$query = $this->query ( $sql );
			while ( $data = $this->fetch_array ( $query ) ) {
				$arr [] = $data;
			}
			return $arr;
		}
		
		function errno(){
			return (($this->link) ? mysql_errno($this->link) : mysql_errno());
		}

		function error(){
			return (($this->link) ? mysql_error($this->link) : mysql_error());
		}

		function affected_rows(){
			return mysql_affected_rows($this->link);
		}

		function result($query,$row){
			$query=@mysql_result($query,$row);
			return $query;
		}

		function num_rows($query){
			$query=mysql_num_rows($query);
			return $query;
		}

		function num_fields(){
			return mysql_num_fields($query);
		}

		function free_result(){
			return mysql_free_result($query);
		}

		function insert_id(){
			return ($id=mysql_insert_id())>=0 ? $id : $this->result($this->query("SELECT last_insert_id()"),0);
		}
		
		function fetch_row($query){
			$query=mysql_fetch_row($query);
			return $query;
		}

		function fetch_field(){
			return mysql_fetch_field($query);
		}

		function version(){
			return mysql_get_server_info($this->link);
		}

		function close(){
			return mysql_close($this->link);
		}

		function msg($message='',$sql=''){
			$timestamp = time();
			$errmsg = '';
			$dberror = $this->error();
			$dberrno = $this->errno();
			if($message) {
				$errmsg = "Web info: $message\n\n";
			}
			if(isset($_SESSION['username'])) {
				$errmsg .= "User: ".htmlspecialchars($_SESSION['username'])."\n";
			}
			$errmsg .= "Time: ".date("Y-n-j g:ia", $timestamp)."\n";
			$errmsg .= "Script: ".$_SERVER['PHP_SELF']."\n";
			if($sql) {
				$errmsg .= "SQL: ".htmlspecialchars($sql)."\n";
			}
			$errmsg .= "Error:  $dberror\n";
			$errmsg .= "Errno:  $dberrno\n\n\n";
            echo $errmsg;
			/*$fp = @fopen('dberror.log', 'a');
			@flock($fp, LOCK_EX);
			@fwrite($fp, $errmsg);
			@fclose($fp);
			echo 'MySQL ERROR';*/
			exit();
		}
	}
?>