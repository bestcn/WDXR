<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\BankCard;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
class AccountForm extends Form
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

        //银行
        $bank = new Text("bank", [
            'class' => 'form-control',
            'placeholder' => '请填写银行'
        ]);
        $bank->addValidators([
            new PresenceOf([
                'message' => '请填写银行',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($bank);

        //银行卡号
        $bankcard = new Text("bank_card", [
            'class' => 'form-control',
            'placeholder' => '请填写银行卡号'
        ]);
        $bankcard->addValidators([
            new PresenceOf([
                'message' => '请填写银行卡号',
                "cancelOnFail" => true,
            ]),
            new BankCard([
                'message' => '银行卡号格式错误',
            ])
        ]);
        $this->add($bankcard);


        //账户类型
        $select_options = ['1' => '企业账户', '2' => '个人账户'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        //active
        $bank_type = new Select('bank_type', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($bank_type);

        //状态
        $select_options = ['0' => '禁用', '1' => '启用'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        //active
        $status = new Select('status', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($status);

        //备注
        $remark = new Text("remark", [
            'class' => 'form-control',
            'placeholder' => '请填写备注'
        ]);
        $this->add($remark);


        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}