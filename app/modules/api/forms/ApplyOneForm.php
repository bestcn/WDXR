<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\LicenseNum;

class ApplyOneForm extends \Phalcon\Forms\Form
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


        //第二步转移
        $licence_num = new Text('licence_num');
        $licence_num->addValidators([
            new PresenceOf([
                'message' => '请输入统一社会信用代码或注册号',
                "cancelOnFail" => true,
            ]),
//            new LicenseNum(),
        ]);
        $this->add($licence_num);

        $legal_name = new Text('legal_name');
        $legal_name->addValidator(new PresenceOf([
            'message' => '请填写法定代表人',
        ]));
        $this->add($legal_name);

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

        $licence = new Text('licence');
        $licence->addValidator(new PresenceOf([
            'message' => '请上传营业执照',
        ]));
        $this->add($licence);

        if($options['company_type'] == RepoCompany::TYPE_COMPANY) {
            $credit_code = new Text('credit_code');
//            $credit_code->addValidator(new PresenceOf([
//                'message' => '请上传机构信用代码证',
//            ]));
            $this->add($credit_code);

            $account_permit = new Text('account_permit');
//            $account_permit->addValidator(new PresenceOf([
//                'message' => '请上传开户许可证',
//            ]));
            $this->add($account_permit);
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



//        $contacts = new Text('contacts');
//        $contacts->addValidator(new PresenceOf([
//            'message' => '请填写联系人',
//            "cancelOnFail" => true,
//        ]));
//        $this->add($contacts);
//
//        $contact_title = new Text('contact_title');
//        $contact_title->addValidator(new PresenceOf([
//            'message' => '请填写联系人职位',
//            "cancelOnFail" => true,
//        ]));
//        $this->add($contact_title);
//
//        $contact_phone = new Text('contact_phone');
//        $contact_phone->addValidators([
//            new PresenceOf([
//                'message' => '请填写联系人手机号',
//                "cancelOnFail" => true,
//            ]),
//            new Regex([
//                "message" => "联系人手机号格式不正确",
//                "pattern" => "/^1[3456789]\d{9}$/",
//            ])
//        ]);
//        $this->add($contact_phone);
//
//        if($options['is_verified'] === false) {
//            $verify_code = new Text('verify_code');
//            $verify_code->addValidators([
//                new PresenceOf([
//                    'message' => '请填写手机验证码',
//                    "cancelOnFail" => true,
//                ]),
//            ]);
//            $this->add($verify_code);
//        }
//
//        $location = new Text('location');
//        $location->addValidators([
//            new PresenceOf([
//                'message' => '请填写当前地址',
//            ]),
//        ]);
//        $this->add($location);

    }

}
