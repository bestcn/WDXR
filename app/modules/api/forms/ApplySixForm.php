<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;

class ApplySixForm extends \Phalcon\Forms\Form
{

    public function initialize($entity, $options)
    {
        $contract_num = new Text('contract_num');
        $contract_num->addValidator(new PresenceOf([
            'message' => '请填写合同编号',
            "cancelOnFail" => true,
        ]));
        $this->add($contract_num);

        $contract = new Text('contract');
        $contract->addValidator(new PresenceOf([
            'message' => '请上传纸质合同照片',
            "cancelOnFail" => true,
        ]));
        $this->add($contract);
        $sign_photo = new Text('sign_photo');
        $sign_photo->addValidator(new PresenceOf([
            'message' => '请上传签订人照片',
            "cancelOnFail" => true,
        ]));
        $this->add($sign_photo);
    }

}
