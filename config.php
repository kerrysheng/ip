<?php

$_CONFIG['db']['server'] = '127.0.0.1';
$_CONFIG['db']['user'] = 'postgres';
$_CONFIG['db']['password'] = '';
$_CONFIG['db']['database'] = 'postgres';

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
