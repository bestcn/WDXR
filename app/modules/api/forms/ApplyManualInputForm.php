<?php
/**
 * Created by PhpStorm.
 * User: dh
 * Date: 2017/10/12
 * Time: 9:47
 */
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Wdxr\Models\Repositories\Company as RepoCompany;

class ApplyManualInputForm extends \Phalcon\Forms\Form
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

        $district = new Text('district');
        $district->addValidator(new PresenceOf([
            'message' => '请选择区县',
            "cancelOnFail" => true,
        ]));
        $this->add($district);

        $address = new Text('address');
        $address->addValidator(new PresenceOf([
            'message' => '请填写详细地址',
            "cancelOnFail" => true,
        ]));
        $this->add($address);

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

        $company_name = new Text('name');
        $company_name->addValidator(new PresenceOf([
            'message' => '请填写企业名称',
        ]));
        $this->add($company_name);

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

    }

}
