<?php
ob_start();
include_once 'include.php';

Dns::get_address('ipip.net',$_CONFIG['dns'][0][1]);