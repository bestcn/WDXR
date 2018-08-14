<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\BankList as EntityBankList;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class BankList
{

    /**
     * @param $id
     * @return EntityBankList
     */
    static public function getBankListById($id)
    {
        /**
         * @var $admin EntityBankList
         */
        $BankList = EntityBankList::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $BankList;
    }

    public function getLast()
    {
        return EntityBankList::query()
            ->where('bank_status = 1')
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $BankList = new EntityBankList();
        $BankList->setBankName($data["bank_name"]);
        $BankList->setBankCode($data["bank_list"]);
        $BankList->setBankStatus($data['bank_status']);
        if (!$BankList->save()) {
            throw new InvalidRepositoryException($BankList->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $BankList = BankList::getBankListById($id);
        $BankList->setBankName($data["bank_name"]);
        $BankList->setBankCode($data["bank_name"]);
        $BankList->setBankStatus($data['bank_status']);
        if (!$BankList->save()) {
            throw new InvalidRepositoryException($BankList->getMessages()[0]);
        }

        return true;
    }

    public function deleteBankList($id)
    {
        $BankList = BankList::getBankListById($id);
        if (!$BankList) {
            throw new InvalidRepositoryException("银行没有找到");
        }

        if (!$BankList->delete()) {
            throw new InvalidRepositoryException("银行删除失败");
        }

        return true;
    }

    static public function getList()
    {
        return EntityBankList::query()
            ->where('bank_status = 1')
            ->execute();
    }

}