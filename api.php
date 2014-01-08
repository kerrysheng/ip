<?php
require_once 'include.php';

$action = strtolower ( gpcr ( 'action', 'g' ) );
$type = strtolower ( gpcr ( 'type', 'g' ) );
$ip = gpcr ( 'ip', 'g' );
$callback = gpcr ( 'callback', 'g' );
$key = gpcr ( 'k', 'g' );
define ( 'NOKEY_PERDAY', 1000 );
$err_op = array (
		1 => '已超过查询次数',
		2 => 'IP或域名错误' 
);

if ($action == 'show') {
	$tpl->display ( 'api.htm' );
} else {
	$ipl = new ip2location ( $db, false );
	$userip = $ipl->getip ();
	$referer= preg_match('/^(?:[\w]{2,}:\/\/)([^\/\:\#\?]*)/',$_SERVER['HTTP_REFERER'],$ref);
	$date = date ( 'Ymd' );
	$timenow = time ();
	$api = array ();
	$api ['code'] = 0;
	// 查询用户资格
	//$e_sql = empty ( $key ) ? "`userip`='{$userip}'" : "`key`='{$key}'";
	$e_sql =!empty($key)?$key:($referer?$ref[1]:$userip);
	//die($e_sql);
	$count_before = $ipl->querydb ( "SELECT count FROM `api_user` WHERE `date`={$date} AND `identify`='{$e_sql}'" );
	if ($count_before [0] ['count'] > 0 && $count_before [0] ['count'] < NOKEY_PERDAY) {
		$ipl->querydb ( "UPDATE `api_user` SET `count`=`count`+1  WHERE `date`={$date} AND `identify`='{$e_sql}'" );
		$can_search = true;
	} elseif ($count_before [0] [count] == 0) {
		$ipl->querydb ( "INSERT INTO `api_user` VALUES (null,'{$e_sql}','{$date}',1)" );
		$can_search = true;
	} else {
		$can_search = false;
		$api ['code'] = 1;
		$api ['msg'] = $err_op [1];
	}

	//有使用权限	
	if ($can_search) {
		$loc = $ipl->getlocation ( $ip );
		
		if ($loc ['type'] != 0) {
			$ipl->querydb ( "INSERT INTO `sitehistory` VALUES(null,'{$userip}','{$loc['in1']}',{$timenow},2)" );
			
			// generate array
			$api ['ip'] = $loc ['in2'];
			$api ['country'] = $loc ['ccname'];
			$api ['province'] = $loc ['rcname'];
			$api ['city'] = $loc ['ci'];
			$api ['isp'] = $loc ['isp'];
		} else {
			$api ['code'] = 1;
			$api ['msg'] = $err_op [2];
		}
	}

	//制定输出格式	
	switch ($type) {
		case 'text' :
			header ( 'Content-Type:text/plain' );
			array_shift ( $api );
			echo implode ( ' ', $api );
			break;
			// case 'php':
			// foreach($api as $k=>$v)$con[]=$k.'=>"'.$v.'"';
			// echo '$blueera_ipapi=array('.implode(',',$con).');';
			break;
		case 'js' :
			header ( 'Content-Type:application/x-javascript' );
			echo 'function get_blueera_status(){return ' . ($api ['code'] == 0 ? 'true' : 'false') . '}' . "\r\n";
			if ($api ['code'] == 0)
				echo 'function get_blueera_country(){return "' . $api ['country'] . '"}' . "\r\n",
			 'function get_blueera_province(){return "' . $api ['province'] . '"}' . "\r\n",
			 'function get_blueera_city(){return "' . $api ['city'] . '"}' . "\r\n",
			 'function get_blueera_isp(){return "' . $api ['isp'] . '"}' . "\r\n";
			break;
		case 'json' :
		default :
			
			if (empty ( $callback )) {
				header ( 'Content-Type:application/json' );
				echo json_encode ( $api );
			} else {
				header ( 'Content-Type:application/x-javascript' );
				echo "if(window.{$callback}){" . $callback . '(' . json_encode ( $api ) . ');}';
			}
	}
}
?>
