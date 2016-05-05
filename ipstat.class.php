<?php

/*
 * ipGeoRecentList 最近查询IP列表
 *
 * ipGeoPerIp{unix_timestamp} {ip} 每个ip每秒访问次数
 *
 * ipGeoHourlyTotal{date+hour} {format}每个格式每小时访问次数
 *
 * */

class IPStat
{
    const IP_LIST_KEY_NAME = 'ipGeoRecentList';
    const PER_IP_QUERY_PREFIX = 'ipGeoPerIp';
    const TOTAL_QUERY_HOURLY_PREFIX = 'ipGeoHourlyTotal';

    const IP_STORE_LEN = 50;
    const IP_QUERY_LEN = 10;
    static $redis_instance = null;
    static $stat_date_hour = 0;

    const QUERY_PER_SECOND = 30;

    public static function get_redis_instance()
    {
        if (self::$redis_instance) {
            return self::$redis_instance;
        } else if ($GLOBALS['redis']) {
            $redis = $GLOBALS['redis'];
        } else {
            try {
                $redis = new Predis\Client();
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        self::$redis_instance = $redis;
        return $redis;
    }


    public static function get_per_ip_query_key()
    {
        return self::PER_IP_QUERY_PREFIX . time();
    }

    public static function get_total_hourly_query_key($date = null)
    {
        if ($date == null) {
            self::$stat_date_hour = date('YmdH');
        } else {
            self::$stat_date_hour = $date;
        }
        return self::TOTAL_QUERY_HOURLY_PREFIX . self::$stat_date_hour;

    }

    public static function set_item($value)
    {
        $key = self::IP_LIST_KEY_NAME;
        $redis = self::get_redis_instance();

        return $redis->zadd($key, array($value => time()));
    }

    public static function get_list($order = 'asc', $count = self::IP_QUERY_LEN)
    {
        $redis = self::get_redis_instance();
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

    public static function set_per_ip_query($field)
    {
        $key = self::get_per_ip_query_key();
        $redis = self::get_redis_instance();
        $ret = $redis->hincrby($key, $field, 1);
        $redis->expire($key, 1);
        return $ret;
    }

    public static function get_per_ip_query($field)
    {
        $key = self::get_per_ip_query_key();
        $redis = self::get_redis_instance();
        return $redis->hget($key, $field);

        //return $redis->hgetall($key);
    }

    public static function set_total_hourly_query($field = 'json')
    {
        $redis = self::get_redis_instance();
        $key = self::get_total_hourly_query_key();

        return $redis->hincrby($key, $field, 1);
    }

    public static function get_total_hourly_query($date)
    {

        $redis = self::get_redis_instance();
        $key = self::get_total_hourly_query_key($date);
        $ret = $redis->hgetall($key);
        return array('date' => self::$stat_date_hour, 'ret' => $ret);
    }

    public static function is_query_available($user_ip)
    {
        if (self::get_per_ip_query($user_ip) > self::QUERY_PER_SECOND) {
            return false;
        } else {
            return true;
        }
    }
}