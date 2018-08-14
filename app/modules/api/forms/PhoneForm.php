<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class PhoneForm extends \Phalcon\Forms\Form
{

    public function initialize($entity, $options)
    {
        $phone = new Hidden('phone');
        $phone->addValidators([
            new PresenceOf([
                'message' => '请填写手机号',
                "cancelOnFail" => true,
            ]),
            new Regex([
                "message" => "手机号格式不正确",
                "pattern" => "/^1[3456789]\d{9}$/",
            ])
        ]);
        $this->add($phone);
    }

}
