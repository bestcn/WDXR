<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(
            new Identical([
                'value' => $this->security->getSessionToken(),
                'message' => '非法访问',
            ])
        );
        $csrf->clear();
        $this->add($csrf);

        //username
        $username = new Text("username", [
            'class' => 'form-control',
            'placeholder' => '用户名'
        ]);
        $username->addValidator(new PresenceOf([
            'message' => '请填写用户名',
            "cancelOnFail" => true,
        ]));
        $this->add($username);

        // Password
        $password = new Password('password', [
            'class' => 'form-control',
            'placeholder' => '密码'
        ]);
        $password->addValidator(new PresenceOf([
            'message' => '请填写密码'
        ]));
        $password->clear();
        $this->add($password);

        //captcha
        $captcha = new Text('captcha', [
            'class' => 'form-control',
            'placeholder' => '验证码'
        ]);
        $captcha->addValidators([
            new PresenceOf([
                'message' => '请填写验证码',
                "cancelOnFail" => true,
            ]),
            new Identical([
                'value' => $this->captcha->getCode(),
                'message' => '验证码错误'
            ])
        ]);
        $captcha->clear();
        $this->add($captcha);

        // Remember
        $remember = new Check('remember', [
            'value' => 'yes'
        ]);
        $this->add($remember);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary btn-block btn-flat',
            'value' => '登录'
        ]));
    }

}