<?php
namespace Phalcon\Validation\Validator;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class LicenseNum extends Validator
{

    public function validate(Validation $validation, $attribute)
    {
        $value = $validation->getValue($attribute);
        $message = ($this->hasOption('message')) ? $this->getOption('message') : '注册号或统一社会信用代码不正确';

        if(self::isValidBusCode($value) === false) {
            $validation->appendMessage(new Message($message, $attribute, 'LicenseNum'));
            return false;
        }

        return true;
    }

    /**
     * 验证营业执照
     * @param $busCode
     * @return bool
     */
    static function isValidBusCode($busCode)
    {
        if(strlen($busCode)==15) {
            $s = [];
            $p = [];
            $a = [];

            $m = 10;
            $p[0] = $m;

            for($i=0; $i < strlen($busCode); $i++) {
                $a[$i] = intval(substr($busCode, $i, 1));
                $s[$i] = ($p[$i] % ($m+1)) + $a[$i];
                if(0 == $s[$i] % $m){
                    $p[$i+1] = 10 * 2;
                } else {
                    $p[$i+1] = ($s[$i] % $m) * 2;
                }
            }

            if(1 == ($s[14] % $m)){
                //营业执照编号正确!
                $ret = true;
            } else {
                //营业执照编号错误!
                $ret = false;
            }
        } elseif(strlen($busCode) == 18) {
            $ret = self::check_group($busCode);
        } else {
            $ret=false;
        }
        return $ret;
    }

    /**
     * 营业执照统一社会信用代码
     * @param $str
     * @return bool
     */
    static function check_group($str)
    {
        $one = '159Y';//第一位可以出现的字符
        $two = '12391';//第二位可以出现的字符
        $str = strtoupper($str);
        if(!strstr($one,$str[1]) && !strstr($two,$str[2]) && !empty($array[substr($str,2,6)])){
            return false;
        }
        $wi = array(1,3,9,27,19,26,16,17,20,29,25,13,8,24,10,30,28);//加权因子数值
        $str_organization = substr($str,0,17);
        $num =0;
        for ($i=0; $i <17; $i++) {
            $num +=self::transformation($str_organization[$i])*$wi[$i];
        }
        switch ($num % 31) {
            case '0':
                $result = 0;
                break;
            default:
                $result = 31-$num%31;
                break;
        }
        if(substr($str,-1,1) == self::transformation($result, true)) {
            return true;
        } else {
            return false;
        }
    }

    static function transformation($num,$status=false){
        $list =array(0,1,2,3,4,5,6,7,8,9,'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15,'G'=>16,'H'=>17,'J'=>18,'K'=>19,'L'=>20,'M'=>21,'N'=>22,'P'=>23,'Q'=>24,'R'=>25,'T'=>26,'U'=>27,'W'=>28,'X'=>29,'Y'=>30);//值转换
        if($status == true){
            $list = array_flip($list);
        }
        return $list[$num];
    }


}