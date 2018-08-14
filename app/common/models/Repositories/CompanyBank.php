<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CompanyBank as EntityCompanyBank;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class CompanyBank extends Repositories
{

    const TYPE_NONE = 0;
    const TYPE_PUBLIC = 1;
    const TYPE_PRIVATE = 2;

    const CATEGORY_MASTER = 1;
    const CATEGORY_WORK = 2;

    static private $_instance = null;

    /**
     * @param $id
     * @return EntityCompanyBank
     */
    static public function getCompanyBankById($id)
    {
        if(isset(self::$_instance[$id]) === false) {
            self::$_instance[$id] = EntityCompanyBank::findFirstById($id);
        }
        return self::$_instance[$id];
    }

    /**
     * 主要银行卡和绩效银行卡只能有一张
     * 如果已经存在则修改已经存在的数据
     * @param $company_id
     * @param $data
     * @param int $category
     * @return bool
     * @throws InvalidRepositoryException
     */
    public static function saveCompanyBank($company_id, $data, $category = self::CATEGORY_MASTER)
    {
        if (($company_bank = self::getExistBankcard($company_id, $category)) === false) {
            $company_bank = new EntityCompanyBank();
        }
        $company_bank->setCompanyId($company_id);
        $company_bank->setBank($data['bank']);
        $company_bank->setBankType($data['bank_type']);
        $company_bank->setNumber($data['number']);
        $company_bank->setProvince($data['province']);
        $company_bank->setCity($data['city']);
        $company_bank->setAddress($data['address']);
        $company_bank->setAccount($data['account']);
        $company_bank->setCategory($category);
        $company_bank->setBankcardPhoto($data['bankcard_photo']);
        if (!$company_bank->save()) {
            $message = $company_bank->getMessages()[0]->getMessage() ? : "保存企业银行卡信息失败";
            throw new InvalidRepositoryException($message);
        }

        return true;
    }

    public static function getBankcard($company_id, $category)
    {
        $company_bank = EntityCompanyBank::findFirst([
            "company_id = :company_id: and category = :category:",
            'bind' => ['company_id' => $company_id, 'category' => $category]
        ]);
        return $company_bank;
    }

    public static function getBank($company_id, $category)
    {
        $company_bank = EntityCompanyBank::findFirst([
            "company_id = :company_id: and category = :category:",
            'bind' => ['company_id' => $company_id, 'category' => $category]
        ]);
        if ($company_bank) {
            return $company_bank;
        }
        throw new InvalidRepositoryException('未找到银行卡信息--'.$company_id);
    }

    public static function getExistBankcard($company_id, $category)
    {
        $company_bank = false;
        if ($category == self::CATEGORY_MASTER) {
            $company_bank = self::getBankcard($company_id, self::CATEGORY_MASTER);
        } elseif ($category == self::CATEGORY_WORK) {
            $company_bank = self::getBankcard($company_id, self::CATEGORY_WORK);
        }

        return $company_bank;
    }

    static public function getWorkBankcard($company_id)
    {
        $company_bank = self::getBankcard($company_id, self::CATEGORY_WORK);
        if($company_bank === false) {
            $company_bank = self::getBankcard($company_id, self::CATEGORY_MASTER);
        }
        return $company_bank;
    }

    static public function getBankAddress($id)
    {
        $company_bank = self::getCompanyBankById($id);

        $bank = $company_bank->getBank(); $address = $company_bank->getAddress();

        if(strpos($address, $company_bank->getBank()) === false) {
            $address = $bank.$address;
        }

        return $address;
    }

    /**
     * 获取企业的所有银行卡
     * @param $company_id
     * @return EntityCompanyBank
     */
    public function getBankCards($company_id)
    {
        $banks = EntityCompanyBank::find([
            "company_id = :company_id:",
            'bind' => ['company_id' => $company_id]
        ]);
        return $banks;
    }

}