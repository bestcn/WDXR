<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\BranchsLevels;
use Wdxr\Models\Entities\Regions;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Modules\Admin\Controllers\SelectController;
use Wdxr\Models\Repositories\Branch;

class CompanyNewForm extends Form
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

        //name
        $name = new Text("name", [
            'class' => 'form-control',
            'placeholder' => '请填写公司名称'
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => '请填写公司名称',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($name);


        //省
        $select = new SelectController();
        $select_options = $select->get_provinceAction();

        if (isset($options['search']) && $options['search'] === true) {
            array_unshift($select_options, '');
        }
        $provinces = new Select('provinces', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($provinces);
        //市

        $select_options = $select->get_citieAction();

        if (isset($options['search']) && $options['search'] === true) {
            array_unshift($select_options, '');
        }
        $cities = new Select('cities', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($cities);
        //区

        $select_options = $select->get_areaAction();

        if (isset($options['search']) && $options['search'] === true) {
            array_unshift($select_options, '');
        }
        $areas = new Select('areas', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($areas);


        //详细地址
        $address = new Text('address', [
            'class' => 'form-control',
            'placeholder' => '请填写详细地址'
        ]);
        $this->add($address);


        //统一信用代码
        $licence_num = new Text('licence_num', [
            'class' => 'form-control',
            'placeholder' => '请填写统一信用代码'
        ]);
        $licence_num->addValidators([
            new PresenceOf([
                'message' => '请填写统一信用代码',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($licence_num);

        //法人名称
        $legal_name = new Text('legal_name', [
            'class' => 'form-control',
            'placeholder' => '请填写法人名称'
        ]);
        $legal_name->addValidators([
            new PresenceOf([
                'message' => '请填写法人名称',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($legal_name);



        //企业性质
        $select_options = ['2' => '个体工商户', '1' => '非个体工商户'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $company_type = new Select('type', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($company_type);

        //经营期限
        $period_start = new Text('period_start', [
            'class' => 'form-control',
            'placeholder' => '请填写经营期限开始日期'
        ]);
        $period_start->addValidators([
            new PresenceOf([
                'message' => '请填写经营期限开始日期',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($period_start);

        $period_end = new Text('period_end', [
            'class' => 'form-control',
            'placeholder' => '请填写经营期限结束日期'
        ]);
        $this->add($period_end);

        //经营范围
        $scope = new TextArea('scope', [
            'class' => 'form-control',
            'placeholder' => '请填写经营范围'
        ]);
        $scope->addValidators([
            new PresenceOf([
                'message' => '请填写经营期限开始日期',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($scope);





        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}