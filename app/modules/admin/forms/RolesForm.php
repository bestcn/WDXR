<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Entities\Departments;

class RolesForm extends Form
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

        //name
        $name = new Text("name", [
            'class' => 'form-control',
            'placeholder' => '请使用英文填写角色名称'
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => '请使用英文填写角色名称',
                "cancelOnFail" => true,
            ])
        ]);
        $name->setFilters(['trim']);
        $this->add($name);

        $description = new TextArea('description', [
            'class' => 'form-control',
            'placeholder' => '请填写角色描述'
        ]);
        $this->add($description);

        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}