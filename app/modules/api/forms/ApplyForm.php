<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Modules\Api\Controllers\ApplyController;

class ApplyForm extends \Phalcon\Forms\Form
{
    public function initialize($entity, $options)
    {
        $type = new Text('type');
        $type->addValidators([
            new PresenceOf([
                'message' => '请选择公司性质',
                "cancelOnFail" => true,
            ]),
            new InclusionIn([
                'message' => '企业性质选择错误',
                'domain' => [RepoCompany::TYPE_COMPANY, RepoCompany::TYPE_SELF_EMPLOYED]
            ])
        ]);
        $this->add($type);

        $province = new Text('province');
        $province->addValidator(new PresenceOf([
            'message' => '请选择省份',
            "cancelOnFail" => true,
        ]));
        $this->add($province);

        $city = new Text('city');
        $city->addValidator(new PresenceOf([
            'message' => '请选择城市',
            "cancelOnFail" => true,
        ]));
        $this->add($city);

//        $district = new Text('district');
//        $district->addValidator(new PresenceOf([
//            'message' => '请选择区县',
//            "cancelOnFail" => true,
//        ]));
//        $this->add($district);

        $address = new Text('address');
        $address->addValidator(new PresenceOf([
            'message' => '请填写详细地址',
            "cancelOnFail" => true,
        ]));
        $this->add($address);

        $contacts = new Text('contacts');
        $contacts->addValidator(new PresenceOf([
            'message' => '请填写联系人',
            "cancelOnFail" => true,
        ]));
        $this->add($contacts);

        $contact_title = new Text('contact_title');
        $contact_title->addValidator(new PresenceOf([
            'message' => '请填写联系人职位',
            "cancelOnFail" => true,
        ]));
        $this->add($contact_title);

        $contact_phone = new Text('contact_phone');
        $contact_phone->addValidators([
            new PresenceOf([
                'message' => '请填写联系人手机号',
                "cancelOnFail" => true,
            ]),
            new Regex([
                "message" => "联系人手机号格式不正确",
                "pattern" => "/^1[3456789]\d{9}$/",
            ])
        ]);
        $this->add($contact_phone);

        $licence = new Text('licence');
        $licence->addValidator(new PresenceOf([
            'message' => '请上传营业执照',
            "cancelOnFail" => true,
        ]));
        $this->add($licence);


        if($options['company_type'] == RepoCompany::TYPE_COMPANY) {
            $licence_num = new Text('licence_num');
            $licence_num->addValidators([
                new PresenceOf([
                    'message' => '请输入统一社会信用代码',
                    "cancelOnFail" => true,
                ]),
//                new Regex([
//                    "message" => "统一社会信用代码格式不正确",
//                    "pattern" => "/^[a-zA-Z0-9]{18}$/",
//                ]),
            ]);

//            $account_permit = new Text('account_permit');
//            $account_permit->addValidator(new PresenceOf([
//                'message' => '请上传开户许可证',
//                "cancelOnFail" => true,
//            ]));
//            $this->add($account_permit);
//
//            $credit_code = new Text('credit_code');
//            $credit_code->addValidator(new PresenceOf([
//                'message' => '请上传组织结构代码证',
//                "cancelOnFail" => true,
//            ]));
//            $this->add($credit_code);
        } else {
            $licence_num = new Text('licence_num');
            $licence_num->addValidators([
                new PresenceOf([
                    'message' => '请输入注册号',
                    "cancelOnFail" => true,
                ])
            ]);
        }

        $idcard = new Text('idcard');
        $idcard->addValidator(new PresenceOf([
            'message' => '请填写身份证号',
        ]));
        $this->add($idcard);

        $idcard_up = new Text('idcard_up');
        $idcard_up->addValidator(new PresenceOf([
            'message' => '请上传身份证正面',
            "cancelOnFail" => true,
        ]));
        $this->add($idcard_up);

        $idcard_down = new Text('idcard_down');
        $idcard_down->addValidator(new PresenceOf([
            'message' => '请上传身份证背面',
            "cancelOnFail" => true,
        ]));
        $this->add($idcard_down);

        $photo = new Text('photo');
        $photo->addValidator(new PresenceOf([
            'message' => '请上传法人手持身份证照片',
            "cancelOnFail" => true,
        ]));
        $this->add($photo);

        $scope = new Text('scope');
        $scope->addValidator(new PresenceOf([
            'message' => '请填写经营范围',
        ]));
        $this->add($scope);

        $period = new Text('period');
        $period->addValidator(new PresenceOf([
            'message' => '请填写营业期限',
        ]));
        $this->add($period);

        $legal_name = new Text('legal_name');
        $legal_name->addValidator(new PresenceOf([
            'message' => '请填写法人/经营者姓名',
        ]));
        $this->add($legal_name);

/*
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
                new Numericality([
                    'message' => '银行卡号格式错误',
                ])
            ]);
            $this->add($bankcard);

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

            */
        

        if($options['form_type'] == ApplyController::TYPE_NEW) {
            $contract_num = new Text('contract_num');
            $contract_num->addValidator(new PresenceOf([
                'message' => '请填写合同编号',
                "cancelOnFail" => true,
            ]));
            $this->add($contract_num);
        }

        $intro = new Text('intro');
        $intro->addValidators([
            new PresenceOf([
                'message' => '请填写公司简介',
                "cancelOnFail" => true,
            ]),
            new StringLength([
                'messageMaximum' => '公司简介不能多余150个字符',
                'messageMinimum' => '公司简介不能少于20个字符',
                'max' => 150,
                'min' => 20,
            ])
        ]);
        $this->add($intro);

    }

}
