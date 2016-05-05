<?php
require 'include.php';

var_dump(IPStat::get_list($redis,'desc'));

//IPStat::set_item($redis,'3.3.3.3');