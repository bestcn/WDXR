<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Rterm as EntityRterm;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Rterm
{

    /**
     * @param $id
     * @return EntityRterm
     */
    static public function getTermById($id)
    {
        $Term = EntityRterm::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Term;
    }

    /**
     * @param $payment
     * @return \Wdxr\Models\Entities\Rterm
     */
    public function getTermByPayment($payment)
    {
        $Term = EntityRterm::findFirst(['conditions' => 'payment = :payment:', 'bind' => ['payment' => $payment]]);
        return $Term;
    }

    public function getLast()
    {
        return EntityRterm::query()
            ->orderBy('id asc')
            ->execute();
    }

    public function seletcPayment($payment)
    {
        $Term = EntityRterm::findFirst(['conditions' => 'payment = :payment:', 'bind' => ['payment' => $payment]]);
        return $Term;
    }


    public function addNew($data)
    {
        $term = new EntityRterm();
        $term->setPayment($data["payment"]);
        $term->setTerm($data["term"]);
        $term->setType($data["type"]);
        $term->setTime(time());
        if (!$term->save()) {
            throw new InvalidRepositoryException($term->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $term = Rterm::getTermById($id);
        $term->setPayment($data["payment"]);
        $term->setTerm($data["term"]);
        $term->setType($data["type"]);
        $term->setTime(time());
        if (!$term->save()) {
            throw new InvalidRepositoryException($term->getMessages()[0]);
        }

        return true;
    }

    static public function deleteTerm($id)
    {
        $Term = Rterm::getTermById($id);
        if (!$Term) {
            throw new InvalidRepositoryException("未找到设置");
        }

        if (!$Term->delete()) {
            throw new InvalidRepositoryException("设置删除失败");
        }

        return true;
    }

}