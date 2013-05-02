<?php

header('Content-Type: text/html; charset=UTF-8');
error_reporting(0);
include_once ('whois.main.php');
$q = isset($_GET['q']) ? $_GET['q'] : '';

if ($q) {
    $domainarr = explode('.', $q);
    $whois = new Whois();
    $result = $whois->Lookup(implode('.', $domainarr));

    while (!isset($result['regrinfo']['registered']) or $result['regrinfo']['registered'] == 'no') {
        if (count($domainarr) > 2 and !preg_match('#[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}#', $q)) {
            array_shift($domainarr);
            $result = $whois->Lookup(implode('.', $domainarr));
        } else {
            $result = $whois->Lookup(implode('.', $domainarr));
            break;
        }
    }

    if (!empty($result['rawdata'])) {
        foreach ($result['rawdata'] as &$v)
            $v = htmlspecialchars($v);
        echo '<p>' . implode($result['rawdata'], "<br/>") . '</p>';
    } else
        echo '<p>' . implode($whois->Query['errstr'], "\n</br>") . '</p>';

}
