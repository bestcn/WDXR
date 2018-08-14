<?php
namespace Wdxr\Modules\Api\Forms;

use Lcobucci\JWT\JWT;
use Phalcon\Validation\Validator\telCode;
use Wdxr\Models\Services\SMS;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\checkIdCard;
use Phalcon\Validation\Validator\checkPayment;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Modules\Api\Controllers\LoanController;

class LoanOneForm extends \Phalcon\Forms\Form
{
    const VERIFIED = "*";  //状态为电话号码已验证

    public function initialize($entity, $options)
    {

        $companyId = new Text('company_id');
        $companyId->addValidators([
            new PresenceOf([
                'message' => '找不到您的企业信息！',
                "cancelOnFail" => true,
            ]),
            new checkPayment(),
        ]);
        $this->add($companyId);

        $contact_phone = new Text('tel');
        $contact_phone->addValidators([
            new PresenceOf([
                'message' => '请填写联系人手机号',
                "cancelOnFail" => true,
            ]),
            new Regex([
                "message" => "联系人手机号格式不正确",
                "pattern" => "/^1[3456789]\d{9}$/",
            ]),
        ]);
        $this->add($contact_phone);

        $name = new Text('name');
        $name->addValidator(new PresenceOf([
            'message' => '请填写姓名',
            "cancelOnFail" => true,
        ]));
        $this->add($name);


        $sex = new Text('sex');
        $sex->addValidator(new PresenceOf([
            'message' => '请选择性别',
            "cancelOnFail" => true,
        ]));
        $this->add($sex);


        $identity = new Text('identity');
        $identity->addValidators([
            new PresenceOf([
                'message' => '请输入身份证号',
                "cancelOnFail" => true,
            ]),
            new checkIdCard(),
        ]);
        $this->add($identity);


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

        $area = new Text('area');
        $area->addValidator(new PresenceOf([
            'message' => '请选择区县',
            "cancelOnFail" => true,
        ]));
        $this->add($area);

        $address = new Text('address');
        $address->addValidator(new PresenceOf([
            'message' => '请填写详细地址',
            "cancelOnFail" => true,
        ]));
        $this->add($address);


        $business = new Text('business');
        $business->addValidators([
            new PresenceOf([
                'message' => '请填写职业/经营项目',
                "cancelOnFail" => true,
            ]),
            new StringLength([
                'messageMaximum' => '经营项目不能多于100个字符',
                'max' => 100,
            ])
        ]);
        $this->add($business);

    }

}
