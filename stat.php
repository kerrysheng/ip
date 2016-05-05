<?php
require 'include.php';

$type = get_variable('type', 'gp');

$hours = 3 * 24;

switch ($type) {
    case 'recent':
    default:
        echo json_encode(IPStat::get_list('desc'));
        break;
    case 'hourly':
        for ($i = 0; $i < $hours; $i++) {
            $time = date("YmdH", strtotime('-' . $i . ' hours'));
            $hourly_output[] = IPStat::get_total_hourly_query($time);
        }
        echo json_encode($hourly_output);
        break;
}

