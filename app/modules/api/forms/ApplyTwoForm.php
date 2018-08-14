<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\checkIdCard;
use Phalcon\Validation\Validator\LicenseNum;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\Company as RepoCompany;

class ApplyTwoForm extends \Phalcon\Forms\Form
{

    public function initialize($entity, $options)
    {
//        $licence_num = new Text('licence_num');
//        $licence_num->addValidators([
//            new PresenceOf([
//                'message' => '请输入统一社会信用代码或注册号',
//                "cancelOnFail" => true,
//            ]),
////            new LicenseNum(),
//        ]);
//        $this->add($licence_num);
//
//        $legal_name = new Text('legal_name');
//        $legal_name->addValidator(new PresenceOf([
//            'message' => '请填写法定代表人',
//        ]));
//        $this->add($legal_name);
//
//        $scope = new Text('scope');
//        $scope->addValidator(new PresenceOf([
//            'message' => '请填写经营范围',
//        ]));
//        $this->add($scope);
//
//        $period = new Text('period');
//        $period->addValidator(new PresenceOf([
//            'message' => '请填写营业期限',
//        ]));
//        $this->add($period);
//
//        $licence = new Text('licence');
//        $licence->addValidator(new PresenceOf([
//            'message' => '请上传营业执照',
//        ]));
//        $this->add($licence);
//
//        if($options['company_type'] == RepoCompany::TYPE_COMPANY) {
//            $credit_code = new Text('credit_code');
////            $credit_code->addValidator(new PresenceOf([
////                'message' => '请上传机构信用代码证',
////            ]));
//            $this->add($credit_code);
//
//            $account_permit = new Text('account_permit');
////            $account_permit->addValidator(new PresenceOf([
////                'message' => '请上传开户许可证',
////            ]));
//            $this->add($account_permit);
//        }

        $idcard_up = new Text('idcard_up');
        $idcard_up->addValidator(new PresenceOf([
            'message' => '请上传身份证正面',
        ]));
        $this->add($idcard_up);

        $idcard_down = new Text('idcard_down');
        $idcard_down->addValidator(new PresenceOf([
            'message' => '请上传身份证背面',
        ]));
        $this->add($idcard_down);

        $idcard = new Text('idcard');
        $idcard->addValidators([
            new PresenceOf([
            'message' => '请填写身份证号',
            "cancelOnFail" => true,
            ]),
            new checkIdCard()
        ]);
        $this->add($idcard);

        $photo = new Text('photo');
        $photo->addValidator(new PresenceOf([
            'message' => '请上传法人手持身份证照片',
        ]));
        $this->add($photo);

//        $intro = new Text('intro');
//        $intro->addValidators([
//            new PresenceOf([
//                'message' => '请填写公司简介',
//                "cancelOnFail" => true,
//            ]),
//            new StringLength([
//                'messageMaximum' => '公司简介不能多余150个字符',
//                'messageMinimum' => '公司简介不能少于20个字符',
//                'max' => 150,
//                'min' => 20,
//            ])
//        ]);
//        $this->add($intro);




        //第一步转移数据
        $contacts = new Text('contacts');
        $contacts->addValidator(new PresenceOf([
            'message' => '请填写联系人',
            "cancelOnFail" => true,
        ]));
        $this->add($contacts);

        //邮政编码]
        $zipcode = new Text('zipcode');
        $zipcode->addValidator(new PresenceOf([
            'message' => '请填写邮政编码',
            "cancelOnFail" => true,
        ]));
        $this->add($zipcode);


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

        if($options['is_verified'] === false) {
            $verify_code = new Text('verify_code');
            $verify_code->addValidators([
                new PresenceOf([
                    'message' => '请填写手机验证码',
                    "cancelOnFail" => true,
                ]),
            ]);
            $this->add($verify_code);
        }

        //行业
        $top_category = new Text('top_category');
        $top_category->addValidators([
            new PresenceOf([
                'message' => '请选择行业',
            ]),
        ]);
        $this->add($top_category);

        $sub_category = new Text('sub_category');
        $sub_category->addValidators([
            new PresenceOf([
                'message' => '请选择附属行业',
            ]),
        ]);
        $this->add($sub_category);

    }

}
