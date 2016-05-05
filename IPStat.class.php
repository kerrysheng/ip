<?php


class IPStat
{
    const IP_LIST_KEY_NAME = 'ipGeoRecentList';

    const IP_STORE_LEN = 50;
    const IP_QUERY_LEN = 10;

    public static function set_item(Predis\Client $redis, $value)
    {
        $key = self::IP_LIST_KEY_NAME;

        return $redis->zadd($key, array($value => time()));
    }

    public static function get_list(Predis\Client $redis, $order = 'asc', $count = self::IP_QUERY_LEN)
    {

        $key = self::IP_LIST_KEY_NAME;

        if ((int)$count > self::IP_QUERY_LEN) $count = self::IP_QUERY_LEN;

        if ($order == 'asc') {
            //return $redis->zrange($key, 0, -1);
            return $redis->zrangebyscore($key, 0, '+inf', array('limit' => array(0, $count)));
        } else {
            //return $redis->zrevrange($key, 0, -1);
            return $redis->zrevrangebyscore($key, '+inf', 0, array('limit' => array(0, $count)));//文档有误
        }
    }
}