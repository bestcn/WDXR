<?php
namespace Wdxr;


class Random
{

    /**
     * 生成固定位字符串随机数
     * @param $min
     * @param $max
     * @return string
     */
    static public function random_numeric($min, $max)
    {
        $number = random_int($min, $max);

        $prefix = '';
        if(($len = strlen($max) - strlen($number)) != 0) {
            $prefix = str_repeat('0', $len);
        }

        return $prefix . $number;
    }

}