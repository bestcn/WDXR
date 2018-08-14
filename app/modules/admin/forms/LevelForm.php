<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Company;
use Wdxr\Modules\Admin\Controllers\SelectController;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;
class LevelForm extends Form
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

        //级别名称
        $level_name = new Text("level_name", [
            'class' => 'form-control',
            'placeholder' => '请填写级别名称'
        ]);
        $this->add($level_name);
        //级别状态
        $select_options = ['0' => '禁用', '1' => '开启'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $level_status = new Select('level_status', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($level_status);

        //是否默认
        $select_options = ['0' => '否', '1' => '是'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $is_default = new Select('is_default', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($is_default);

        //级别金额
        $level_money = new Text("level_money", [
            'class' => 'form-control',
            'placeholder' => '请填写级别金额'
        ]);
        $this->add($level_money);

        //每天返现金额金额
        $day_amount = new Text("day_amount", [
            'class' => 'form-control',
            'placeholder' => '请填写每天返现金额'
        ]);
        $this->add($day_amount);

        //详细报销信息
        $info = new Text("info", [
            'class' => 'form-control',
            'placeholder' => '请填写详细报销信息'
        ]);
        $this->add($info);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}