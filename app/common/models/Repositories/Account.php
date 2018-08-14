<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/9/19
 * Time: 15:40
 */
namespace Wdxr\Models\Repositories;

use \Wdxr\Models\Entities\Account as EntityAccount;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Account
{

    public function getLast()
    {
        return EntityAccount::query()
            ->where('status = 1')
            ->orderBy('id DESC')
            ->execute();
    }

    static public function getAccountById($id)
    {
        /**
         * @var $admin EntityAccount
         */
        $account = EntityAccount::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $account;
    }

    public function addNew($data)
    {
        $account = new EntityAccount();
        $account->setBank($data["bank"]);
        $account->setBankCard($data["bank_card"]);
        $account->setBankType($data["bank_type"]);
        $account->setRemark($data["remark"]);
        if (!$account->save()) {
            throw new InvalidRepositoryException($account->getMessages()[0]);
        }
        return true;
    }

    static public function delete($id)
    {
        $account = Account::getAccountById($id);
        if (!$account) {
            throw new InvalidRepositoryException("账户没有找到");
        }

        if (!$account->delete()) {
            throw new InvalidRepositoryException("账户删除失败");
        }

        return true;
    }

}