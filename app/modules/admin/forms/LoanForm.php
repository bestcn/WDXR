<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\Level;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\LoansInfo;
use Wdxr\Modules\Admin\Controllers\SelectController;

class LoanForm extends Form
{
    public function initialize($entity = null, $options = null)
    {


        //请填写申请人名称
        $loan_name = new Text("name", [
            'class' => 'form-control',
            'placeholder' => '请填写申请人名称'
        ]);
        $loan_name->addValidators([
            new PresenceOf([
                'message' => '请填写申请人名称',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($loan_name);


        //企业级别
        $level = new Level();
        $select_options = $level->get_level();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $level_data = new Select('level_id', $select_options, [
            'class' => 'form-control',
            'id' => 'level'
        ]);
        $this->add($level_data);

        //性别
        $sex_options = ['1' => '男', '2' => '女'];
        $sex_type = new Select('sex', $sex_options, [
            'class' => 'form-control',
        ]);
        $this->add($sex_type);

        //申请期限
        $term_options = ['1' => '三个月', '2' => '六个月', "3" => "九个月", "4" => "十二个月"];
        $term_type = new Select('term', $term_options, [
            'class' => 'form-control',
        ]);
        $this->add($term_type);

        //请填写身份证号
        $identity_account = new Text("identity", [
            'class' => 'form-control',
            'placeholder' => '请填写身份证号'
        ]);
        $identity_account->addValidators([
            new PresenceOf([
                'message' => '请填写身份证号',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($identity_account);




        //详细地址
        $address_program = new Text("address", [
            'class' => 'form-control',
            'placeholder' => '请填写详细地址'
        ]);
        $address_program->addValidators([
            new PresenceOf([
                'message' => '请填写详细地址',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($address_program);

        //经营范围
        $business = new Text("business", [
            'class' => 'form-control',
            'placeholder' => '请填写经营范围'
        ]);
        $business->addValidators([
            new PresenceOf([
                'message' => '请填写经营范围',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($business);



        //省
        $select = new SelectController();
        $select_options = $select->get_provinceAction();

        if (isset($options['search']) && $options['search'] === true) {
            array_unshift($select_options, '');
        }
        $provinces = new Select('province', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($provinces);
        //市

        $select_options = $select->get_citieAction();

        if (isset($options['search']) && $options['search'] === true) {
            array_unshift($select_options, '');
        }
        $cities = new Select('city', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($cities);
        //区

        $select_options = $select->get_areaAction();

        if (isset($options['search']) && $options['search'] === true) {
            array_unshift($select_options, '');
        }
        $areas = new Select('area', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($areas);

        //如果是修改信息
        if($options['edit']){

            //获取修改的分站信息
            $url = explode('/',$_REQUEST['_url']);
            $verify_id = $url[count($url)-1];
            $verify = CompanyVerify::getCompanyVerifyById($verify_id);
            $info = LoansInfo::getLoansInfoById($verify->getDataId())->toArray();
            //市
            $select_options = $select->get_edit_citieAction($info['province']);
            if(isset($options['search']) && $options['search'] === true)
            {
                array_unshift($select_options, '');
            }
            $cities = new Select('city', $select_options, [
                'class' => 'form-control',
            ]);

            $this->add($cities);

            //区
            $select_options = $select->get_edit_areaAction($info['city']);

            if(isset($options['search']) && $options['search'] === true)
            {
                array_unshift($select_options, '');
            }
            $areas = new Select('area', $select_options, [
                'class' => 'form-control',
            ]);
            $this->add($areas);

        }




        $admins = Admins::find(["columns" => "id, name", "conditions" => 'on_job = :on_job: and status = :status: and is_lock = :is_lock:', 'bind' => ['on_job' => 1, 'status' => 1, 'is_lock' => 0]]);
        $device_id = new Select('admin_id', $admins, [
            'class' => 'form-control',
            'placeholder' => '请选择业务员',
            'using' => ['id', 'name']
        ]);
        $device_id->addValidators([
            new PresenceOf([
                'message' => '请选择业务员',
            ])
        ]);
        $this->add($device_id);



        $contact_phone = new Text('tel',[
            'class' => 'form-control',
            'placeholder' => '请填写联系人手机号'
        ]);
        $contact_phone->addValidators([
            new PresenceOf([
                'message' => '请填写联系人手机号',
                "cancelOnFail" => true,
            ]),
            new Regex([
                "message" => "联系人手机号格式不正确",
                "pattern" => "/^1[34578]\d{9}$/",
            ])
        ]);
        $this->add($contact_phone);


        //请填写申请金额
        $money = new Text("money", [
            'class' => 'form-control',
            'placeholder' => '请填写申请金额'
        ]);
        $money->addValidators([
            new PresenceOf([
                'message' => '请填写申请金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($money);

        //申请用途
        $purpose = new Text("purpose", [
            'class' => 'form-control',
            'placeholder' => '请填写申请用途',
            'value'=>'支付河北华企管家信息科技有限公司年度服务费'
        ]);
        $purpose->addValidators([
            new PresenceOf([
                'message' => '请填写申请用途',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($purpose);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '提交'
        ]));

    }
}