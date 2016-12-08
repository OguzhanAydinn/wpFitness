<?php
class dbapi {
	public $conn;
	public $db_type;

	public function __construct($DBTYPE = 'mysql') {
		$this->db_type = $DBTYPE;
	}
	
	public function __wakeup(){
		$this->Connect($DBNAME, $DBHOST, $DBUSER, $DBPASS);
	}

	public function Connect($DBNAME, $DBHOST, $DBUSER, $DBPASS) {
		if ($this->db_type == 'sybase') {
			$this->conn  = sybase_connect($DBHOST,$DBUSER, $DBPASS);
			if(sybase_select_db($DBNAME)){
				return $this->conn;
			} else {
				return false;
			}
		} elseif ($this->db_type == 'mssql') {
			$this->conn  = mssql_pconnect($DBHOST,$DBUSER, $DBPASS);
			if(mssql_select_db($DBNAME)){
				return $this->conn;
			} else {
				return false;
			}
		} elseif ($this->db_type == 'oracle') {
			$this->conn  = oci_pconnect($DBHOST,$DBUSER, $DBPASS);
			if(mssql_select_db($DBNAME)){
				return $this->conn;
			} else {
				return false;
			}
		} elseif ($this->db_type == 'odbc') {
			$this->conn = odbc_connect("Driver={SQL Server};Server=$DBHOST;Database=$DBNAME;", $DBUSER, $DBPASS);
			return $this->conn;
		} else {
//			$this->conn = new mysqli($DBHOST, $DBUSER, $DBPASS,$DBNAME);
//			pre($this->conn);
//			if ($this->conn->connect_errno) {
//				die( "Failed to connect to Database: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error);
//			}
//			return $this->conn;
			$this->conn = mysqli_connect($DBHOST, $DBUSER, $DBPASS);
			if (mysqli_select_db($this->conn, $DBNAME)) {
				return $this->conn;
			} else {
				die(mysqli_error($this->conn));
				return false;
			}
		}
	}

	public function Execute($sql) {
//		if (empty($this->conn) || !is_a($this->conn, 'mysqli')){
//			// reconnect
//			$this->Connect($DBNAME, $DBHOST, $DBUSER, $DBPASS);
//		}
		$rs = new RecordSet($sql, $this->db_type, $this->conn);
		return $rs;
	}

	public function Close() {
		if ($this->db_type == 'sybase') {
			return @sybase_close($this->conn);
		} elseif ($this->db_type == 'mssql') {
			return @mssql_close($this->conn);
		} elseif ($this->db_type == 'odbc') {
			return @odbc_close($this->conn);
		} else {
//			return mysqli_close($this->conn);
		}
	}

	public function Insert_ID() {
		if ($this->db_type == 'mysql') {
			return @mysqli_insert_id($this->conn);
		} else {
			return 0;
		}
	}

	public function Escape($txt) {
		if ($this->db_type == 'mysql') {
			return @mysqli_real_escape_string($this->conn,$txt);
		} else {
			if (get_magic_quotes_gpc()) {
				$temp = stripslashes($txt);
			}
			return $txt;
		}
	}

}

class RecordSet {
	public $rs;
	public $db_type;
	public $conn;

	public function RecordSet($sql, $db_type, $conn) {
		$this->db_type = $db_type;
		$this->conn = $conn;
		if ($this->db_type == 'sybase') {
			$rsx =@sybase_query($this->sql_escape($sql)); 
			if (!$rsx) {
				$this->queryError(sybase_get_last_message(),$sql);
			}
		} elseif ($this->db_type == 'mssql') {
			$rsx =@mssql_query($this->sql_escape($sql)); 
			pred($rsx);
			if (!$rsx) {
				$this->queryError(mssql_get_last_message(),$sql);
			}
		} elseif ($this->db_type == 'odbc') {
			$rsx =@odbc_exec($this->conn, $this->sql_escape($sql)); 
			if (!$rsx) {
				$this->queryError(odbc_errormsg(),$sql);
			}
		} else {
			$rsx = mysqli_query($this->conn, $this->sql_escape($sql));
			if (!$rsx) {
				$this->queryError(@mysqli_error($this->conn),$sql);
			}
		}
		$this->rs = $rsx;
	}
	
	private function queryError($err, $sql){
		//queryLog($err);
		die($err.'<br/>'.$sql);
	}

	public function RecordCount() {
		if ($this->db_type == 'sybase') {
			return @sybase_num_rows($this->rs);
		} elseif ($this->db_type == 'mssql') {
			return @mssql_num_rows($this->rs);
		} elseif ($this->db_type == 'odbc') {
			return @odbc_num_rows($this->rs);
		} else {
			return @mysqli_num_rows($this->rs);
		}
	}

	public function FetchNextObject($class = '') {
		if (!$this->RecordCount() > 0) {
			return false;
		}
		if ($this->db_type == 'sybase') {
			return @sybase_fetch_object($this->rs);
		} elseif ($this->db_type == 'mssql') {
			return @mssql_fetch_object($this->rs);
		} elseif ($this->db_type == 'odbc') {
			return @odbc_fetch_object($this->rs);
		} else {
			if ($class <> '') {
				return @mysqli_fetch_object($this->rs, $class);
			} else {
				return @mysqli_fetch_object($this->rs);
			}
		}
	}

	public function GetArray() {
		if ($this->db_type == 'sybase') {
			return @sybase_fetch_array($this->rs);
		}elseif ($this->db_type == 'mssql') {
			return @mssql_fetch_array($this->rs);
		}elseif ($this->db_type == 'odbc') {
			return @odbc_fetch_array($this->rs);
		}else{
			return @mysqli_fetch_array($this->rs, MYSQL_ASSOC);
		}
	}

	private function sql_escape($var) {
		return ($var);
		if (get_magic_quotes_gpc()) {
			$temp = stripslashes($var);
		} else {
			$temp = $var;
		}
		if (stristr($temp, "mysql")) {
			$temp = "";
		} else if (stristr($temp, "load_file")) {
			$temp = "";
		} else if (stristr($temp, "union")) {
			$temp = "";
		}
		return ($temp);
	}

}
?>
