<?php
namespace Wdxr\Modules\Api\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\checkPayment;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Modules\Api\Controllers\LoanController;

class LoanTwoForm extends \Phalcon\Forms\Form
{
    public function initialize($entity, $options)
    {


//        $purpose = new Text('purpose');
//        $purpose->addValidator(new PresenceOf([
//            'message' => '请填写借款用途',
//            "cancelOnFail" => true,
//        ]));
//        $this->add($purpose);



        $money = new Text('money');
        $money->addValidators([
            new PresenceOf([
                'message' => '请填写申请金额',
                "cancelOnFail" => true,
            ]),
            new Between([
                "minimum" => 10000,
                "maximum" => 80000,
                "message" => "您的申请金额必须在一万元至八万元人民币之间",
            ]),
            new Regex([
                "message" => "您的申请金额必须是一个合法的正整数数字",
                "pattern" => "/^\+?[1-9][0-9]*$/",
            ])
        ]);
        $this->add($money);


        $term = new Text('term');
        $term->addValidator(new PresenceOf([
            'message' => '请选择申请期限',
            "cancelOnFail" => true,
        ]));
        $this->add($term);


    }


}
