<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class AdminPasswordForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        // Password
        $password = new Password('password', [
            'class' => 'form-control',
            'placeholder' => '密码'
        ]);
        $password->addValidators([
            new PresenceOf([
                'message' => '请填写密码',
                "cancelOnFail" => true,
            ]),
            new Confirmation([
                'message' => '密码与确认密码不匹配',
                'with' => 'confirm_password'
            ]),
            new StringLength([
                "min" => 6,
                'max' => 255,
                'messageMaximum' => '密码最大不能超过255位',
                'messageMinimum' => '密码最小不能少于6位'
            ])
        ]);
        $password->clear();
        $this->add($password);

        //confirm_password
        $confirm_password = new Password('confirm_password', [
            'class' => 'form-control',
            'placeholder' => '确认密码'
        ]);
        $confirm_password->clear();
        $this->add($confirm_password);
    }

}