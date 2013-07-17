<?php
class ip2location {
	private $intip;
	private $db_resource; // object
	private $location = array ();
	private $wrong_mode;
	public static $iptable = 'ipblock';
	public static $countrytable = 'country';
	public static $regiontable ='region';

	public $match_domain = '#^(?:[A-Za-z]+://)?((?:[\w\-]+\.)+(?:com|net|org|gov|int|edu|cn|info|cc|name|biz|tv|cn|mobi|sh|ac|io|tw|hk|com\.hk|ws|travel|us|tm|la|in|eu|it|jp|co|me|mx|ca|ag|com\.co|net\.co|nom\.co|am|asia|at|be|bz|com\.bz|net\.bz|net\.br|com\.br|de|es|com\.es|nom\.es|org\.es|fm|gs|jobs|ms|com\.mx|nl|nu|tc|tk|vg|ad|ae|af|ai|al|an|ao|aq|ar|as|au|aw|az|ba|bb|bd|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|cd|cf|cg|ch|ci|ck|cl|cm|cr|cu|cv|cx|cy|cz|dj|dk|dm|do|dz|ec|ee|eg|er|et|fi|fj|fk|fo|ga|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gt|gu|gw|gy|hm|hn|hr|ht|hu|id|ie|il|im|iq|ir|is|je|jm|jo|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|mt|mu|mv|mw|my|mz|na|nc|ne|nf|ng|ni|no|np|nr|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|si|sk|sl|sm|sn|sr|st|sv|sy|sz|td|tf|tg|th|tj|tl|tn|to|tr|tt|tz|ua|ug|uk|uy|uz|va|vc|ve|vi|vn|vu|wf|ye|yt|yu|za|zm|zw))(?::(\d)+)?(?:/.*)?$#';
	public $match_ip = '/^(0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])(\.(0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])){3}$/';


	function __construct($db, $mode = true) {
		$this->db_resource = $db;
		$this->wrong_mode = $mode;
		if ($this->db_resource->connect_error)
			$this->dbwrong ( 'DB connect error' );
		else
			$this->db_resource->set_charset ( "utf8" );
	}
	function dbwrong($info = '') {
		$msg = "DB ERROR<br/>";
		$this->db_resource->connect_errno ? $msg .= "connect error NO:" . $this->db_resource->connect_errno : $msg .= "error NO:" . $this->db_resource->errno;
		$this->db_resource->connect_error ? $msg .= '<br/>connect error info:' . $this->db_resource->connect_error : $msg .= '<br/>error info:' . $this->db_resource->error;
		$msg .= '<br/>Info:' . $info;
		$this->wrong_mode ? die ( $msg ) : '';
	}
	function affected_rows(){
		$result=$this->db_resource->affected_rows;
		$result==-1?$this->dbwrong('affected_rows wrong'):'';
		return $result;
	}
	function querydb($sql, $wrongmsg = '', $querytype = MYSQLI_ASSOC) {
		$result = $this->db_resource->query ( $sql );
//var_dump($result);
		$result === false ? $this->dbwrong ( $wrongmsg ) : '';
		if (is_object ( $result )) {
			while ( $resultset = $result->fetch_array ( $querytype ) )
				$resultarray [] = $resultset;
			return $resultarray;
		} else
			return $result;
	}
	function queryfirst($sql, $wrongmsg = '', $querytype = MYSQLI_ASSOC) {
		$result = $this->db_resource->query ( $sql . ' limit 1' );
		$result === false ? $this->dbwrong ( $wrongmsg ) : '';
		if (is_object ( $result ))
			return $result->fetch_array ( $querytype );
		else
			return $result;
	}
	function getip() {
		$ip = false;
		// if (! empty ( $_SERVER ['HTTP_CLIENT_IP'] ))
		// 	$ip = $_SERVER ['HTTP_CLIENT_IP'];
		
		if ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) {
			$ips = explode ( ",", $_SERVER ['HTTP_X_FORWARDED_FOR'] );
			foreach($ips as $isip) {
				$isip=trim ( $isip );
				if ($isip&& $isip!= 'unknown') {
					$ip = $isip;
					break;
				}
			}
		}
		return $ip ? $ip : $_SERVER ['REMOTE_ADDR'];
	}
	function getlocation($input = null) {
		if ($input == null) {
			$input = 'myip';
		}
		if (preg_match ( $this->match_domain, $input, $match )) {
			$domain = $match [1];
			$ipv4 = gethostbyname ( $domain );
			$this->getiplocation ( $ipv4 );
			$this->location ['type'] = 1;
			$this->location ['in1'] = $domain;
			$this->location ['in2'] = $ipv4;
		} elseif (preg_match ( $this->match_ip, $input )) {
			$ippart = explode ( '.', $input );
			foreach ( $ippart as &$v )
				$v = intval ( $v );
			$ipv4 = implode ( '.', $ippart );
			$this->getiplocation ( $ipv4 );
			$this->location ['type'] = 2;
			$this->location ['in1'] = $input;
			$this->location ['in2'] = $ipv4;
		} elseif ($input == 'myip') {
			$myip = $this->getip ();
			$this->getiplocation ( $myip );
			$this->location ['type'] = 2;
			$this->location ['in1'] = $input;
			$this->location ['in2'] = $myip;
		} else {
			$this->location ['in1'] = $input;
			$this->location ['type'] = 0;
		}
		
		return $this->location;
	}
	function getiplocation($ip) {
		$this->intip = sprintf ( "%u", ip2long ( $ip ) );
		// query db
		$sql = "SELECT * FROM " . self::$iptable . " AS a LEFT JOIN " . self::$countrytable . " AS c ON a.`c`=c.`alpha2` LEFT JOIN ".self::$regiontable." AS r ON a.`r`=r.`r_code` AND a.`c`=r.`c_code`  WHERE {$this->intip}  BETWEEN `a`.`start`  AND  `a`.`end` ";
		$ipresult = $this->queryfirst ( $sql, 'LOCATION QUERY FAILED' );
		if (is_array ( $ipresult )) {
			$this->location = $ipresult;
		} else {
			$getnoloc = $this->queryfirst ( "SELECT * FROM " . self::$countrytable . " WHERE `alpha2`='zz'", 'NOLOC QUERY FAILED' );
			$this->location = $getnoloc;
		}
		//print_r($this->location);
		// binary ip
		$ipnum = explode ( '.', $ip );
		foreach ( $ipnum as $v ) {
			$ipbin [] = sprintf ( "%08b", $v );
			$iphex [] = sprintf ( "%02X", $v );
		}
		$this->location ['binip'] = implode ( '', $ipbin );
		$this->location ['hexip'] = implode ( '', $iphex );
		$this->location ['intip'] = $this->intip;
	}
}
