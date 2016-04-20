<?php

/**
 * ipv4 general class
 */
class IP
{
    const RESERVED_IPV4 = array(
        '0.0.0.0/8', '10.0.0.0/8', '100.64.0.0/10',
        '127.0.0.0/8', '169.254.0.0/16', '172.16.0.0/12',
        '192.0.0.0/24', '192.0.2.0/24', '192.88.99.0/24',
        '192.168.0.0/16', '198.18.0.0/15', '198.51.100.0/24',
        '203.0.113.0/24', '224.0.0.0/3');
    //reference https://en.wikipedia.org/wiki/Reserved_IP_addresses


    static $reserved_ipv4_int = array();
    static $reserved_ipv4_total = 0;

    public static function ip2decimal($ip)
    {
        return unpack('N', inet_pton($ip))[1];
    }

    public static function decimal2ip($iplong)
    {
        return inet_ntop(pack('N', $iplong));
    }

    public static function trans_to_int_reserved()
    {
        self::$reserved_ipv4_total = count(self::RESERVED_IPV4);
        for ($i = 0; $i < self::$reserved_ipv4_total; $i++) {
            $ip_parameter = explode('/', self::RESERVED_IPV4[$i]);
            $ip_range = self::cidr2range($ip_parameter[0], $ip_parameter[1]);
            self::$reserved_ipv4_int[$i][0] = self::ip2decimal($ip_range[0]);
            self::$reserved_ipv4_int[$i][1] = self::ip2decimal($ip_range[1]);

        }
    }

    public static function cidr2range($ipaddr, $netmask = 32)
    {
        $iparray = array();
        $ip2decimal = self::ip2decimal($ipaddr);


        $iparray[0] = self::decimal2ip($ip2decimal & (-1 << (32 - $netmask)));
        $iparray[1] = self::decimal2ip($ip2decimal | (~(-1 << (32 - $netmask))));

        return $iparray;
    }


    public static function is_reserved($ip, $intip = false)
    {
        $flag = false;

        if (!self::$reserved_ipv4_total) {
            self::trans_to_int_reserved();
        }

        $ip_compare = !$intip ? self::ip2decimal($ip) : $ip;

        for ($i = 0, $k = self::$reserved_ipv4_total; $i < $k; $i++) {
            $first_compare = self::$reserved_ipv4_int[$i][0];
            $last_compare = self::$reserved_ipv4_int[$i][1];

            if ($ip_compare >= $first_compare && $ip_compare <= $last_compare) {
                $flag = true;
                break;
            }
        }
        return $flag;

    }
}