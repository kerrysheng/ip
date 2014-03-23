<?php

$_CONFIG['db']['server'] = '127.0.0.1';
$_CONFIG['db']['user'] = 'root';
$_CONFIG['db']['password'] = '123456';
$_CONFIG['db']['database'] = 'test';

date_default_timezone_set ( 'asia/shanghai' );

error_reporting(E_ALL & ~ E_NOTICE);


$_CONFIG['api']['output_overtime']='已超过查询次数';
$_CONFIG['api']['output_wrongformat']='IP或域名错误';
$_CONFIG['api']['output_nocalculateip']='没有可以计算的IP';
$_CONFIG['api']['api_nokey_search_perday']=1000;

$_CONFIG['continent']['AF']=array('Africa','非洲');
$_CONFIG['continent']['AS']=array('Asia','亚洲');
$_CONFIG['continent']['EU']=array('Europe','欧洲');
$_CONFIG['continent']['AN']=array('Antarctica','南极洲');
$_CONFIG['continent']['OC']=array('Oceania','大洋洲');
$_CONFIG['continent']['NA']=array('North America','北美洲');
$_CONFIG['continent']['SA']=array('South America','南美洲');