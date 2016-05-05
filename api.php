<?php

require 'include.php';

$ip_addr = get_variable('ip', 'gp');
$format = get_variable('format', 'gp', 'json');
$cb = get_variable('callback', 'gp');
$api = array();

$ip_addr = empty($ip_addr) ? IPLoc::get_ip() : $ip_addr;

if (!filter_var($ip_addr, FILTER_VALIDATE_IP)) {
    $api['code'] = Ret_Code::RET_IP_ERROR;
    $api['msg']= Ret_Code::ERR_MSG[Ret_Code::RET_IP_ERROR];
} else {
    $ip_geo = new IPLoc($_CONFIG['db']);
    $ip_geo_result = $ip_geo->get_location($ip_addr);

    $redis_sto[$ip_addr]=time();

    IPStat::set_item($redis,$ip_addr);

    $api['code'] = 0;
    $api['ip'] = $ip_addr;
    $api['country'] = $ip_geo_result->getCountryAlpha2();
    $api['isp']=$ip_geo_result->getIsp();
    if (empty($ip_geo_result->getCity()) && !empty($ip_geo_result->getCounty())) {
        $location = array(
            $ip_geo_result->getCountry(),
            $ip_geo_result->getRegion(),
            $ip_geo_result->getCounty()
        );
        $area = array(
            $ip_geo_result->getCountry(),
            $ip_geo_result->getRegion(),
            $ip_geo_result->getCounty()
        );
        $api['location'] = implode(' ', array_filter($location, function ($v) {
            return !empty($v);
        }));
        $api['area'] = implode(' ', array_filter($area, function ($v) {
            return !empty($v);
        }));
    } else {
        $location = array(
            $ip_geo_result->getCountry(),
            $ip_geo_result->getRegion(),
            $ip_geo_result->getCity()
        );
        $area = array(
            $ip_geo_result->getCountry(),
            $ip_geo_result->getRegion(),
            $ip_geo_result->getCity(),
            $ip_geo_result->getCounty()
        );
        $api['location'] = implode(' ', array_filter($location, function ($v) {
            return !empty($v);
        }));
        $api['area'] = implode(' ', array_filter($area, function ($v) {
            return !empty($v);
        }));
    }
}


//$api = array_merge($api, $ip_geo_result->getAll());

switch ($format) {
    case 'json':
    default:
        if ($cb === '') {
            header('Content-Type:application/json');
            echo json_encode($api);
        } else {
            header('Content-Type:text/javascript');
            echo "if(window.{$cb}){" . $cb . "(" . json_encode($api) . ")};";
        }
        break;
    case 'text':
        header('Content-Type:text/plain');
        array_shift($api);
        echo implode("\t", $api);
}