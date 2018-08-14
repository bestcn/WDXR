<?php
namespace Wdxr\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Wdxr\Models\Repositories\Account;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;

class AuditForm extends Form
{
    public function initialize($entity, $options)
    {
        // CSRF
//        $csrf = new Hidden('csrf');
//        $csrf->addValidator(
//            new Identical([
//                'value' => $this->security->getSessionToken(),
//                'message' => '非法访问',
//            ])
//        );
//        $csrf->clear();
//        $this->add($csrf);

        $verify_id = new Hidden('verify_id');
        $verify_id->addValidator(new PresenceOf([
                'message' => '缺少审核参数',
        ]));
        $this->add($verify_id);

        $accounts = (new Account())->getLast();
        $account = new Select('account', $accounts, [
            'class' => 'form-control',
            'using' => ['id', 'bank']
        ]);
        if($options['status'] == RepoCompanyVerify::STATUS_OK) {
            $account->addValidator(new PresenceOf([
                'message' => '请选择放款账户',
            ]));
        }
        $this->add($account);

        $remark = new TextArea('remark', [
            'class' => 'form-control m-b',
        ]);
        if($options['status'] == RepoCompanyVerify::STATUS_FAIL) {
            $remark->addValidator(new PresenceOf([
                'message' => '请填写驳回原因',
            ]));
        }
        $this->add($remark);

    }

}