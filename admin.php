<?php
require_once 'include.php';

$ip_start = get_variable('ip1', 'gp');
$ip_end = get_variable('ip2', 'gp');
$ipr = new IPRange($_CONFIG['db']);
$ipr->get_range($ip_start, $ip_end);