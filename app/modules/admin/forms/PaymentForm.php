<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Entities\Levels as EntityLevel;
use Phalcon\Validation\Validator\InclusionIn;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Entities\Regions;
use Wdxr\Models\Repositories\BankList;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Phalcon\Validation\Validator\BankCard;

class PaymentForm extends \Phalcon\Forms\Form
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

        $admins = Admins::find(["columns" => "id, name", "conditions" => 'on_job = :on_job: and status = :status: and is_lock = :is_lock:', 'bind' => ['on_job' =>1, 'status' => 1, 'is_lock' => 0]]);
        $device_id = new Select('admin_id', $admins, [
            'class' => 'form-control',
            'placeholder' => '请选择业务员',
            'using' => ['id', 'name']
        ]);
        $device_id->addValidators([
            new PresenceOf([
                'message' => '请选择业务员',
            ])
        ]);
        $this->add($device_id);

        $types = [
            CompanyPayment::TYPE_TRANSFER => CompanyPayment::getTypeName(CompanyPayment::TYPE_TRANSFER),
            CompanyPayment::TYPE_CASH => CompanyPayment::getTypeName(CompanyPayment::TYPE_CASH),
            CompanyPayment::TYPE_POS => CompanyPayment::getTypeName(CompanyPayment::TYPE_POS),
        ];
        $type = new Select('payment_type', $types,[
            'class' => 'form-control m-b',
            'placeholder' => '请选择缴费类型',
        ]);
        $type->addValidators([
            new PresenceOf([
                'message' => '请选择缴费类型',
            ])
        ]);
        $this->add($type);

        //企业级别
        $level = new Level();
        $select_options = $level->get_level();
        $level_data = new Select('level_id', $select_options, [
            'class' => 'form-control',
            'id' => 'level'
        ]);
        $this->add($level_data);

        $bankcard_photo = new File('bankcard_photo');
        $this->add($bankcard_photo);

        $work_photo = new File('work_photo');
        $this->add($work_photo);

        $bankcard = new Text('bankcard', [
            'class' => 'form-control',
            'placeholder' => '请输入银行卡号',
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

        $bank_types = [
            RepoCompany::BANK_TYPE_PUB => '对公账户',
            RepoCompany::BANK_TYPE_PRI => '个人帐户'
        ];
        $bank_type = new Select('bank_type', $bank_types, [
            'class' => 'form-control m-b',
        ]);
        $bank_type->addValidators([
            new PresenceOf([
                'message' => '请选择银行账户类型',
                "cancelOnFail" => true,
            ]),
            new InclusionIn([
                'message' => '银行账户类型选择错误',
                'domain' => [RepoCompany::BANK_TYPE_PRI, RepoCompany::BANK_TYPE_PUB]
            ])
        ]);
        $this->add($bank_type);

        $banks = BankList::getList();
        $bank = new Select('bank', $banks, [
            'class' => 'form-control m-b',
            'using' => ['bank_name', 'bank_name'],
        ]);
        $bank->addValidator(new PresenceOf([
            'message' => '请选择开户银行',
            "cancelOnFail" => true,
        ]));
        $this->add($bank);
        $provinces = Regions::find(["depth = 1"]);

        $bank_province = new Select('bank_province', $provinces,  [
            'class' => 'form-control m-b',
            'using' => ['id', 'name'],
        ]);
        $bank_province->addValidator(new PresenceOf([
            'message' => '请选择开户行所在省份',
            "cancelOnFail" => true,
        ]));
        $this->add($bank_province);

        $cities = Regions::find(["depth = 2 and pid = :pid:", 'bind' => ['pid' => $provinces[0]->getId()]]);

        $bank_city = new Select('bank_city', $cities, [
            'class' => 'form-control m-b',
            'using' => ['id', 'name']
        ]);
        $bank_city->addValidator(new PresenceOf([
            'message' => '请选择开户行所在城市',
            "cancelOnFail" => true,
        ]));
        $this->add($bank_city);

        $bank_name = new Text('bank_name', [
            'class' => 'form-control',
            'placeholder' => '请输入开户行',
        ]);
        $bank_name->addValidator(new PresenceOf([
            'message' => '请填写详细的开户行',
            "cancelOnFail" => true,
        ]));
        $this->add($bank_name);

        /**
         * 绩效银行卡
         */
        $work_bankcard = new Text('work_bankcard', [
            'class' => 'form-control',
            'placeholder' => '请输入绩效银行卡号',
        ]);
        $work_bank_province = new Select('work_bank_province', $provinces,  [
            'class' => 'form-control m-b',
            'using' => ['id', 'name'],
            'useEmpty' => true,
            'emptyText' => '',
            'emptyValue' => 0
        ]);
        $work_bank_city = new Select('work_bank_city', $cities, [
            'class' => 'form-control m-b',
            'using' => ['id', 'name'],
            'useEmpty' => true,
            'emptyText' => '',
            'emptyValue' => 0
        ]);
        $work_bank_name = new Text('work_bank_name', [
            'class' => 'form-control',
            'placeholder' => '请输入绩效开户行',
        ]);
        $work_bank = new Select('work_bank', $banks, [
            'class' => 'form-control m-b',
            'using' => ['bank_name', 'bank_name'],
            'useEmpty' => true,
            'emptyText' => '',
            'emptyValue' => 0
        ]);

        if($this->request->getPost('bank_type') == RepoCompany::BANK_TYPE_PUB) {
            $work_bankcard->addValidators([
                new PresenceOf([
                    'message' => '请填写绩效银行卡号',
                    "cancelOnFail" => true,
                ]),
                new BankCard([
                    'message' => '绩效银行卡号格式错误',
                ])
            ]);
            $work_bank_province->addValidator(new PresenceOf([
                'message' => '请选择绩效开户行所在省份',
            ]));
            $work_bank_city->addValidator(new PresenceOf([
                'message' => '请选择绩效开户行所在城市',
            ]));
            $work_bank_name->addValidator(new PresenceOf([
                'message' => '请填写详细的绩效开户行',
            ]));
            $work_bank->addValidator(new PresenceOf([
                'message' => '请选择绩效开户银行',
                "cancelOnFail" => true,
            ]));
        }
        $this->add($work_bankcard);
        $this->add($work_bank_province);
        $this->add($work_bank_city);
        $this->add($work_bank_name);
        $this->add($work_bank);


        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));

    }

}