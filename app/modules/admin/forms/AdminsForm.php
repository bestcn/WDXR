<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Entities\Branchs as EntityBranchs;
use Wdxr\Models\Entities\Positions as EntityPosition;

class AdminsForm extends Form
{
    public function initialize(Admins $entity = null, $options = null)
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

        //Id
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
            $this->add($id);
        } elseif (isset($options['search']) && $options['search']) {
            $id = new Text("id", [
                'class' => 'form-control',
                'placeholder' => '请填写ID'
            ]);
            $this->add($id);
        }

        //username
        $username = new Text("name", [
            'class' => 'form-control',
            'placeholder' => '请填写用户名'
        ]);
        $username->addValidators([
            new PresenceOf([
                'message' => '请填写用户名',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($username);

        if(!isset($options['edit'])) {
            // Password
            $password = new Password('password', [
                'class' => 'form-control',
                'placeholder' => '密码'
            ]);
            $password->addValidators([
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
            $password->addValidator(
                new PresenceOf([
                    'message' => '请填写密码',
                    "cancelOnFail" => true,
                ])
            );
            $this->add($password);

            //confirm_password
            $confirm_password = new Password('confirm_password', [
                'class' => 'form-control',
                'placeholder' => '确认密码'
            ]);
            $this->add($confirm_password);
        }

        //email
        $email = new Email('email', [
            'class' => 'form-control',
            'placeholder' => '请填写邮箱'
        ]);
        $email->addValidators([
            new PresenceOf([
                'message' => '请填写邮箱',
                "cancelOnFail" => true,
            ]),
            new \Phalcon\Validation\Validator\Email([
                'message' => '邮箱格式错误'
            ])
        ]);
        $this->add($email);

        //phone
        $phone = new Text('phone', [
            'class' => 'form-control',
            'placeholder' => '请填写手机号'
        ]);
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

        //position_id
        $positions = EntityPosition::find(['order' => 'orderby asc']);
        $position = new Select('position_id',  $positions, [
            'class' => 'form-control',
            'placeholder' => '请选择职位',
            'using' => ['id', 'name']
        ]);
        $position->addValidators([
            new PresenceOf([
                'message' => '请选择角色',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($position);
//
//        //banned
//        $select_options = ['N' => '否', 'Y' => '是'];
        $attr = [
            'class' => 'form-control',
        ];
//        if(isset($options['search']) && $options['search'] === true)
//        {
//            $attr = array_merge($attr, [
//                'useEmpty' => true,
//                'emptyText' => '...',
//                'emptyValue' => ''
//            ]);
//        }
//        $banned = new Select('banned', $select_options, $attr);
//        $this->add($banned);

        //is_probation
        $probation_options = ['0' => '否', '1' => '是'];
        $is_probation = new Select('is_probation', $probation_options, $attr);
        $this->add($is_probation);

//        is_lock
        $is_lock = new Select('is_lock', $probation_options, $attr);
        $this->add($is_lock);

//        on_job
        $on_job = new Select('on_job', $probation_options, $attr);
        $this->add($on_job);

        //entry_time
        $entry_time = new Text('entry_time', [
            'class' => 'form-control',
            'placeholder' => '请填写入职时间'
        ]);
//        $entry_time->addValidators([
//            new PresenceOf([
//                'message' => '请填写入职时间',
//                "cancelOnFail" => true,
//            ])
//        ]);
        $this->add($entry_time);

        //branchs
        $branchs = EntityBranchs::find('branch_status = 1');
        $branch = new Select('branch_id',  $branchs, [
            'class' => 'form-control',
            'placeholder' => '请选择所属分公司',
            'using' => ['id', 'branch_name']
        ]);
        $branch->addValidators([
            new PresenceOf([
                'message' => '请选择所属分公司',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($branch);

        $status = new Select('status', ['0'=>"不可用",'1'=>"可用"], $attr);
        $this->add($status);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}