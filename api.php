<?php
/* Mar 18 ,2014
 * IP Geolocation Finder 5.0
 * core ip api for public
 * author:shengyueming@gmail.com
 *
 *
 *
 *
 */
require_once 'include.php';

$type = strtolower ( gpcr ( 'type', 'g' ) );
$ip = trim(gpcr ( 'ip', 'g' ));
$callback = gpcr ( 'callback', 'g' );
$api=array();

if($type=='show'){

}else{
	$referer= parse_url($_SERVER['HTTP_REFERER']);
 	
 	$iploc=new ip2loc($db);
 	$userip=$iploc::getip();
 	$ip=empty($ip)?$userip:$ip;
 	$user_identity=$referer&&array_key_exists('host', $referer)?$referer['host']:$userip;

 	if(get_api_permission($db,$user_identity,$_CONFIG['api']['api_nokey_search_perday'])){
 		$api['code']=0;
 	switch($type){

 		case 'calc':
 		$iploc->getcalculateip($ip);
 		$result=$iploc->getresult();
 		switch($result['type']){
 			case $iploc::IP_IPV6:
 			case $iploc::IP_IPV6_RESERVED:	
 			$api['ip']=$result['raw'];
 			$api['type']=$result['type'];
 			$api['ipv6_calc']=$result['ipv6'];
 			break;
 			case $iploc::IP_IPV4:
 			case $iploc::IP_IPV4_INT:
 			case $iploc::IP_IPV4_RESERVED:
 			case $iploc::IP_IPV6_V4MAPPED:
 			$api['ip']=$result['raw'];
 			$api['type']=$result['type'];
 			$api['ipv4_calc']=$result['ipv4'];
 			$api['ipv6_v4mapped_calc']=$result['ipv6v4mapped'];
 			break;

 			default:
 			case $iploc::IP_NOT_CALC_IP:
 			$api['code']=1;
 			$api['msg']=$_CONFIG['api']['output_nocalculateip'];

 		}
 		break;
 		default:
 		$iploc->getlocation($ip);
 		$result=$iploc->getresult();
 		
 		switch($result['type']){
 			case $iploc::IP_IPV4:
 			case $iploc::IP_IPV6:
 			case $iploc::IP_IPV6_V4MAPPED:
 			case $iploc::IP_IPV6_RESERVED:
 			case $iploc::IP_IPV4_RESERVED:
 			case $iploc::IP_IPV4_INT:
 			$api['ip']=$result['raw'];
 			$api['type']=$result['type'];
 			$api['continent']=$_CONFIG['continent'][$result['cocode']][1];
 			$api['country']=$result['ccname'];
 			$api['region']=$result['rname'];
 			$api['city']=$result['ciname'];
 			$api['isp']=$result['isp'];
 			break;
 			case $iploc::IP_URL:
 			$api['type']=$result['type'];
 			$api['domain']=$result['raw'];
 			$api['data']=array();
 			$eachip=array();
 			foreach ($result['result'] as $key => $value) {
 				$eachip['ip']=$value['ip'];
 				$eachip['type']=$value['dnstype'];
 				$eachip['continent']=$_CONFIG['continent'][$value['cocode']][1];
 				$eachip['country']=$value['ccname'];
 				$eachip['region']=$value['rname'];
 				$eachip['city']=$value['ciname'];
 				$eachip['isp']=$value['isp'];
 				array_push($api['data'], $eachip);
 			}
 			break;
 			case $iploc::IP_INVALID:
 			default:
 			$api['code']=1;
 			$api['msg']=$_CONFIG['api']['output_wrongformat'];
	
 		}
 	}
 			
 	}
 	else{
 		$api['code']=1;
 		$api['msg']=$_CONFIG['api']['output_overtime'];
 	}

 	switch($type){
 		case 'text':
 		header('Content-Type: text/plain');
 		array_shift($api);
 		if(array_key_exists('data', $api)){
 			foreach($api['data'] as $k =>&$v){
 				$v=implode(' ', $v);
 			}
 			$api['data']=implode("\r\n", $api['data']);
 		}
 		echo implode("\r\n", $api);
 		break;
 		case  'js':
 		header ( 'Content-Type:application/javascript' );
 		$get_jsfunction=get_jsfunction($api);
 		if(count($get_jsfunction)>0){
 			echo implode("\r\n", $get_jsfunction);
 		}
 		
 		break;
 		case 'json':
 		case 'calc':
 		default:
 		if(empty($callback)){
 			header ( 'Content-Type:application/json' );
 			echo json_encode ( $api);
 		}else{//jsonp
 			header ( 'Content-Type:application/javascript' );
 			echo 'if(window.'.$callback.'){'.$callback.'('.json_encode($api).');};';
 		}
 		

 	}
 	 //var_dump($result);
}
function get_jsfunction($source,$dup=0){
	$res=array();

 		foreach ($source as $k=>$v) {
 			if(is_array($v)){
 				$sub_res=get_jsfunction($v,is_numeric($k)?++$dup:0);
 				$res=array_merge($res,$sub_res);
 			}
 			else array_push($res, 'function get_blueera_'.$k.($dup?'_'.$dup:'').'{return "'.$v.'"};');
 		}
 		return $res;
}

function get_api_permission(PDO $db,$identity,$max_search=1000){
		$date = date ( 'Ymd' );
		$count_today = $db->query( "SELECT `count` FROM `api_user` WHERE `date`={$date} AND `identify`='{$identity}' limit 1");
		if($count_today===false){
			die('db error');
		}
		$result=$count_today->fetch();
	if ($result&&$result ['count'] > 0 && $result  ['count'] < $max_search) {
		$db->exec( "UPDATE `api_user` SET `count`=`count`+1  WHERE `date`={$date} AND `identify`='{$identity}'" );
		return $result ['count'] ;
	} elseif (!$result) {
		$db->exec ( "INSERT INTO `api_user` VALUES (null,'{$identity}','{$date}',1)" );
		return 1;
	}
	else {
		return 0;
	}
}