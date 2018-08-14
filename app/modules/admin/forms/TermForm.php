<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
class TermForm extends Form
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


        //缴费类型
        $select_options = ['1' => '转账', '2' => '现金','3' => 'POS','4' => '贷款'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $payment = new Select('payment', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($payment);

        //公司名称
        $company_id = new Text('company_name', [
            'class' => 'form-control',
            'disabled' => 'disabled'
        ]);
        $this->add($company_id);

        //审核期限
        $term = new Text('term', [
            'class' => 'form-control',
            'placeholder' => '请填写审核期限'
        ]);

        $this->add($term);

        //期限类型
        $select_options = ['0' => '日', '1' => '月','2' => '年'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }
        $type = new Select('type', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($type);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}