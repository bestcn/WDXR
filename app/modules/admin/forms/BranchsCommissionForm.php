<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\BranchsLevels;

class BranchsCommissionForm extends Form
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

        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
            $this->add($id);
        }

        $level = BranchsLevels::find(["level_status = 1"]);
        $level = new Select('level', $level, [
            'class' => 'form-control m-b',
            'using' => ['id', 'level_name']
        ]);
        $level->addValidator(new PresenceOf([
            'message' => '请选择等级名称',
        ]));
        $this->add($level);


        //业绩总额
        $amount = new Text('amount', [
            'class' => 'form-control',
            'placeholder' => '请填写业绩总额'
        ]);

        $this->add($amount);


        //比率
        $ratio = new Text('ratio', [
            'class' => 'form-control',
            'placeholder' => '请填写提成比率'
        ]);
        $this->add($ratio);


        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}