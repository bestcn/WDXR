<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Wdxr\Models\Repositories\CompanyBank;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Modules\Admin\Controllers\SelectController;
class SelectForm extends Form
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

        //省
        $select = new SelectController();
        $select_options = $select->get_provinceAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $provinces = new Select('province', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($provinces);
        //市

        $select_options = $select->get_citieAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $cities = new Select('city', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($cities);
        //区

        $select_options = $select->get_areaAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $areas = new Select('district', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($areas);


        //开户省
        $select = new SelectController();
        $select_options = $select->get_provinceAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $bank_provinces = new Select('bank_province', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($bank_provinces);
        //开户市

        $select_options = $select->get_citieAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $bank_cities = new Select('bank_city', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($bank_cities);
        //开户区

        $select_options = $select->get_areaAction();

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $bank_areas = new Select('bank_district', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($bank_areas);

        //如果是修改信息
        if($options['edit']){

            //获取修改的分站信息
            $url = explode('/',$_REQUEST['_url']);
            $branch_id = $url[count($url)-2];
            $branch = CompanyInfo::getLastCompanyInfo($branch_id)->toArray();
            $company_bank = CompanyBank::getBankcard($branch_id,CompanyBank::CATEGORY_MASTER);

            //市
            $select_options = $select->get_edit_citieAction($branch['province']);
            if(isset($options['search']) && $options['search'] === true)
            {
                array_unshift($select_options, '');
            }
            $cities = new Select('city', $select_options, [
                'class' => 'form-control',
            ]);

            $this->add($cities);

            //区
            $select_options = $select->get_edit_areaAction($branch['city']);

            if(isset($options['search']) && $options['search'] === true)
            {
                array_unshift($select_options, '');
            }
            $areas = new Select('district', $select_options, [
                'class' => 'form-control',
            ]);
            $this->add($areas);

            if($company_bank){
                //开户市
                $select_options = $select->get_edit_citieAction($company_bank->getProvince());
                if(isset($options['search']) && $options['search'] === true)
                {
                    array_unshift($select_options, '');
                }
                $bank_cities = new Select('bank_city', $select_options, [
                    'class' => 'form-control',
                ]);

                $this->add($bank_cities);

                //开户区
                $select_options = $select->get_edit_areaAction($company_bank->getCity());

                if(isset($options['search']) && $options['search'] === true)
                {
                    array_unshift($select_options, '');
                }
                $bank_areas = new Select('bank_district', $select_options, [
                    'class' => 'form-control',
                ]);
                $this->add($bank_areas);
            }
        }






        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}