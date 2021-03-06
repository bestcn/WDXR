<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\checkPayment;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\checkIdCard;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Modules\Api\Controllers\LoanController;

class LoanEditForm extends \Phalcon\Forms\Form
{
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

//        $area = new Text('area');
//        $area->addValidator(new PresenceOf([
//            'message' => '请选择区县',
//            "cancelOnFail" => true,
//        ]));
//        $this->add($area);

        $address = new Text('address');
        $address->addValidator(new PresenceOf([
            'message' => '请填写详细地址',
            "cancelOnFail" => true,
        ]));
        $this->add($address);

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


        $business = new Text('business');
        $business->addValidators([
            new PresenceOf([
                'message' => '请填写职业/经营项目',
                "cancelOnFail" => true,
            ]),
            new StringLength([
                'messageMaximum' => '经营项目不能多余100个字符',
                'max' => 100,
            ])
        ]);
        $this->add($business);


        $term = new Text('term');
        $term->addValidator(new PresenceOf([
            'message' => '请选择您的申请期限',
            "cancelOnFail" => true,
        ]));
        $this->add($term);

//        $purpose = new Text('purpose');
//        $purpose->addValidator(new PresenceOf([
//            'message' => '请输入您的借款用途',
//            "cancelOnFail" => true,
//        ]));
//        $this->add($purpose);

        $money = new Text('money');
        $money->addValidators([
            new PresenceOf([
                'message' => '请填写申请金额',
                "cancelOnFail" => true,
            ]),
            new Between([
                "minimum" => 10000,
                "maximum" => 80000,
                "message" => "您的申请金额必须在一万元至八万元人民币之间",
            ]),
            new Regex([
                "message" => "您的申请金额必须是一个合法的正整数数字",
                "pattern" => "/^\+?[1-9][0-9]*$/",
            ])
        ]);
        $this->add($money);



    }



}
