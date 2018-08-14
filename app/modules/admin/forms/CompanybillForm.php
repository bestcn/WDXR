<?php
namespace Wdxr\Modules\Admin\Forms;


use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Repositories\Chairman;
use Wdxr\Modules\Admin\Controllers\SelectController;
class CompanybillForm extends Form
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

        //房租发票
        $rent = new File('rent[]', [
            'class' => 'form-control',
            'multiple' => 'multiple'
        ]);
        $this->add($rent);

        //房租收条
        $rent_receipt = new File('rent_receipt[]', [
            'class' => 'form-control',
            'multiple' => 'multiple'
        ]);
        $this->add($rent_receipt);

        //房租合同
        $rent_contract = new File('rent_contract[]', [
            'class' => 'form-control',
            'multiple' => 'multiple'
        ]);
        $this->add($rent_contract);

        //物业费
        $property_fee = new File('property_fee[]', [
            'class' => 'form-control',
            'multiple' => 'multiple'
        ]);
        $this->add($property_fee);

        //水费
        $water_fee = new File('water_fee[]', [
            'class' => 'form-control',
            'multiple' => 'multiple'
        ]);
        $this->add($water_fee);

        //电费
        $electricity = new File('electricity[]', [
            'class' => 'form-control',
            'multiple' => 'multiple'
        ]);
        $this->add($electricity);


        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '提交'
        ]));
    }

}