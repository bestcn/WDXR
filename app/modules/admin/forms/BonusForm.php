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
class BonusForm extends Form
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



        $select_options = ['1' => '事业合伙人', '2' => '普惠'];
        if(isset($options['search']) && $options['search'] === true)
        {
            array_unshift($select_options, '');
        }

        //推荐人状态
        $recommend = new Select('recommend', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($recommend);

        //新客户状态
        $customer = new Select('customer', $select_options, [
            'class' => 'form-control',
        ]);
        $this->add($customer);

        //1
        $first = new Text("first", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $first->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($first);

        //2
        $second = new Text("second", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $second->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($second);

        //3
        $third = new Text("third", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $third->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($third);

        //4
        $fourth = new Text("fourth", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $fourth->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($fourth);

        //5
        $fifth = new Text("fifth", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $fifth->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($fifth);

        //6
        $sixth = new Text("sixth", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $sixth->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($sixth);

        //7
        $seventh = new Text("seventh", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $seventh->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($seventh);

        //8
        $eighth = new Text("eighth", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $eighth->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($eighth);

        //9
        $ninth = new Text("ninth", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $ninth->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($ninth);

        //10
        $tenth = new Text("tenth", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $tenth->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($tenth);

        //11
        $eleventh = new Text("eleventh", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $eleventh->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($eleventh);

        //12
        $twelfth = new Text("twelfth", [
            'class' => 'form-control',
            'placeholder' => '请填写金额'
        ]);
        $twelfth->addValidators([
            new PresenceOf([
                'message' => '请填写金额',
                "cancelOnFail" => true,
            ])
        ]);
        $this->add($twelfth);




        //submit
        $this->add(new Submit('submit', [
            'class' => 'btn btn-primary',
            'value' => '保存'
        ]));
    }

}