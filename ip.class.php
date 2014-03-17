<?php
/* Mar 16 ,2014
 * IP Geolocation Finder 5.0
 * core ip.class 
 * author:shengyueming@gmail.com
 *
 */
class ip2loc {
	//ip category
	const IP_IPV4=0x1001;
	const IP_IPV4_BINARY=0x1002;
	const IP_IPV4_OCTET=0x1003;
	const IP_IPV4_HEX=0x1004;
	const IP_IPV4_INT=0x1005;

	const IP_IPV6=0x2001;
	const IP_IPV6_V4MAPPED=0x2002;
	const IP_IPV6_V4COMPATIBLE=0x2003;
	const IP_IPV6_6TO4=0x2004;

	const URL_IPV4=0x3001;
	const URL_IPV6=0x3002;

	const IP_UNINITIAL=0xF00;
	const IP_INVALID=0xF01;
	const IP_IPV4_INVALID=0xF02;
	const IP_IPV6_INVALID=0xF03;

	const URL_UNINITIAL=0xF10;
	const URL_INVALID=0xF11;

	//ip tables
	const IP_BLOCK_TABLE='block';
	const IP_LOC_TABLE='loc';
	const IP_COUNTRY_TABLE='country';

	
	//ip table columns
	const FIELD_IP_START='f1';
	const FIELD_IP_END='f2';
	//const FIELD_IP_COUNTRY='';
	//const FIELD_IP_COUNTRY_FULLNAME='';
	const FIELD_LOC_REGION_ENAME='rname';
	const FIELD_LOC_CITY='ciname';
	const FIELD_IP_ISP='';
	const FIELD_IP_GEOID='geo_id';
	const FIELD_LOC_GEOID='gid';
	const FIELD_LOC_CONTINENT='cocode';
	const FIELD_LOC_COUNTRY_CODE='ccode';
	const FIELD_COUNTRY_CODE='alpha2';
	const FIELD_COUNTRY_ALPHA3='alpha3';
	const FIELD_COUNTRY_ENAME='cename';
	const FIELD_COUNTRY_CNAME='ccname';

	//fields of output
	const O_IPV4_NORMAL='ipv4';
	const O_IPV6_V4MAPPED='ipv6v4mapped';
	const O_IPV6_NORMAL='ipv6';
	const O_DB_CONTINENT_CODE='dbcontinentcode';
	const O_DB_COUNTRY_CODE='dbcountrycode';
	const O_DB_COUNTRY_ALPHA3='dbcountryalpha3';
	const O_DB_COUNTRY_ENAME='dbcountryename';
	const O_DB_COUNTRY_CNAME='dbcountrycname';
	const O_DB_REGION_ENAME='dbregionename';
	const O_DB_CITY='dbcityename';

	//generic
	private $raw_ip;
	private $ips=array();
	private $db; 
	private $location =array();
	private $wrong_mode;
	private $ip_type=self::IP_UNINITIAL;
	private $url_type=self::URL_UNINITIAL;


	//regexp of ip or url
	public static $regexp_domain = '#^(?:[A-Za-z]+://)?((?:[\w\-]+\.)+(?:com|net|org|gov|int|edu|cn|info|cc|name|biz|tv|cn|mobi|sh|ac|io|tw|hk|ws|travel|us|tm|la|in|eu|it|jp|co|me|mx|ca|ag|am|asia|at|be|bz|de|es|fm|gs|jobs|ms|nl|nu|tc|tk|vg|ad|ae|af|ai|al|an|ao|aq|ar|as|au|aw|az|ba|bb|bd|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|cd|cf|cg|ch|ci|ck|cl|cm|cr|cu|cv|cx|cy|cz|dj|dk|dm|do|dz|ec|ee|eg|er|et|fi|fj|fk|fo|ga|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gt|gu|gw|gy|hm|hn|hr|ht|hu|id|ie|il|im|iq|ir|is|je|jm|jo|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|mt|mu|mv|mw|my|mz|na|nc|ne|nf|ng|ni|no|np|nr|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|si|sk|sl|sm|sn|sr|st|sv|sy|sz|td|tf|tg|th|tj|tl|tn|to|tr|tt|tz|ua|ug|uk|uy|uz|va|vc|ve|vi|vn|vu|wf|ye|yt|yu|za|zm|zw))(?::(\d)+)?(?:/.*)?$#';
	public static $regexp_ipv4 = '/^(0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])(\.(0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])){3}$/';
	//public static $regexp_ipv6_v4other='/^[0-9a-f:]+:((0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])(\.(0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])){3})$/i';
	//public static $regexp_ipv6_v4mapped='/^::ffff:([0-9.]+)$/i';


	function __construct($db) {
		$this->db = $db;
	}

	protected function set_wrong_db_msg($extinguish='',$type=0){
		$separation='';
		switch ($type){
			case 0:default:$separation='<br>';
			break;
			case 1:$separation="\r\n";
		}
		$msg='DATABASE ERR INFO :'.$separation;
		$msg.='DB ERR NO :'.$this->db->errorCode().$separation;
		$msg.='DB ERR TERMINOLOGY :'.$this->db->errorInfo();

		return $msg;
	}
	//bitwise hexadecimal to binary,compatible with ipv4/ipv6
	//pass string in ,string out
	public static function  bit_hex2bin($v){
		$origin_array=str_split($v);
		$res_array= array_map(function ($a){
			if($a=='0')return  '0000';
			else return str_pad(base_convert($a, 16, 2),4,'0',STR_PAD_LEFT);
		}, $origin_array);

		return implode('', $res_array);
	}

	public static function  bit_bin2hex($v){
		$c=count($v);
		$re='';
		for($i=0;$i<=$c-4;$i+=4){
			$re.=base_convert($v[$i].$v[$i+1].$v[$i+2].$v[$i+3], 2, 16);
		}
		return $re;
	}
	//binary bit compare,compatible with ipv4/ipv6
	//input binary ip strings 
	public static function bit_compare($a,$b){
		if(!preg_match("/^[01]+$/", $a)||!preg_match("/^[01]+$/", $b))return false;
		$ar=strlen($a);
		$br=strlen($b);
		$max_len=max($ar,$br);
		if($ar!=$br){
			if($max_len>$ar)$pad_part=& $a;
			else $pad_part=& $b;
			$pad_part=str_pad($pad_part, $max_len,"0",STR_PAD_LEFT);
		}

		$uar=str_split($a);
		$ubr=str_split($b);
		//$udiff=array();
		for($i=0;$i<$max_len;$i++){
			//array_unshift($udiff, (intval($uar[i]) ^ intval($ubr[i])));
			if(1===intval($uar[$i]) ^ intval($ubr[$i])){
				return $uar[$i]==='1'?1:-1;
			}
		}
		return 0;
		//$bit_diff=intval($a) ^ intval($b);
		var_dump($uar,$ubr,$udiff);

	}
	public  static function is_ipv4($ip){
		return filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)===false?false:true;
	}
	public static function is_ipv6($ip){
		return filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)===false?false:true;
	}
	public static function is_url($url){
		return preg_match(self::regexp_domain, $url)==false?false:true;
	}

	//improved version that widely used comparing to ripe defined ipv4-mapped ipv6
	//::ffff/96.These addresses are used to embed IPv4 addresses in an IPv6 address.
	public static function is_ipv6_v4mapped($ip){
		if(false===self::is_ipv6($ip))return false;
		//$mappend_start=unpack('H*',inet_pton('::ffff:0:0'));
		//$mapped_end=unpack('H*', inet_pton('::ffff:ffff:ffff'));
		//$mapped_ip=unpack('H*', inet_pton($ip));
		$mapped_start_bin=self::bit_hex2bin(self::ip2hex('::ffff:0:0'));
		$mapped_end_bin=self::bit_hex2bin(self::ip2hex('::ffff:ffff:ffff'));
		$mapped_ip_bin=self::bit_hex2bin(self::ip2hex($ip));

		//var_dump($mapped_start_bin,$mapped_end_bin,$mapped_ip_bin);
		//var_dump(self::bit_compare($mapped_start_bin,$mapped_ip_bin));
		return self::bit_compare($mapped_start_bin,$mapped_ip_bin)<=0&&self::bit_compare($mapped_ip_bin,$mapped_end_bin)<=0;
	}
	// public static function is_ipv6_v4mapped($ip){
	// 	return is_ipv6($ip)&&(preg_match(self::regexp_ipv6_v4mapped, $ip)===false?false:true);
	// }
	public static function is_ipv4_int($ip){
		$max=pow(2, 32)-1;
		//var_dump($max);
		return is_numeric($ip)&&$ip<$max&&$ip>0&&(str_split($ip)[0]!=0);
	}

	//conanical ip input
	//output full-bit hex of ipv4/ipv6
	public static function ip2hex($ip){
		$unpack=unpack('H*',inet_pton($ip));

		return $unpack[1]?$unpack[1]:false;
	}

	public function getiptype($ip){
		if(self::is_ipv6_v4mapped($ip)){
			$this->ip_type=self::IP_IPV6_V4MAPPED;
		}elseif(self::is_ipv6($ip)){	
			$this->ip_type=self::IP_IPV6;	
		}elseif(self::is_ipv4_int($ip)){
			$this->ip_type=self::IP_IPV4_INT;
		}elseif (self::is_ipv4($ip)) {
			$this->ip_type=self::IP_IPV4;
		// }elseif(self::is_url($ip)){
		// 	$this->ip_type=self::IP_URL;
		}else{
			$this->ip_type=self::IP_INVALID;
		}
		return $this->ip_type;
	}

	public function getaddrip($data){
		if($this->ip_type==self::IP_UNINITIAL)return;

		switch ($this->ip_type) {
			case self::IP_IPV4:
				$trans_ip='::ffff:'.$data;
			break;
			case self::IP_IPV4_INT:
				$trans_ip='::ffff:'.long2ip($data);
			break;
			case self::IP_IPV6:
			case self::IP_IPV6_V4MAPPED:
				$trans_ip=$data;
			break;
			// case self::IP_URL:

			// break;
			default:
			$trans_ip=false;
		}

		return $trans_ip===false?false:inet_pton($trans_ip);
		
	}

	//canonical ip input
	public function getcalculateip($ip){
		//if($this->ip_type==self::IP_UNINITIAL)return;
		
		switch ($this->ip_type) {	
			case self::IP_IPV6_V4MAPPED:
			$ip=substr($ip, 7);
			break;
			case self::IP_IPV4_INT:
			$ip=long2ip($ip);
			break;
		}
		$hexip=self::ip2hex($ip);
		switch($this->ip_type){
			case self::IP_IPV6_V4MAPPED:
			case self::IP_IPV4_INT:
			case self::IP_IPV4:
			//default:
			$this->ips[self::O_IPV4_NORMAL]['normal']=$ip;
			$this->ips[self::O_IPV4_NORMAL]['hex']='0x'.$hexip;
			$this->ips[self::O_IPV4_NORMAL]['int']=hexdec($hexip);
			$this->ips[self::O_IPV4_NORMAL]['bin']=self::bit_hex2bin($hexip);
			$this->ips[self::O_IPV4_NORMAL]['octet']='0'.base_convert($hexip, 16, 8);
			$this->ips[self::O_IPV6_V4MAPPED]['normal']='::ffff:'.$ip;
			$this->ips[self::O_IPV6_V4MAPPED]['uncompressd']='0:0:0:0:0:ffff:'.$ip;
			$this->ips[self::O_IPV6_V4MAPPED]['fullyuncompressd']='0000:0000:0000:0000:0000:ffff:'.$ip;
			$this->ips[self::O_IPV6_V4MAPPED]['hex']='::ffff:'.substr($hexip, 0,4).':'.substr($hexip,4,4);
			break;
			
			case self::IP_IPV6:
			//$hexip=self::ip2hex($ip);
			//normal ipv6 is a full-bit hexadecimal
			$twodimen_array_ip=array_chunk(str_split($hexip), 4);
			$array_ip_uncompress=array_map(function($v){
				return implode('', $v);
			}, $twodimen_array_ip);
			$array_ip_compress=array_map(function($v){
				return preg_replace('/^(0*)([0-9a-f]+)$/i', "$2", implode('', $v));
			}, $twodimen_array_ip);
			$this->ips[self::O_IPV6_NORMAL]['normal']=$hexip;
			$this->ips[self::O_IPV6_NORMAL]['fullycompressed']=inet_ntop(pack('H*', $hexip));
			$this->ips[self::O_IPV6_NORMAL]['uncompressd']=implode(':',$array_ip_uncompress);
			$this->ips[self::O_IPV6_NORMAL]['compressd']=implode(':',$array_ip_compress);
				
			break;
			default:
			return false;
		}

		var_dump($this->ips);
	}

	public function querydb($addrip){
		$sql="select * from (SELECT * FROM `".self::IP_BLOCK_TABLE."` as a ,`".self::IP_LOC_TABLE."` as b  WHERE '{$addrip}' >=a.".self::FIELD_IP_START."  and a.geo_id=b.gid order by a.".self::FIELD_IP_START."  desc limit 3) as c where '{$addrip}'<=c.".self::FIELD_IP_END." limit 1";
		$result_set=$this->db->query($sql);
		$result=$result_set->fetch(PDO::FETCH_ASSOC);
		//var_dump($result);
		if($result!==false){
			$this->ips[self::O_DB_CONTINENT_CODE]=$result[self::FIELD_LOC_CONTINENT];
			$this->ips[self::O_DB_COUNTRY_CODE]=$result[self::FIELD_LOC_COUNTRY_CODE];
			$this->ips[self::O_DB_COUNTRY_ALPHA3]=$result[self::FIELD_COUNTRY_ALPHA3];
			$this->ips[self::O_DB_COUNTRY_ENAME]=$result[self::FIELD_COUNTRY_ENAME];
			$this->ips[self::O_DB_COUNTRY_CNAME]=$result[self::FIELD_COUNTRY_CNAME];
			$this->ips[self::O_DB_REGION_ENAME]=$result[self::FIELD_LOC_REGION_ENAME];
			$this->ips[self::O_DB_CITY]=$result[self::FIELD_LOC_CITY];
			var_dump($this->ips);
		}
		else{
			$this->set_wrong_db_msg();
		}
	}
	public static function  getip() {
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
	public function getlocation($ip){

	}

}

