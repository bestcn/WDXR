<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\BankCard;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\Company as RepoCompany;

class ApplyThreeForm extends \Phalcon\Forms\Form
{
    public function initialize($entity, $options)
    {
        if($options['is_payment'] == 0) {

            $bankcard_photo = new Text('bankcard_photo');
//        $bankcard_photo->addValidator(new PresenceOf([
//            'message' => '请上传银行卡照片',
//            "cancelOnFail" => true,
//        ]));
            $this->add($bankcard_photo);

            $bankcard = new Text('bankcard');
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

            //开户人
            $account_holder = new Text('account_holder');
            $account_holder->addValidator(new PresenceOf([
                'message' => '请填写开户人',
                "cancelOnFail" => true,
            ]));
            $this->add($account_holder);

            $bank_province = new Text('bank_province');
            $bank_province->addValidator(new PresenceOf([
                'message' => '请选择开户行所在省份',
                "cancelOnFail" => true,
            ]));
            $this->add($bank_province);

            $bank_city = new Text('bank_city');
            $bank_city->addValidator(new PresenceOf([
                'message' => '请选择开户行所在城市',
                "cancelOnFail" => true,
            ]));
            $this->add($bank_city);

            $bank_type = new Text('bank_type');
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

            $bank = new Text('bank');
            $bank->addValidator(new PresenceOf([
                'message' => '请选择开户银行',
                "cancelOnFail" => true,
            ]));
            $this->add($bank);

            $bank_name = new Text('bank_name');
            $bank_name->addValidator(new PresenceOf([
                'message' => '请填写详细的开户行',
                "cancelOnFail" => true,
            ]));
            $this->add($bank_name);
        }


        if($options['bank_type'] == RepoCompany::BANK_TYPE_PUB && $options['is_payment'] == 0) {
            $work_bankcard = new Text('work_bankcard');
            $work_bankcard->addValidators([
                new PresenceOf([
                    'message' => '请填写绩效银行卡号',
                    "cancelOnFail" => true,
                ]),
                new BankCard([
                    'message' => '绩效银行卡号格式错误',
                ])
            ]);
            $this->add($work_bankcard);

            //绩效卡开户人
            $work_account_holder = new Text('work_account_holder');
            $work_account_holder->addValidator(new PresenceOf([
                'message' => '请填写绩效银行卡开户人',
                "cancelOnFail" => true,
            ]));
            $this->add($work_account_holder);


            $work_bank_province = new Text('work_bank_province');
            $work_bank_province->addValidator(new PresenceOf([
                'message' => '请选择开户行所在省份',
                "cancelOnFail" => true,
            ]));
            $this->add($work_bank_province);

            $work_bank_city = new Text('work_bank_city');
            $work_bank_city->addValidator(new PresenceOf([
                'message' => '请选择开户行所在城市',
                "cancelOnFail" => true,
            ]));
            $this->add($work_bank_city);

//            $work_bank_type = new Text('work_bank_type');
//            $work_bank_type->addValidators([
//                new PresenceOf([
//                    'message' => '请选择绩效银行账户类型',
//                    "cancelOnFail" => true,
//                ]),
//                new InclusionIn([
//                    'message' => '绩效银行账户类型选择错误',
//                    'domain' => [RepoCompany::BANK_TYPE_PRI, RepoCompany::BANK_TYPE_PUB]
//                ])
//            ]);
//            $this->add($work_bank_type);

            $work_bank = new Text('work_bank');
            $work_bank->addValidator(new PresenceOf([
                'message' => '请选择开户银行',
                "cancelOnFail" => true,
            ]));
            $this->add($work_bank);

            $work_bank_name = new Text('work_bank_name');
            $work_bank_name->addValidator(new PresenceOf([
                'message' => '请填写详细的开户行',
                "cancelOnFail" => true,
            ]));
            $this->add($work_bank_name);
        }
    }

}
