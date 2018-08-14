<?php
namespace Phalcon\Validation\Validator;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Wdxr\Models\Repositories\Loan;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Services\Loan as SerLoan;

class checkPayment extends Validator
{

    public function validate(Validation $validation, $attribute)
    {
        $value = $validation->getValue($attribute);
        $message = ($this->hasOption('message')) ? $this->getOption('message') : '您已有申请正在审核中，请等待审核结果！';

        if(self::isCheckPayment($value) === false) {
            $validation->appendMessage(new Message($message, $attribute, 'company_id'));
            return false;
        }

        return true;
    }

    //验证是否正在审核
    static function isCheckPayment($companyId){

        $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($companyId);
        $loan = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($companyId);
        if($payment === false && $loan === false ){
            return true;
        }else{
            return false;
        }
    }

}