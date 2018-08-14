<?php
namespace Wdxr;

class Captcha {

    //随机因子
    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
    //验证码
    private $code;
    //验证码长度
    public $code_len = 4;
    //宽度
    public $width = 100;
    //高度
    public $height = 30;
    //图形资源句柄
    private $img;
    //指定的字体
    private $font;
    //指定字体大小
    public $font_size = 15;
    //指定字体颜色
    private $font_color;
    //设置背景色
    private $background = '#EDF7FF';
    //验证码类型
    public $type = '';
    //输出多少次后更换验证码
    private $testLimit = 3;

    /**
     * @var $session_service \Phalcon\Session\Adapter\Files
     */
    private $session_service;

    //构造方法初始化
    public function __construct() {
        $this->font =  'fonts/elephant.ttf';
        $captcha_type = 2;
        switch ($captcha_type) {
            //纯数字
            case 1:
                $this->charset = '0123456789';
                break;
            //纯字母
            case 2:
                $this->charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ';
                break;
        }
        $this->session_service = \Phalcon\Di::getDefault()->getSession();
    }

    //魔术方法，设置
    public function __set($name, $value) {
        if (empty($name) || in_array($name, array('code', 'img'))) {
            return false;
        }
        $this->$name = $value;
    }

    //生成随机码
    private function createCode() {
        $code = '';
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->code_len; $i++) {
            $code .= $this->charset[mt_rand(0, $_len)];
        }
        return $code;
    }

    //生成背景
    private function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        if (empty($this->background)) {
            $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        } else {
            //设置背景色
            $color = imagecolorallocate($this->img, hexdec(substr($this->background, 1, 2)), hexdec(substr($this->background, 3, 2)), hexdec(substr($this->background, 5, 2)));
        }
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    //生成文字
    private function createFont() {
        $_x = $this->width / $this->code_len;
        $isFontColor = false;
        if ($this->font_color && !$isFontColor) {
            $this->font_color = imagecolorallocate($this->img, hexdec(substr($this->font_color, 1, 2)), hexdec(substr($this->font_color, 3, 2)), hexdec(substr($this->font_color, 5, 2)));
            $isFontColor = true;
        }
        for ($i = 0; $i < $this->code_len; $i++) {
            if (!$isFontColor) {
                $this->font_color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            }
            imagettftext($this->img, $this->font_size, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->font_color, $this->font, $this->code[$i]);
        }
    }

    //生成线条、雪花
    private function createLine() {
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    //输出
    public function output($regenerate = false) {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-type:image/png');
        $this->createBg();
        $this->getVerifyCode($regenerate);
        $this->createLine();
        $this->createFont();
        imagepng($this->img);
        imagedestroy($this->img);
    }

    /**
     * $regenerate
     * @param bool $regenerate 刷新
     * @return string
     */
    protected function getVerifyCode($regenerate = false) {
        $name = $this->getSessionKey();
        $old = $this->session_service->get($name);
        //没有的话重新生成个
        if (empty($old) || $regenerate) {
            $this->code = $this->createCode();
            $this->session_service->set($name, $this->code);
            $this->session_service->set($name . 'count', 1);
        } else {
            $this->code = $old;
        }
        return $this->code;
    }

    //获取验证码
    public function getCode() {
        return strtolower($this->getVerifyCode());
    }

    /**
     * 验证输入，看它是否生成的代码相匹配。
     * @param string      $input         用户输入的验证码
     * @param bool $caseSensitive 是否验证大小写
     * @return bool
     */
    public function validate($input, $caseSensitive = false) {
        $code = $this->getVerifyCode();
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        $name = $this->getSessionKey() . 'count';
        $old = ($name);
        $session = (int) $old + 1;
        $this->session_service->set($name, $session);
        if ($session > $this->testLimit || $valid) {
            $this->getVerifyCode(true);
        }
        return $valid;
    }

    //返回用于存储验证码的会话变量名。
    protected function getSessionKey() {
        return md5($this->type);
    }

}
