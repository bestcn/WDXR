<?php
namespace Wdxr;

use Phalcon\Mvc\User\Component;

class Time extends Component
{

    /**
     * 获取上个月月份
     * @return false|string
     */
    public static function getLastMonth()
    {
        $last_month = strtotime('last month');
        return intval(date('m', $last_month));
    }

    /**
     * 获取两个日期之间的天数
     * @param $start
     * @param $end
     * @return float|int
     */
    public static function getDays($start, $end)
    {
        $start_time = strtotime($start);
        $end_time = strtotime($end);

        $days = ($end_time - $start_time) / 86400 + 1;
        return $days;
    }

    /**
     * 获取零点的unix时间戳
     * @param $time
     * @return false|int
     */
    public static function getFloorTime($time)
    {
        return strtotime(date('Ymd', $time));
    }

    public static function getEndTime($time)
    {
        return Time::getFloorTime($time) + 86400 -1;
    }
}