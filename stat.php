<?php
require 'include.php';

var_dump(IPStat::get_list('desc'));

//var_dump(IPStat::get_per_ip_list());
var_dump(IPStat::get_total_hourly_query());