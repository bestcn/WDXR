<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Term as EntityTerm;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Term extends Repositories
{

    /**
     * @param $id
     * @return EntityTerm
     */
    public static function getTermById($id)
    {
        /**
         * @var $admin EntityTerm
         */
        $Term = EntityTerm::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);

        return $Term;
    }

    public function getLast()
    {
        return EntityTerm::query()
            ->orderBy('id asc')
            ->execute();
    }

    public function seletcPayment($payment)
    {
        $Term = EntityTerm::findFirst(['conditions' => 'payment = :payment:', 'bind' => ['payment' => $payment]]);
        return $Term;
    }

    /**
     * @param $company_id
     * @return bool|EntityTerm
     */
    public function getTermByCompanyId($company_id)
    {
        $payment = CompanyPayment::getPaymentByCompanyId($company_id, CompanyPayment::STATUS_OK);
        if ($payment === false) {
            return false;
        }
        $term = EntityTerm::findFirst([
            'conditions' => 'payment = :payment:',
            'bind' => ['payment' => $payment->getType()]
        ]);

        return $term;
    }

    /**
     * @param $payment
     * @return \Wdxr\Models\Entities\Term
     */
    public function getTermByPayment($payment)
    {
        $Term = EntityTerm::findFirst(['conditions' => 'payment = :payment:', 'bind' => ['payment' => $payment]]);

        return $Term;
    }


    public function addNew($data)
    {
        $Term = new EntityTerm();
        $Term->setPayment($data["payment"]);
        $Term->setTerm($data["term"]);
        $Term->setType($data["type"]);
        $Term->setTime(time());
        if (!$Term->save()) {
            throw new InvalidRepositoryException($Term->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $Term = Term::getTermById($id);
        $Term->setPayment($data["payment"]);
        $Term->setTerm($data["term"]);
        $Term->setType($data["type"]);
        $Term->setTime(time());
        if (!$Term->save()) {
            throw new InvalidRepositoryException($Term->getMessages()[0]);
        }

        return true;
    }

    static public function deleteTerm($id)
    {
        $Term = Term::getTermById($id);
        if (!$Term) {
            throw new InvalidRepositoryException("未找到设置");
        }

        if (!$Term->delete()) {
            throw new InvalidRepositoryException("设置删除失败");
        }

        return true;
    }

}