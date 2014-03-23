<?php

/*
 * IP GEOLOCATION FINDER. PROGRAMMED BY SWIMM.
 * LM:2013/8/24
 */
require_once ("include.php");


$a=new ip2loc($db);
//$a->test();
//echo $a::bit_compare('11010010','11010010');
//echo $a::is_url('.a.com')?'a':'b';
//echo $a::ip2hex('::ff00');
//$a->getlocation('google.com');
$a->getcalculateip('::ffff:154.1.1.1');
var_dump($a->getresult());
//var_dump($a::is_ipv46_reserved('10.1.1.1',4));


?>
