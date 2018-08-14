<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class AdminPasswordForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        // CSRF
        $csrf = new Hidden('csrf');
//        $csrf->addValidator(
//            new Identical([
//                'value' => $this->security->getSessionToken(),
//                'message' => '非法访问',
//            ])
//        );
        $csrf->clear();
        $this->add($csrf);

        //Id
        $id = new Hidden('id');
        $this->add($id);

        $old_password = new Password('old_password', [
            'class' => 'form-control',
            'placeholder' => '请输入旧密码'
        ]);
        $this->add($old_password);

        // Password
        $password = new Password('password', [
            'class' => 'form-control',
            'placeholder' => '请输入新密码'
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

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}