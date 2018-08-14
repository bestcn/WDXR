<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

class PasswordValidation extends Validation
{
    public function initialize()
    {
//        $this->add('old_password', new PresenceOf([
//            'message' => '请填写旧密码',
//            "cancelOnFail" => true,
//        ]));

        // Password
        $this->add('password', new PresenceOf([
            'message' => '请填写密码',
            "cancelOnFail" => true,
        ]));
        $this->add('password', new Confirmation([
            'message' => '密码与确认密码不匹配',
            'with' => 'confirm_password'
        ]));
        $this->add('password', new Validation\Validator\PasswordStrength([
            'minScore' => 2,
            'message' => '密码强度太弱，密码必须包含数字与字母',
            'allowEmpty' => false
        ]));

        $this->add('confirm_password', new PresenceOf([
            'message' => '请填写确认密码',
            "cancelOnFail" => true,
        ]));
    }

}