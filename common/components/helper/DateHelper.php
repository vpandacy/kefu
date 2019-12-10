<?php

namespace common\components\helper;

class DateHelper
{
    public static function getFormatDateTime($fmt = "Y-m-d H:i:s", $strtotime = "")
    {
        return $strtotime ? date($fmt, $strtotime) : date($fmt);
    }

    public static function completingTimeByMinutes($minutes)
    {

        $result = "";
        if (!is_numeric($minutes)) {
            return "";
        }
        if ($minutes >= 600) {
            $result .= floor($minutes / 600) . "天";
            $minutes = ($minutes % 600);
        }
        if ($minutes >= 60) {
            $result .= floor($minutes / 60) . "小时";
            $minutes = ($minutes % 60);
        }
        if ($minutes > 0 && $minutes < 60) {
            $result .= $minutes . "分";
        }
        return $result;
    }

    /**
     * Author: Vincent
     * 将时间差以更优雅的方式展示
     * 例如x天y小时z分钟
     *
     */
    public static function getBeautyDateTimeDesc($to_stamps = 0, $from_stamps = 0)
    {
        if (!$from_stamps) {
            $from_stamps = time();
        }

        $diff_time = $to_stamps - $from_stamps;
        $result = "<font>";
        if( $diff_time <= 0 ){
            $result = "<font style='color:red;'>超期 ";
        }

        $diff_time = abs($diff_time);
        if ($diff_time >= 86400) {
            $result .= floor($diff_time / 86400) . "天";
            $diff_time = ($diff_time % 86400);
        }
        if ($diff_time >= 3600) {
            $result .= floor($diff_time / 3600) . "小时";
            $diff_time = ($diff_time % 3600);
        }
        if ($diff_time >= 60) {
            $result .= floor($diff_time / 60) . "分";
        }

        return $result."</font>";
        //return self::completingTimeByMinutes($diffTime);
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