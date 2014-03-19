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
	const IP_IPV4_RESERVED=0x1006;

	const IP_IPV6=0x2001;
	const IP_IPV6_V4MAPPED=0x2002;
	const IP_IPV6_V4COMPATIBLE=0x2003;
	const IP_IPV6_6TO4=0x2004;
	const IP_IPV6_RESERVED=0x2005;

	const IP_URL=0x3001;
	const URL_IPV4=0x3002;
	const URL_IPV6=0x3003;

	const IP_UNINITIAL=0xF00;
	const IP_INVALID=0xF01;
	//const IP_IPV4_INVALID=0xF02;
	//const IP_IPV6_INVALID=0xF03;

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
	const O_URL_DOMAIN='domain';
	const O_URL_DNSTYPE='dnstype';
	const O_URL_RESOLVEIP='ip';
	//const O_URL_TTL='ttl';
	const O_URL_IPRESULT='ipresult';


	//generic
	private $raw;
	private $parsed_url;
	private $ips=array();
	//private $domains=array();
	private static $db=null; 
	private $location =array();
	private $wrong_mode;
	private $ip_type=self::IP_UNINITIAL;
	private $url_type=self::URL_UNINITIAL;


	//regexp of ip or url
	// public static $regexp_domain = '#^(?:[A-Za-z]+://)?((?:[\w\-]+\.)+(?:com|net|org|gov|int|edu|cn|info|cc|name|biz|tv|cn|mobi|sh|ac|io|tw|hk|ws|travel|us|tm|la|in|eu|it|jp|co|me|mx|ca|ag|am|asia|at|be|bz|de|es|fm|gs|jobs|ms|nl|nu|tc|tk|vg|ad|ae|af|ai|al|an|ao|aq|ar|as|au|aw|az|ba|bb|bd|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|cd|cf|cg|ch|ci|ck|cl|cm|cr|cu|cv|cx|cy|cz|dj|dk|dm|do|dz|ec|ee|eg|er|et|fi|fj|fk|fo|ga|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gt|gu|gw|gy|hm|hn|hr|ht|hu|id|ie|il|im|iq|ir|is|je|jm|jo|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|mt|mu|mv|mw|my|mz|na|nc|ne|nf|ng|ni|no|np|nr|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|si|sk|sl|sm|sn|sr|st|sv|sy|sz|td|tf|tg|th|tj|tl|tn|to|tr|tt|tz|ua|ug|uk|uy|uz|va|vc|ve|vi|vn|vu|wf|ye|yt|yu|za|zm|zw))(?::(\d)+)?(?:/.*)?$#';
	public static $regexp_domain ='/^(([a-z0-9-]+)\.)+([a-z]{2,6})$/i';
	public static $regexp_ipv4 = '/^(0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])(\.(0?0?\d|0?[1-9]\d|1\d\d|2[0-4]\d|25[0-5])){3}$/';

	//https://en.wikipedia.org/wiki/Reserved_IP_addresses
	private static $reserved_ipv4=array(
		'0.0.0.0/8','10.0.0.0/8','100.64.0.0/10',
		'127.0.0.0/8','169.254.0.0/16','172.16.0.0/12',
		'192.0.0.0/29','192.0.2.0/24','192.88.99.0/24',
		'192.168.0.0/16','198.18.0.0/15','198.51.100.0/24',
		'203.0.113.0/24','224.0.0.0/4','240.0.0.0/4','255.255.255.255/32');

	private static $reserved_ipv6=array('::/128','::1/128','::ffff:0:0/96','100::/64','64:ff9b::/96',
		'2001::/32','2001:10::/28','2001:db8::/32','2002::/16','fc00::/7','fe80::/10','ff00::/8');

	function __construct($db) {
		self::$db = $db;
		$db->exec("set names utf8");
	}

	protected function set_wrong_db_msg($extinguish='',$type=0){
		$separation='';
		switch ($type){
			case 0:default:$separation='<br>';
			break;
			case 1:$separation="\r\n";
		}
		$msg='DATABASE ERR INFO :'.$separation;
		$msg.='DB ERR NO :'.self::$db->errorCode().$separation;
		$msg.='DB ERR TERMINOLOGY :'.self::$db->errorInfo();

		var_dump(self::$db->errorInfo());
		die($msg);
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
		$origin_array=str_split($v);
		$c=count($origin_array);
		$re='';
		for($i=0;$i<=$c-4;$i+=4){
			$re.=base_convert($origin_array[$i].$origin_array[$i+1].$origin_array[$i+2].$origin_array[$i+3], 2, 16);
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
		//var_dump($uar,$ubr,$udiff);

	}
	//ip compare ;use bit_compare
	//conanial ip input ,bit_compare's return output
	public static function ip_compare($a,$b){
		$ax=self::bit_hex2bin(self::ip2hex($a));
		$bx=self::bit_hex2bin(self::ip2hex($b));

		return self::bit_compare($ax,$bx);
	}
	//convert CIDR formatted ip to its first and last ip address
	//compatible with ipv4/ipv6 and netmask compatible like /16 or 255.0.0.0
	//type maybe 0,1,2 
	public static function cidr2range($ipaddr,$netmask,$type=2){
		if(self::is_ipv4($ipaddr)){
			if($netmask<=32&&$netmask>0)$totalbit=32;
			else if(self::is_ipv4($netmask)){
				$binmask=self::bit_hex2bin( self::ip2hex($netmask));
				$netmask=strpos($binmask, "0");
				//note that there is no 1 after first 0 appeared
				$remain_check=strpos(substr($binmask, $netmask),'1');
				if($remain_check!==false)return false;
				$totalbit=32;
			}
			else return false;
		}else if(self::is_ipv6($ipaddr)&&$netmask<=128&&$netmask>0){
			$totalbit=128;
		}
		else return false;

		$binip=self::bit_hex2bin( self::ip2hex($ipaddr));

		$hostprefix=substr($binip, 0,$netmask);
		//binary format
		$firstip=str_pad($hostprefix, $totalbit,'0');
		$endip=str_pad($hostprefix, $totalbit,'1');

		for($tree=0;$tree<5;$tree++){

		if($tree===1){
			//hex format
			$firstip=self::bit_bin2hex($firstip);
			$endip =self::bit_bin2hex($endip);
		}
		if($tree===2){
			//readable format
			$firstip=self::hex2ip($firstip);
			$endip=self::hex2ip($endip);
		}

		if($tree===$type)break;
		}
		return array("first"=>$firstip,"end"=>$endip);
	}
	public static function parse_url_like($url=''){
		if(strpos($url,'://')===false)$url='http://'.$url;

		return parse_url($url)===false?flase:parse_url($url);
	}
	public  static function is_ipv4($ip){
		return filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)===false?false:true;
	}
	public static function is_ipv6($ip){
		return filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)===false?false:true;
	}
	public static function is_url($url){
		//return preg_match(self::$regexp_domain, $url)==false?false:true;
		$parsed_arr=self::parse_url_like($url);
		return $parsed_arr!==false&&preg_match(self::$regexp_domain, $parsed_arr['host']);
	}

	//improved version that widely used comparing to ripe defined ipv4-mapped ipv6
	//::ffff/96.These addresses are used to embed IPv4 addresses in an IPv6 address.
	public static function is_ipv6_v4mapped($ip){
		$mappend_start='::ffff:0:0';
		$mapped_end='::ffff:ffff:ffff';

		return self::is_ipv6($ip)&&self::ip_compare($mappend_start,$ip)<=0&&self::ip_compare($ip,$mapped_end)<=0;
	}

	public static function is_ipv4_int($ip){
		$max=pow(2, 32)-1;
		//var_dump($max);
		return is_numeric($ip)&&$ip<$max&&$ip>0&&(str_split($ip)[0]!=0);
	}
	public static function is_ipv46_reserved($ip,$type=4){
		
		switch($type){
			case 4:
			default:
			if(!self::is_ipv4($ip))return false;
			$const_reserved=self::$reserved_ipv4;
			break;
			case 6:
			if(!self::is_ipv6($ip))return false;
			$const_reserved=self::$reserved_ipv6;
		}
		$binip=self::bit_hex2bin( self::ip2hex($ip));

		foreach($const_reserved as $reserved){
			$ipmasks=explode('/', $reserved);
			$r =self::cidr2range($ipmasks[0],$ipmasks[1],0);

		 	if(self::bit_compare($r['first'],$binip)<=0&&self::bit_compare($binip,$r['end'])<=0)
		 		return true;
		}
		return false;
		//var_dump($reserved_arr);
	}

	//conanical ip input
	//output full-bit hex of ipv4/ipv6
	public static function ip2hex($ip){
		$unpack=unpack('H*',inet_pton($ip));

		return $unpack[1]?$unpack[1]:false;
	}
	public static function hex2ip($ip){
		return inet_ntop(pack("H*",$ip));
	}

	public function getiptype($ip){
		if($this->ip_type!=self::IP_UNINITIAL)return false;


		if(self::is_ipv6_v4mapped($ip)){
			$this->ip_type=self::IP_IPV6_V4MAPPED;
		}elseif(self::is_ipv6($ip)){	
			$this->ip_type=self::IP_IPV6;	
		}elseif(self::is_ipv4_int($ip)){
			$this->ip_type=self::IP_IPV4_INT;
		}elseif (self::is_ipv4($ip)) {
			$this->ip_type=self::IP_IPV4;
		}elseif(self::is_url($ip)){
			$this->ip_type=self::IP_URL;
		}else{
			$this->ip_type=self::IP_INVALID;
		}
		return $this->ip_type;
	}

	public function getaddrip($data=''){
		if($this->ip_type==self::IP_UNINITIAL)return false; 
		if(!$data)$data=$this->raw;
		if(!$data)return false;

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

	public function getcalculateip($input=''){
		if($this->ip_type==self::IP_UNINITIAL){
			if(!$input)return;
			else{
				$this->getiptype($input);
				$ip=$input;
			}
		}else $ip=$this->raw;
		
		switch ($this->ip_type) {	
			case self::IP_IPV6_V4MAPPED:
			//compatible with ipv6_v4mapped like ::ffff:abcd:abcd
			//or 0:0::ffff:1.1.2.3 etc. which isn't a uncanonical address
			$transfer=self::hex2ip(self::ip2hex($ip));
			$ip=substr($transfer, 7);
			break;
			case self::IP_IPV4_INT:
			$ip=long2ip($ip);
			break;
			case self::IP_INVALID:
			return false;
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
			$this->ips[self::O_IPV6_NORMAL]['fullycompressed']=self::hex2ip($hexip);
			$this->ips[self::O_IPV6_NORMAL]['uncompressd']=implode(':',$array_ip_uncompress);
			$this->ips[self::O_IPV6_NORMAL]['compressd']=implode(':',$array_ip_compress);
				
			break;
			// default:
			// return false;
		}

		//var_dump($this->ips);
	}

	private function querydb($addrip){
		$sql="select * from (SELECT * FROM `".self::IP_BLOCK_TABLE."` WHERE '{$addrip}' >=`".self::FIELD_IP_START."` order by `".self::FIELD_IP_START."` desc limit 3) as a,`".self::IP_LOC_TABLE."` as b,`".self::IP_COUNTRY_TABLE."` as c where '{$addrip}'<=a.".self::FIELD_IP_END." and a.geo_id=b.gid and b.ccode=c.alpha2 limit 1";
		//echo $sql;

		$result_set=self::$db->query($sql);
		
		//var_dump($result);
		if($result_set!==false){
			$result=$result_set->fetch(PDO::FETCH_ASSOC);
			if($result!==false){
			$this->ips[self::O_DB_CONTINENT_CODE]=$result[self::FIELD_LOC_CONTINENT];
			$this->ips[self::O_DB_COUNTRY_CODE]=$result[self::FIELD_LOC_COUNTRY_CODE];
			$this->ips[self::O_DB_COUNTRY_ALPHA3]=$result[self::FIELD_COUNTRY_ALPHA3];
			$this->ips[self::O_DB_COUNTRY_ENAME]=$result[self::FIELD_COUNTRY_ENAME];
			$this->ips[self::O_DB_COUNTRY_CNAME]=$result[self::FIELD_COUNTRY_CNAME];
			$this->ips[self::O_DB_REGION_ENAME]=$result[self::FIELD_LOC_REGION_ENAME];
			$this->ips[self::O_DB_CITY]=$result[self::FIELD_LOC_CITY];
			}else{

			}
			
			//var_dump($this->ips);
		}else{
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
	//get domain's URLs (only A and AAAA)(ipv4/ipv6)
	public function resolvedomain($domain=''){
		if($this->ip_type==self::IP_UNINITIAL){
			if(!$domain)return;
			else{
				$input=$domain;
			}
		}
		else $input=$this->raw;

		$parsed=self::parse_url_like($input);
		if($parsed&&($host=$parsed['host'])){
			 return $resolved=dns_get_record($host,DNS_A|DNS_AAAA);

		}else{
			return false;
		}
		
	}
	//get every location of ip that got by dns
	private function getdomainlocation($dnsresult){
		$count=count($dnsresult);
		if($count<=0)return false;

		for($k=0;$k<$count;$k++){
			//if($dnsresult[k]['type']
			$type=$dnsresult[$k]['type'];
			$ip= $type==='AAAA'?$dnsresult[$k]['ipv6']:$dnsresult[$k]['ip'];
			$theipinfo=self::_instance();
			$theipinfo->getlocation($ip);
			$theipinfo->getcalculateip();
			$this->ips[$k][self::O_URL_DOMAIN]=$dnsresult[$k]['host'];
			$this->ips[$k][self::O_URL_DNSTYPE]=$type;
			$this->ips[$k][self::O_URL_RESOLVEIP]=$ip;
			$this->ips[$k][self::O_URL_IPRESULT]=$theipinfo->getresult();

		}
		//var_dump($this->domains);
	}
	public static function _instance($db=null){
		if(!self::$db&&!$db)return null;
		return new self($db?$db:self::$db);
	}
	// entrance
	public function getlocation($input=''){
		if(self::IP_UNINITIAL==$this->ip_type&&!$input)return false;
		elseif($input){
			$this->raw=$input;
			$this->getiptype($input);
		}
				
		//echo $this->raw;
		

		switch ($this->ip_type) {
			case self::IP_URL:
			$this->getdomainlocation($this->resolvedomain());
			break;
			case self::IP_IPV6_V4MAPPED:
			case self::IP_IPV4_INT:
			case self::IP_IPV4:
			case self::IP_IPV6:

			return $this->querydb($this->getaddrip());
			break;
			case self:IP_INVALID:
			return false;

		}
	}
	public function getresult(){
		return $this->ips;
	}
	public function getlastinput(){
		return $this->raw;
	}

}

