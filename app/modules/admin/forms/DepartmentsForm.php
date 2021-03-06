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
use Wdxr\Models\Entities\Departments;

class DepartmentsForm extends Form
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
            'placeholder' => '请填写部门名称'
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => '请填写部门名称',
                "cancelOnFail" => true,
            ])
        ]);
        $name->setFilters(['trim']);
        $this->add($name);

        $description = new TextArea('description', [
            'class' => 'form-control',
            'placeholder' => '请填写部门描述'
        ]);
        $description->addValidators([
            new PresenceOf([
                'message' => '请填写部门描述',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($description);

        //order by
        $order_by = new Text('orderBy', [
            'class' => 'form-control',
            'placeholder' => '请填写排序,数字越小越靠前',
        ]);
        $order_by->addValidators([
            new PresenceOf([
                'message' => '请填写排序',
                "cancelOnFail" => true,
            ]),
        ]);
        $this->add($order_by);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}