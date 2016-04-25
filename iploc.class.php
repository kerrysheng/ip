<?php

/**
 * ip location class
 */
class IPLoc
{
    protected $db_param = array();
    protected $db;
    //private $tb;
    //private $column;
    public $last_query_time = 0;

    function __construct($db)
    {
        $this->db_param['server'] = $db['server'];
        $this->db_param['port'] = $db['port'];
        $this->db_param['user'] = $db['user'];
        $this->db_param['password'] = $db['password'];
        $this->db_param['database'] = $db['database'];

        $dsn = "pgsql:dbname={$this->db_param['database']};host={$this->db_param['server']}";

        try {
            $this->db = new PDO($dsn, $this->db_param['user'], $this->db_param['password']);

        } catch (PDOException $e) {
            exit($e->getMessage());
        }

    }


    public static function get_ip()
    {
        $ip = false;

        if ($_SERVER ['HTTP_X_FORWARDED_FOR']) {
            $ips = explode(",", $_SERVER ['HTTP_X_FORWARDED_FOR']);
            foreach ($ips as $isip) {
                $isip = trim($isip);
                if ($isip && $isip != 'unknown') {
                    $ip = $isip;
                    break;
                }
            }
        }
        return $ip ? $ip : $_SERVER ['REMOTE_ADDR'];
    }

    protected function query_db($ip, $lang = 'cn', $return_stat = true)
    {

        $ret_stmt = $this->db->prepare("select k.*,ccountry.content as rcountry from (select * from tbip where :ip >= ip1 order by ip1 desc limit 1) as k left join tbipd as ccountry on k.country=ccountry.symbol and ccountry.language= :lang where k.ip2>= :ip ;");
        $ret_stmt->bindParam('ip', $ip);
        $ret_stmt->bindParam('lang', $lang);
        $db_exec_time = new Spent_Time();
        $db_exec_time->start();
        $ret_stmt->execute();
        $db_exec_time->stop();
        $this->last_query_time = $db_exec_time->spent();

        if ($return_stat)
            return $ret_stmt;

        $ret = $ret_stmt->fetch();
        return $ret;

    }

    public function get_location($ip)
    {
        $loc_ret = $this->query_db($ip);
        $ip_model = new IPLoc_Model();

        $ret = $loc_ret->fetch(PDO::FETCH_ASSOC);
        $ip_model->setCountry($ret['rcountry']);
        $ip_model->setRegion($ret['region']);
        $ip_model->setCity($ret['city']);
        $ip_model->setCounty($ret['county']);
        $ip_model->setIsp($ret['isp']);
        $ip_model->setCountryAlpha2($ret['country']);
        return $ip_model;
    }


}

class IPLoc_Model
{
    private $country;
    private $region;
    private $city;
    private $county;
    private $isp;
    private $country_alpha2;

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     */
    public function setCounty($county)
    {
        $this->county = $county == null ? '' : $county;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country == null ? '' : $country;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region == null ? '' : $region;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city == null ? '' : $city;
    }

    /**
     * @return mixed
     */
    public function getIsp()
    {
        return $this->isp;
    }

    /**
     * @param mixed $isp
     */
    public function setIsp($isp)
    {
        $this->isp = $isp == null ? '' : $isp;
    }

    /**
     * @return mixed
     */
    public function getCountryAlpha2()
    {
        return $this->country_alpha2;
    }

    /**
     * @param mixed $country_alpha2
     */
    public function setCountryAlpha2($country_alpha2)
    {
        $this->country_alpha2 = $country_alpha2 = null ? '' : $country_alpha2;
    }

    public function getAll()
    {
        return get_object_vars($this);
    }


}