<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\LicenseNum;

class ApplyOneValidation extends \Phalcon\Validation
{

    public function initialize()
    {
        $this->add('type', new InclusionIn([
            'message' => '企业性质选择错误',
            'domain' => [RepoCompany::TYPE_COMPANY, RepoCompany::TYPE_SELF_EMPLOYED]
        ]));

        $this->add('name', new PresenceOf([
            'message' => '请填写企业名称',
        ]));

        $this->add('province', new PresenceOf([
            'message' => '请选择省份',
        ]));

        $this->add('city', new PresenceOf([
            'message' => '请选择城市',
        ]));

        //不验证区县
//        $this->add('district', new PresenceOf([
//            'message' => '请选择区县',
//        ]));

        $this->add('address', new PresenceOf([
            'message' => '请填写详细地址',
        ]));

        $this->add('licence_num', new PresenceOf([
            'message' => '请输入统一社会信用代码或注册号',
            "cancelOnFail" => true,
        ]));
        //todo 该方法存在潜在的错误
//        $this->add('licence_num', new LicenseNum());

        $this->add('legal_name', new PresenceOf([
            'message' => '请填写法定代表人',
        ]));

        $this->add('scope', new PresenceOf([
            'message' => '请填写经营范围',
        ]));

        $this->add('period', new PresenceOf([
            'message' => '请填写营业期限',
        ]));

        $this->add('licence', new PresenceOf([
            'message' => '请上传营业执照',
        ]));

        $this->add('intro', new StringLength([
            'messageMaximum' => '公司简介不能多余150个字符',
            'messageMinimum' => '公司简介不能少于20个字符',
            'max' => 150,
            'min' => 20,
        ]));

    }

}
