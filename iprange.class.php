<?php

class IPRange extends IPLoc
{


    function __construct($db)
    {
        parent::__construct($db);
    }

    protected function query_db($ip_start, $ip_end, $lang = 'cn', $return_stat = true)
    {
        $ret_stmt = $this->db->prepare("select * from tbip where :ipstart<=ip2 AND :ipend>=ip1");
        $ret_stmt->bindParam('ipstart', $ip_start);
        $ret_stmt->bindParam('ipend', $ip_end);
        $db_time = new Spent_Time();
        $db_time->start();
        $ret_stmt->execute();
        $db_time->stop();
        $this->last_query_time = $db_time->spent();

        if ($return_stat) {
            return $ret_stmt;
        }

    }

    public function get_range($ip_start, $ip_end)
    {
        $range_ret = $this->query_db($ip_start, $ip_end);
        while ($record = $range_ret->fetch()) {
            var_dump($record,$this->last_query_time);
        }
    }
}