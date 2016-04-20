<?php

require_once ("include.php");


$iploc=new IPLoc($_CONFIG['db']);
$iploc->get_location('60.182.14.1');