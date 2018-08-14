<?php
namespace Wdxr\Modules\Company\Forms;


use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Modules\Admin\Controllers\SelectController;
class ApplyForm extends Form
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

        //username
        $branch_name = new Text("branch_name", [
            'class' => 'form-control',
            'placeholder' => '请填写分公司名称'
        ]);
        $branch_name->addValidators([
            new PresenceOf([
                'message' => '请填写分公司名称',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($branch_name);




        //省
        $select = new SelectController();
        $select_options = $select->get_provinceAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $provinces = new Select('provinces', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($provinces);
        //市

        $select_options = $select->get_citieAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $cities = new Select('cities', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($cities);
        //区

        $select_options = $select->get_areaAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $areas = new Select('areas', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($areas);



        //地区
        $branch_area = new Text('branch_area', [
            'class' => 'form-control',
            'placeholder' => '请填写详细地址'
        ]);
        $this->add($branch_area);








        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}