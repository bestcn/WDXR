<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Repositories\Chairman;
use Wdxr\Modules\Admin\Controllers\SelectController;
class CompanyinfForm extends Form
{
    public function initialize($entity = null, $options = null)
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

        //Id
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
            $this->add($id);
        } else if(isset($options['search']) && $options['search']) {
            $id = new Text("id", [
                'class' => 'form-control',
                'placeholder' => '请填写ID'
            ]);
            $this->add($id);
        }
        //联系人
        $contacts = new Text('contacts', [
            'class' => 'form-control',
            'placeholder' => '请填写联系人'
        ]);
        $this->add($contacts);

        //联系人职位
        $contact_title = new Text('contact_title', [
            'class' => 'form-control',
            'placeholder' => '请填写联系人职位'
        ]);
        $this->add($contact_title);

        //联系方式
        $contact_phone = new Text('contact_phone', [
            'class' => 'form-control',
            'placeholder' => '请填写联系方式'
        ]);
        $contact_phone->addValidators([
            new PresenceOf([
                'message' => '请填写联系方式',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($contact_phone);

        //企业详细地址
        $address = new Text('address', [
            'class' => 'form-control',
            'placeholder' => '请填写详细地址'
        ]);
        $this->add($address);

        //企业简介
        $intro = new Text('intro', [
            'class' => 'form-control',
            'placeholder' => '请填写简介'
        ]);
        $this->add($intro);


        //账户性质
        $select_options = ['2' => '个人账户', '1' => '对公账户'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $BankType = new Select('BankType', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($BankType);

        //银行名称
        $Bank = new Text("Bank", [
            'class' => 'form-control',
            'placeholder' => '请填写银行名称'
        ]);
        $Bank->addValidators([
            new PresenceOf([
                'message' => '请填写银行名称',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($Bank);

        //银行卡号
        $bankcard = new Text('bankcard', [
            'class' => 'form-control',
            'placeholder' => '请填写卡号'
        ]);
        $this->add($bankcard);

        //开户行
        $bank_name = new Text('bank_name', [
            'class' => 'form-control',
            'placeholder' => '请填写开户行全称'
        ]);
        $this->add($bank_name);


        //合同编号
        $contract_num = new Text('contract_num', [
            'class' => 'form-control',
            'placeholder' => '请填写合同编号'
        ]);
        $this->add($contract_num);


        /*
         * 开户地址
         */


        //开户省
        $select = new SelectController();
        $select_options = $select->get_provinceAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $bank_province = new Select('bank_province', $select_options, [
            'class' => 'form-control',
            'id' => 'bank_provinces'
        ]);
        $this->add($bank_province);

        //市
        $select_options = $select->get_citieAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $bank_city = new Select('bank_city', $select_options, [
            'class' => 'form-control',
            'id' => 'bank_cities'
        ]);
        $this->add($bank_city);

        //区
        $select_options = $select->get_areaAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $bank_district = new Hidden('bank_district', $select_options, [
            'class' => 'form-control',
            'id' => 'bank_areas'
        ]);
        $this->add($bank_district);

/*
 * 企业地址
 */
        //省
        $select = new SelectController();
        $select_options = $select->get_provinceAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $province = new Select('province', $select_options, [
            'class' => 'form-control',
            'id' => 'provinces'
        ]);
        $this->add($province);
        //市
        $select_options = $select->get_citieAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $city = new Select('city', $select_options, [
            'class' => 'form-control',
            'id' => 'cities'
        ]);
        $this->add($city);
        //区
        $select_options = $select->get_areaAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $district = new Select('area', $select_options, [
            'class' => 'form-control',
            'id' => 'areas'
        ]);
        $this->add($district);



        //图片
        $files = new File('files[]', [
            'class' => 'form-control'
        ]);
        $this->add($files);


        //营业执照
        $licence = new File('licence', [
            'class' => 'form-control'
        ]);
        $this->add($licence);

        //开户许可证
        $account_permit = new File('account_permit', [
            'class' => 'form-control'
        ]);
        $this->add($account_permit);

        //机构代码
        $credit_code = new File('credit_code', [
            'class' => 'form-control'
        ]);
        $this->add($credit_code);

        //法人身份证正面
        $idcard_up = new File('idcard_up', [
            'class' => 'form-control'
        ]);
        $this->add($idcard_up);

        //法人身份证背面
        $idcard_down = new File('idcard_down', [
            'class' => 'form-control'
        ]);
        $this->add($idcard_down);

        //法人手持身份证照片
        $photo = new File('photo', [
            'class' => 'form-control'
        ]);
        $this->add($photo);

        //银行卡照片
        $bankcard_photo = new File('bankcard_photo', [
            'class' => 'form-control'
        ]);
        $this->add($bankcard_photo);

        //纸质合同照片
        $contract = new File('contract', [
            'class' => 'form-control'
        ]);
        $this->add($contract);

        //签订人照片
        $sign_photo = new File('sign_photo', [
            'class' => 'form-control'
        ]);
        $this->add($sign_photo);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '提交'
        ]));
    }

}