<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Entities\BranchsLevels;
use Wdxr\Models\Entities\Regions;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Modules\Admin\Controllers\SelectController;
use Wdxr\Models\Repositories\Branch;
class BranchsForm extends Form
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

        //branchlevel
        $branch_options = BranchsLevels::find(["level_status = 1"]);
        $branch_level = new Select('branch_level', $branch_options, [
            'class' => 'form-control m-b',
            'using' => ['id', 'level_name']
        ]);
        $branch_level->addValidator(new PresenceOf([
            'message' => '请选择等级名称',
        ]));
        $this->add($branch_level);


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
//
//        //省
//        $select = new SelectController();
//        $select_options = Regions::find(["depth = 1"]);
//
//        if(isset($options['search']) && $options['search'] === true)
//        {
//            array_unshift($select_options, '');
//        }
//        $provinces = new Select('provinces', $select_options, [
//            'class' => 'form-control',
//        ]);
//        $this->add($provinces);
//        //市
//
//        $select_options =  Regions::find(["depth = 2 and pid = :pid:", 'bind' => ['pid' => $select_options[0]->getId()]]);
//
//        if(isset($options['search']) && $options['search'] === true)
//        {
//            array_unshift($select_options, '');
//        }
//        $cities = new Select('cities', $select_options, [
//            'class' => 'form-control',
//        ]);
//        $this->add($cities);
//
//        //区
//
//        $select_options =  Regions::find(["depth = 3 and pid = :pid:", 'bind' => ['pid' => $select_options[0]->getId()]]);
//
//        if(isset($options['search']) && $options['search'] === true)
//        {
//            array_unshift($select_options, '');
//        }
//        $areas = new Select('areas', $select_options, [
//            'class' => 'form-control',
//        ]);
//        $this->add($areas);


        //如果是修改信息
        if($options['edit']){

            //获取修改的分站信息
            $url = explode('/',$_REQUEST['_url']);
            $branch_id = $url[count($url)-1];
            $branch = Branch::getBranchById($branch_id)->toArray();

            //市
            $select_options = $select->get_edit_citieAction($branch['provinces']);
            if(isset($options['search']) && $options['search'] === true)
            {
                array_unshift($select_options, '');
            }
            $cities = new Select('cities', $select_options, [
                'class' => 'form-control',
            ]);
            $this->add($cities);

            //区
            $select_options = $select->get_edit_areaAction($branch['cities']);

            if(isset($options['search']) && $options['search'] === true)
            {
                array_unshift($select_options, '');
            }
            $areas = new Select('areas', $select_options, [
                'class' => 'form-control',
            ]);
            $this->add($areas);
        }



        //地区
        $branch_area = new Text('branch_area', [
            'class' => 'form-control',
            'placeholder' => '请填写详细地址'
        ]);
        $this->add($branch_area);

//管理员
        $arr = array();
        $admins = Admins::find(["columns" => "id, name", "conditions" => 'on_job = :on_job: and status = :status: and is_lock = :is_lock:', 'bind' => ['on_job' => 1, 'status' => 1, 'is_lock' => 0]]);

        foreach($admins->toArray() as $k=>$v){
            $arr[$v['id']] = $v['name'];
        }
        $select_options = $arr;

        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $branch_admin_id = new Select('branch_admin_id', $select_options, [
            'class' => 'form-control',
        ]);

        $this->add($branch_admin_id);

        //管理员ID
        $branch_admin = new Hidden('branch_admin');
        $this->add($branch_admin);
//管理员

        //联系方式
        $branch_phone = new Text('branch_phone', [
            'class' => 'form-control',
            'placeholder' => '请填写联系方式'
        ]);
        $branch_phone->addValidators([
            new PresenceOf([
                'message' => '请填写联系方式',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($branch_phone);

        //账户及银行
        $branch_account = new Text('branch_account', [
            'class' => 'form-control',
            'placeholder' => '请填写银行卡'
        ]);
        $this->add($branch_account);

        $branch_bank = new Text('branch_bank', [
            'class' => 'form-control',
            'placeholder' => '请填写银行名称'
        ]);
        $this->add($branch_bank);


        //状态
        $select_options = ['0' => '否', '1' => '是'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }

        //active
        $branch_status = new Select('branch_status', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($branch_status);


//        //人员分配列表
//        $all = new Admin();
//        $all = $all->getAll()->toArray();
//        $select_options = array();
//        foreach($all as $k=>$v){
//            $select_options[$v['id']] = $v['name'];
//        }
//        if(isset($options['search']) && $options['search'] === true)
//        {
//            array_unshift($select_options, '');
//        }
//        $salesmans = new Select('salesmans', $select_options, [
//            'class' => 'form-control dual_select',
//            'multiple' => 'multiple',
//        ]);
//        $this->add($salesmans);



        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}