<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\checkIdCard;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\LicenseNum;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\Company as RepoCompany;

class ReApplyForm extends \Phalcon\Forms\Form
{

    public function initialize($entity, $options)
    {

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

        $zipcode = new Text('zipcode');
        $zipcode->addValidator(new PresenceOf([
            'message' => '请填写邮政编码',
        ]));
        $this->add($zipcode);

        $sub_category = new Text('sub_category');
        $sub_category->addValidator(new PresenceOf([
            'message' => '请选择附属行业',
        ]));
        $this->add($sub_category);


    }

}
