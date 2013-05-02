<?php
require_once 'include.php';

$action = strtolower(gpcr ( 'action', 'g' ));
$type = strtolower(gpcr ( 'type', 'g' ));
$ip = gpcr ( 'ip', 'g' );
$callback = gpcr ( 'callback', 'g' );
$key=gpcr('k','g');
define('NOKEY_PERDAY',10000);

if($action=='show'){
	$tpl->display('api.htm');
}
else{
$ipl = new ip2location($db,false);
$userip=$ipl->getip();
$loc = $ipl->getlocation ( $ip );
$api = array ();
$date=date('Ymd');
$timenow=time();
if ($loc ['type'] != 0){
	$api ['code'] = 0;
	$ipl->querydb ( "INSERT INTO `sitehistory` VALUES(null,'{$userip}','{$loc['in1']}',{$timenow},2)" );
}
else
	$api ['code'] = 1;

//查询用户api
$e_sql=empty($key)?"`userip`='{$userip}'":"`key`='{$key}'";
echo $count_before=$ipl->querydb("SELECT count FROM `api_user` WHERE `date`={$date} AND {$e_sql}");

//generate array
$api['ip']=$loc['in2'];
$api['country']=$loc['ccname'];
$api['province']=$loc['rcname'];
$api['city']=$loc['ci'];
$api['isp']=$loc['isp'];


switch($type){
	case 'text':
		header('Content-Type:text/plain');
		array_shift($api);
		echo implode(' ',$api);
	break;
	case 'php':
		foreach($api as $k=>$v)$con[]=$k.'=>"'.$v.'"';
		echo '$blueera_ipapi=array('.implode(',',$con).');';
	break;
	case 'js':
		header('Content-Type:application/x-javascript');
		echo 'function get_blueera_country(){return "'.$api['country'].'";}'."\r\n";
		echo 'function get_blueera_province(){return "'.$api['province'].'";}'."\r\n";
		echo 'function get_blueera_city(){return "'.$api['city'].'";}'."\r\n";
		echo 'function get_blueera_isp(){return "'.$api['isp'].'";}'."\r\n";
	break;
	case 'json':
	default:
		
		if(empty($callback)){
		header('Content-Type:application/json');
		echo json_encode($api);}
		else{
		header('Content-Type:application/x-javascript');
		echo "if(window.{$callback}){".$callback.'('.json_encode($api).');}';
		}
}

}
?>
