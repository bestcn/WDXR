<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\Level;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;

class CompanysForm extends Form
{
    public function initialize($entity = null, $options = null)
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
        } else if(isset($options['search']) && $options['search']) {
            $id = new Text("id", [
                'class' => 'form-control',
                'placeholder' => '请填写ID'
            ]);
            $this->add($id);
        }

        //企业名称
        $company_name = new Text("name", [
            'class' => 'form-control',
            'placeholder' => '请填写企业名称'
        ]);
        $company_name->addValidators([
            new PresenceOf([
                'message' => '请填写企业名称',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($company_name);


        //企业级别
        $level = new Level();
        $select_options = $level->get_level();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $level_data = new Select('level_id', $select_options, [
            'class' => 'form-control',
            'id' => 'level'
        ]);
        $this->add($level_data);

        //企业性质
        $select_options = ['2' => '个体工商户', '1' => '非个体工商户'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $company_type = new Select('type', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($company_type);



        //状态
        $select_options = ['0' => '否', '1' => '是'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $company_status = new Select('status', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($company_status);

        $auditing = new Select('auditing', [
            Company::AUDIT_NOT => '未申请',
            Company::AUDIT_APPLY => '已申请',
            Company::AUDIT_OK => '申请通过',
            Company::AUDIT_REVOKED => '申请被驳回'
        ], [
            'class' => 'form-control',
        ]);
        $this->add($auditing);

        $payment = new Select('payment', [
            Company::PAYMENT_NOT => '未支付',
            Company::PAYMENT_OK => '支付成功',
            Company::PAYMENT_APPLY => '已申请',
            Company::PAYMENT_CANCEL => '申请撤销',
            Company::PAYMENT_FAIL => '申请被驳回',
        ], [
            'class' => 'form-control',
        ]);
        $this->add($payment);

        if(!$options['edit']) {
            //企业账号
            $company_account = new Text("user_name", [
                'class' => 'form-control',
                'placeholder' => '请填写企业账号'
            ]);
            $company_account->addValidators([
                new PresenceOf([
                    'message' => '请填写企业账号',
                    "cancelOnFail" => true,
                ])
            ]);
            $this->add($company_account);


            //企业密码
            if (!isset($options['edit'])) {
                // Password
                $company_password = new Password('user_password', [
                    'class' => 'form-control',
                    'placeholder' => '密码'
                ]);
                $company_password->addValidators([
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
                $company_password->addValidator(
                    new PresenceOf([
                        'message' => '请填写密码',
                        "cancelOnFail" => true,
                    ])
                );
                $this->add($company_password);

                //confirm_password
                $confirm_password = new Password('confirm_password', [
                    'class' => 'form-control',
                    'placeholder' => '确认密码'
                ]);
                $this->add($confirm_password);
            }
        }
        /*
        //企业简介textarea
        $company_program = new Text("company_program", [
            'class' => 'form-control',
            'placeholder' => '请填写企业简介'
        ]);
        $this->add($company_program);
        */


        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}