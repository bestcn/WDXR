<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\Level;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Modules\Admin\Controllers\SelectController;

class PresentationForm extends Form
{
    public function initialize($entity = null, $options = null)
    {

        //请填写联社系统借款信息
        $loan_name = new Text("system_loan", [
            'class' => 'form-control',
            'placeholder' => '请填写联社系统借款信息'
        ]);

        //请填写对外担保金额信息
        $identity_account = new Text("sponsion", [
            'class' => 'form-control',
            'placeholder' => '请填写对外担保金额信息'
        ]);

        //请填写其他金融机构借款信息
        $license_account = new Text("other_loan", [
            'class' => 'form-control',
            'placeholder' => '请填写其他金融机构借款信息'
        ]);

        //请填写不良借贷或不良担保金额信息
        $household_account = new Text("unhealthy", [
            'class' => 'form-control',
            'placeholder' => '请填写不良借贷或不良担保金额信息'
        ]);

        //请填写上年收入
        $address_program = new Text("last_year", [
            'class' => 'form-control',
            'placeholder' => '请填写上年收入'
        ]);

        //经营范围
        $business = new Text("this_year", [
            'class' => 'form-control',
            'placeholder' => '请填写今年收入'
        ]);

        $contact_phone = new Text('quota', [
            'class' => 'form-control',
            'placeholder' => '请填写担保金额'
        ]);

        $remarks = new Text("remarks", [
            'class' => 'form-control',
            'placeholder' => '请填写备注'
        ]);

        $remark = new Text("remark", [
            'class' => 'form-control',
            'placeholder' => '请填写驳回原因'
        ]);

        if ($options['status'] == CompanyVerify::STATUS_OK) {
            $loan_name->addValidators([
                new PresenceOf([
                    'message' => '请填写联社系统借款信息',
                    "cancelOnFail" => true,
                ])
            ]);
            $identity_account->addValidators([
                new PresenceOf([
                    'message' => '请填写对外担保金额信息',
                    "cancelOnFail" => true,
                ])
            ]);
            $license_account->addValidators([
                new PresenceOf([
                    'message' => '请填写其他金融机构借款信息',
                    "cancelOnFail" => true,
                ])
            ]);
            $household_account->addValidators([
                new PresenceOf([
                    'message' => '请填写不良借贷或不良担保金额信息',
                    "cancelOnFail" => true,
                ])
            ]);
            $address_program->addValidators([
                new PresenceOf([
                    'message' => '请填写上年收入',
                    "cancelOnFail" => true,
                ])
            ]);
            $business->addValidators([
                new PresenceOf([
                    'message' => '请填写今年收入',
                    "cancelOnFail" => true,
                ])
            ]);
            $contact_phone->addValidators([
                new PresenceOf([
                    'message' => '请填写担保金额',
                    "cancelOnFail" => true,
                ])
            ]);
        } elseif ($options['status'] == CompanyVerify::STATUS_FAIL) {
            $remark->addValidators([
                new PresenceOf([
                    'message' => '如果需要驳回,请填写驳回原因',
                ])
            ]);
        }

        $this->add($loan_name);
        $this->add($identity_account);
        $this->add($household_account);
        $this->add($address_program);
        $this->add($business);
        $this->add($license_account);
        $this->add($contact_phone);
        $this->add($remarks);
        $this->add($remark);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '提交'
        ]));
    }
}