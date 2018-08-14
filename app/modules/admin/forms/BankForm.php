<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\BankCard;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\LicenseNum;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Entities\Achievement;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Entities\Regions;
use Wdxr\Models\Repositories\BankList;
use Wdxr\Models\Repositories\Company as RepoCompany;

class ApplyForm extends \Phalcon\Forms\Form
{
    public function initialize($entity, $options)
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


        $bankcard_photo = new File('bankcard_photo');
        $this->add($bankcard_photo);

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

        $bank_province = new Select('province', $provinces,  [
            'class' => 'form-control m-b',
            'using' => ['id', 'name'],
        ]);
        $bank_province->addValidator(new PresenceOf([
            'message' => '请选择开户行所在省份',
            "cancelOnFail" => true,
        ]));
        $this->add($bank_province);

        $cities = Regions::find(["depth = 2 and pid = :pid:", 'bind' => ['pid' => $provinces[0]->getId()]]);

        $bank_city = new Select('city', $cities, [
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




    }

}
