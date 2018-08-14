<?php
namespace XingeApp;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 11:18
 */
class TagTokenPair
{

    public function __construct($tag, $token)
    {
        $this->tag = strval($tag);
        $this->token = strval($token);
    }

    public function __destruct()
    {
    }

    public $tag;
    public $token;
}
?>