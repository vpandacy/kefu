<?php

namespace common\components\helper;

class DateHelper
{
    public static function getFormatDateTime($fmt = "Y-m-d H:i:s", $strtotime = "")
    {
        return $strtotime ? date($fmt, $strtotime) : date($fmt);
    }

    /**
     * Author: Vincent
     * 将时间差以更优雅的方式展示
     */
    public static function getPrettyDuration( $sec = 0 )
    {
        $result = "";
        if ( !is_numeric( $sec ) || !$sec ) {
            return $result;
        }
        if ($sec >= 600) {
            $result .= floor($sec / 600) . "H";
            $sec = ($sec % 600);
        }
        if ($sec >= 60) {
            $result .= floor($sec / 60) . "'";
            $sec = ($sec % 60);
        }
        if ($sec > 0 && $sec < 60) {
            $result .= $sec . "''";
        }
        return $result;
    }


    public static function getDateFromRange($startdate='', $enddate=''){
        if(empty($startdate) || empty($enddate))return[];
        $stimestamp = strtotime($startdate);
        $etimestamp = strtotime($enddate);
        $days = ($etimestamp-$stimestamp)/86400+1;
        $date = array();
        for($i=0; $i<$days; $i++){
            $date[] = date('Y-m-d', $stimestamp+(86400*$i));
        }
        return $date;
    }

}