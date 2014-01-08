<?php
require_once ("include.php");

$num = gpcr ( 'num', 'g', '1' );
$pagesize = 200;
$ipl = new ip2location($db);
$rc = $ipl->queryfirst ( "select  count(distinct search) as count from sitehistory" );
$totalnum = $rc ['count'];
$totalp = ceil ( $totalnum / $pagesize );
$num > $totalp ? $num = $totalp : ($num < 1 ? $num = 1 : '');

$offset = ($num - 1) * $pagesize;

$res = $ipl->querydb ( "select `search` from `sitehistory` group by `search` order by `id` desc limit {$offset},{$pagesize}" );

$tpl->assign ( 'tp', $totalp );
$tpl->assign ( 'list', $res );
$tpl->display ( 'list.htm' );

?>
