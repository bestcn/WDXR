<?php
namespace Wdxr\Models\Services;


class TimeService
{

    /**
     * 人性化的时间格式
     * @param $time
     * @return false|string
     */
    static public function humanTime($time)
    {
        $now_time = time();
        $show_time = self::isTimestamp($time) ? $time : strtotime($time);
        $dur = $now_time - $show_time;
        $last_year = mktime(23, 59, 59, 12, 31, date('Y') - 1);
        switch ($dur) {
            case $dur < 0:
                return date('Y-m-d H:i:s', $show_time);
                break;
            case $dur < 60:
                return $dur . '秒前';
                break;
            case $dur < 3600:
                return floor($dur / 60) . '分钟前';
                break;
            case $dur < 86400:
                return floor($dur / 3600) . '小时前';
                break;
            case $dur < 604800:
                return floor($dur / 86400) . '天前';
                break;
            case $show_time < $last_year:
                return date('Y-m-d', $show_time);
                break;
            default:
                return date('m-d', $show_time);
        }
    }

    /**
     * 理论上，任何一个整型数字都是一个合法的unix时间戳，带格式的时间格式也有可能是一个整型，但一般位数不满足10位
     * 因此确保参数一定是正数，再保证位数可基本确定
     * @param $time
     * @return bool
     */
    static public function isTimestamp($time)
    {
        return strlen(abs(intval($time))) === 10;
    }

    /**
     * 判断是否是一个时间格式的日期时间
     * @param $date
     * @return bool
     */
    static public function isDate($date)
    {
        return strlen(strtotime($date)) === 10;
    }

}