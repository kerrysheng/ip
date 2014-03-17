<?php

/*
 * IP GEOLOCATION FINDER. PROGRAMMED BY SWIMM.
 * LM:2013/8/24
 */
require_once ("include.php");


$a=new ip2loc(new PDO('mysql:host=localhost;dbname=test','root','123456'));
//$a->test();
//echo $a::bit_compare('11010010','11010010');
//echo $a::is_ipv6_v4mapped('::ffff:255.255.255.255')?'a':'b';
//echo $a::ip2hex('::ff00');
echo $a->getcalculateip('1.3.3.254')?'a':'b';
//$st = $timerun->start ();
// session_start;
//$as ['version'] = '4.3'; // Version
//$as ['timenow'] = time();

// $ipl = new ip2location ( $db );
// $as ['yourip'] = $userip = $ipl->getip ();

// if (isset ( $_COOKIE ['uiploc'] ) && strpos ( $_COOKIE ['uiploc'], $userip ) !== false) {
// 	$myloc = explode ( '|', $_COOKIE ['uiploc'] );
// 	$as ['youraddr'] = $myloc [1];
// 	//$as ['myflag']=$myloc[2];
// } else {
// 	$myloc = $ipl->getlocation ();
// 	$as ['youraddr'] = $myloc ['ccname'];
// 	//$cflag_file = '/static/images/flags/' . strtolower ( $myloc ['c'] ) . '.png';
// 	//$as ['myflag'] = file_exists ( SROOT . $cflag_file ) ? '<img src="' . AURL . $cflag_file . '" alt="'.$myloc['ccname'].'"/>' : '';
// 	setcookie ( 'uiploc', "{$userip}|{$as ['youraddr']}", time () + 10 * 24 * 3600 );
// }

// $uos = getos ();
// $useros = $uos ['img'] == '' ? '' : '<img src="' . AURL . '/static/images/os/' . $uos ['img'] . '" alt="' . $uos ['title'] . '"/>';
// $ubrowser = getbrowser ();
// $userbro = $ubrowser ['img'] == '' ? '' : '<img src="' . AURL . '/static/images/browser/' . $ubrowser ['img'] . '" alt="' . $ubrowser ['title'] . '" />';
// $as ['browser'] ['img'] = $userbro;
// $as ['browser'] ['title'] = $ubrowser ['title'];
// $as ['os'] ['img'] = $useros;
// $as ['os'] ['title'] = $uos ['title'];

// $ipaddr = trim ( gpcr ( "ip", 'g' ) );
// if ($ipaddr) {
// 	$iploc = $ipl->getlocation ( quan_convert ( $ipaddr ) );
// 	// print_r($iploc);
// 	$goto = $iploc ['type'];
// 	$as ['ip1'] = $inputproprity = $iploc ['in1'];
// 	$as ['ip2'] = $iploc ['in2'];
	
// 	// 有返回结果
// 	if ($goto) {
// 		//$timestampnow = time();
// 		$ipl->querydb ( "INSERT INTO `sitehistory` VALUES(null,'{$as ['yourip']}','{$as ['ip1']}',{$as ['timenow']},1)" );
// 		// ################搜索历史cookie存取#####################
// 		$exptime = time () + 10 * 3600 * 24;
// 		$num_of_list = 8;
// 		$history_cname = 'search_history';
// 		if (! isset ( $_COOKIE [$history_cname] ))
// 			setcookie ( $history_cname, $inputproprity, $exptime );
// 		else {
// 			$cval = explode ( '|', $_COOKIE [$history_cname] );
			
// 			if (in_array ( $inputproprity, $cval ))
// 				foreach ( $cval as $k => $v )
// 					$v == $inputproprity ? array_splice ( $cval, $k, 1 ) : '';
			
// 			array_unshift ( $cval, $inputproprity );
			
// 			while ( count ( $cval ) > $num_of_list )
// 				array_pop ( $cval );
			
// 			setcookie ( $history_cname, implode ( '|', $cval ), $exptime );
// 		}
// 		// ################end搜索历史cookie存取#####################
// 		$cflag_file = '/static/images/flags/' . strtolower ( $iploc ['c'] ) . '.png';
// 		$as ['cflag'] = file_exists ( SROOT . $cflag_file ) ? '<img src="' . AURL . $cflag_file . '" id="cflag"/>' : '';
// 		$tpl->assign ( 'lo', $iploc );
// 	}
// }

// $tpl->assign ( 'goto', isset ( $goto ) ? $goto : 0 );
// $et = $timerun->stop ();
// $as ['processtime'] = $timerun->spent ();
// $tpl->assign ( $as );
// $tpl->display ( 'ip.htm' );

?>
