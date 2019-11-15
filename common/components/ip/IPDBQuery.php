<?php

namespace common\components\ip;


class IPDBQuery
{

    private static $ip = null;

    private static $fp = null;

    private static $cached = array();

    public static function find($ip)
    {
        $ip = trim( $ip );
        if (empty($ip) === true) {
            return 'N/A';
        }
        $nip = gethostbyname($ip);
        $ipdot = explode('.', $nip);

        if ($ipdot[0] < 0 || $ipdot[0] > 255 || count($ipdot) !== 4) {
            return 'N/A';
        }

        if (isset(self::$cached[$nip]) === true) {
            return self::$cached[$nip];
        }

        if (self::$fp === null) {
            self::init();
        }

        self::$cached[$nip] = self::$ip->find( $ip , 'CN');

        return self::$cached[$nip];
    }


    private static function init()
    {
        if (self::$fp === null) {
            self::$ip = new \ipip\db\City( __DIR__ . '/ipipfree.ipdb' );
        }
    }

    public function __destruct()
    {
        if (self::$fp !== null) {
            self::$fp = null;
        }
    }
}