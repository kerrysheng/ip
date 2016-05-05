<?php

$_CONFIG['db']['server'] = '127.0.0.1';
$_CONFIG['db']['user'] = 'postgres';
$_CONFIG['db']['password'] = '';
$_CONFIG['db']['database'] = 'postgres';

$_CONFIG['redis']['server']='127.0.0.1';

date_default_timezone_set('asia/shanghai');

error_reporting(E_ALL & ~E_NOTICE);

$_CONFIG['tb']['ip'] = 'tbip';
$_CONFIG['column']['ip_start'] = 'ip1';
$_CONFIG['column']['ip_end'] = 'ip2';
$_CONFIG['column']['country'] = 'country';
$_CONFIG['column']['region'] = 'region';
$_CONFIG['column']['city'] = 'city';
$_CONFIG['column']['isp'] = 'isp';

$_CONFIG['tb']['loc']='tbipd';
$_CONFIG['column']['definition']='';
$_CONFIG['column']['value']='';

$_CONFIG['dns']=array(
    array('安徽电信','61.132.163.68','61.132.163.68'),
    array('广东电信','202.96.128.86','202.96.134.33')
);
