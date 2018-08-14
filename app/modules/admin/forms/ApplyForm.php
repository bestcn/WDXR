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
    public function initialize($entity=null, $options = null)
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

        $contacts = new Text('contacts', [
            'class' => 'form-control required',
            'placeholder' => '请输入联系人姓名'
        ]);
        $contacts->addValidator(new PresenceOf([
            'message' => '请填写联系人',
        ]));
        $this->add($contacts);

        $contact_phone = new Text('contact_phone', [
            'class' => 'form-control required phone',
            'placeholder' => '请输入联系人手机号'
        ]);
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

        $contact_title = new Text('contact_title', [
            'class' => 'form-control required',
            'placeholder' => '请输入联系人职务'
        ]);
        $contact_title->addValidator(new PresenceOf([
            'message' => '请填写联系人岗位',
        ]));
        $this->add($contact_title);

        $zipcode = new Text('zipcode', [
            'class' => 'form-control required',
            'placeholder' => '请输入邮政编码',
            'value' => '050000'
        ]);
        $zipcode->addValidators([
            new PresenceOf([
                'message' => '请输入邮政编码',
            ]),
        ]);
        $this->add($zipcode);

//        $location = new Text('location', [
//            'class' => 'form-control required',
//            'placeholder' => '请输入当前地址',
//            'value' => '河北省石家庄市桥西区工农路冀企管家'
//        ]);
//        $location->addValidators([
//            new PresenceOf([
//                'message' => '请填写当前地址',
//            ]),
//        ]);
//        $this->add($location);


        $licence = new File('licence', [
            'class' => 'required',
        ]);
        $licence->addValidator(new PresenceOf([
            'message' => '请上传营业执照',
        ]));
        $this->add($licence);

        $credit_code = new File('credit_code');
        $account_permit = new File('account_permit');
//        if($options['company_type'] == RepoCompany::TYPE_COMPANY) {
////            $credit_code->addValidator(new PresenceOf([
////                'message' => '请上传机构信用代码证',
////            ]));
////            $account_permit->addValidator(new PresenceOf([
////                'message' => '请上传开户许可证',
////            ]));
//        }
        $this->add($credit_code); $this->add($account_permit);

            $admins = Admins::find(["columns" => "id, name", "conditions" => 'on_job = :on_job: and status = :status: and is_lock = :is_lock:', 'bind' => ['on_job' => 1, 'status' => 1, 'is_lock' => 0]]);
        $device_id = new Select('admin_id', $admins, [
            'class' => 'form-control',
            'placeholder' => '请选择业务员',
            'using' => ['id', 'name']
        ]);
        $this->add($device_id);

        $category = new \Category\Classification();
        $top_category = $category->getTopCategory();
        $top_data =[];
        foreach ($top_category as $key=>$val){
            $top_data[$val['code']] = $val['name'];
        }
        $top = new Select('top_category', $top_data, [
            'class' => 'form-control',
            'placeholder' => '请选择行业',
        ]);
        $this->add($top);

        $sub_category = $category->getSelectArray('A');
        $sub_data =[];
        foreach ($sub_category as $key=>$val){
            $sub_data[$val['code']] = $val['name'];
        }
        $sub = new Select('sub_category', $sub_data, [
            'class' => 'form-control',
            'placeholder' => '请选择行业',
        ]);
        $this->add($sub);

        $photo = new File('photo', [
            'class' => 'required',
        ]);
        $photo->addValidator(new PresenceOf([
            'message' => '请上传法人手持身份证照片',
        ]));
        $this->add($photo);

        $idcard_up = new File('idcard_up', [
            'class' => 'required',
        ]);
        $idcard_up->addValidator(new PresenceOf([
            'message' => '请上传身份证正面',
        ]));
        $this->add($idcard_up);

        $idcard_down = new File('idcard_down', [
            'class' => 'required',
        ]);
        $idcard_down->addValidator(new PresenceOf([
            'message' => '请上传身份证背面',
        ]));
        $this->add($idcard_down);

        $intro = new TextArea('intro', [
            'class' => 'form-control required',
            'placeholder' => '请输入公司简介',
        ]);
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


//        $recommend = new Text('recommend', [
//            'class' => 'form-control',
//            'placeholder' => '（可选）请输入推荐人或企业',
//        ]);
//        $this->add($recommend);
//
//        $manager = new Text('manager', [
//            'class' => 'form-control',
//            'placeholder' => '（可选）请输入管理人或企业',
//        ]);
//        $this->add($manager);


    }

}
