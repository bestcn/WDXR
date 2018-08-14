<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
class CommissionForm extends Form
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


        //提成类型
        $select_options = ['1' => '业务员', '2' => '合伙人'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $type = new Select('type', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($type);

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