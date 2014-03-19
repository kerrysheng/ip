<?php

$_CONFIG['db']['server'] = '127.0.0.1';
$_CONFIG['db']['user'] = 'root';
$_CONFIG['db']['password'] = '123456';
$_CONFIG['db']['database'] = 'test';

date_default_timezone_set ( 'asia/shanghai' );

error_reporting(E_ALL & ~ E_NOTICE);


$_CONFIG['api']['output_overtime']='已超过查询次数';
$_CONFIG['api']['output_wrongformat']='无法识别的数据';
$_CONFIG['api']['api_nokey_search_perday']=1000;