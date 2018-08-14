<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Validation\Validator\checkIdCard;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class ApplyTwoValidation extends \Phalcon\Validation
{

    public function initialize()
    {
        $this->add('idcard_up', new PresenceOf([
            'message' => '请上传身份证正面',
        ]));

        $this->add('idcard_down', new PresenceOf([
            'message' => '请上传身份证背面',
        ]));

        $this->add('idcard', new PresenceOf([
            'message' => '请填写身份证号',
            "cancelOnFail" => true,
        ]));
        $this->add('idcard', new checkIdCard());

        $this->add('photo', new PresenceOf([
            'message' => '请上传法人手持身份证照片',
        ]));

        //第一步转移数据
        $this->add('contacts', new PresenceOf([
            'message' => '请填写联系人',
        ]));

        //邮政编码
        $this->add('zipcode', new PresenceOf([
            'message' => '请填写邮政编码',
        ]));

        $this->add('contact_title', new PresenceOf([
            'message' => '请填写联系人职位',
        ]));

        $this->add('contact_phone', new PresenceOf([
            'message' => '请填写联系人手机号',
        ]));
        $this->add('contact_phone', new Regex([
            "message" => "联系人手机号格式不正确",
            "pattern" => "/^1[3456789]\d{9}$/",
        ]));

        //todo 验证码
        if($options['is_verified'] === false) {
            $this->add('verify_code', new PresenceOf([
                'message' => '请填写手机验证码',
                "cancelOnFail" => true,
            ]));
        }

        //行业
        $this->add('top_category', new PresenceOf([
            'message' => '请选择行业',
        ]));
        $this->add('sub_category', new PresenceOf([
            'message' => '请选择附属行业',
        ]));

    }

}
