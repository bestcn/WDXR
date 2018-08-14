<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Contracts as EntityContract;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class Contract extends Repositories
{

    const STATUS_NOT = 0;
    const STATUS_IN_USE = 1;
    const STATUS_TEMP = 2;

    /**
     * 获取一个合同编号并绑定地理位置信息
     * @param $device_id
     * @param $company_id
     * @param $service_id
     * @return EntityContract
     * @throws InvalidRepositoryException
     */
    public function getLastContractNum($device_id, $company_id, $service_id)
    {
        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $service = $company_service->getService($service_id);

        //默认的合同号
        $contract_num = $this->getContractNum();
        //未生效的普惠客户没有合同号
        if ($service->getType() == CompanyService::TYPE_ORDINARY) {
            if ($service->getServiceStatus() != CompanyService::SERVICE_ENABLE) {
                $contract_num = null;
            }
        }
        //如果该服务已经绑定了合同且有合同号，则使用已有的
        if ($contract = Contract::getServiceContract($service_id)) {
            if ($contract->getContractNum()) {
                return $contract;
            }
        }

        $contract = $contract === false ? (new EntityContract()) : $contract;
        $contract->setContractStatus(self::STATUS_IN_USE);
        $contract->setDeviceId($device_id);
        $contract->setCompanyId($company_id);
        $contract->setContractNum($contract_num);
        $contract->setServiceId($service_id);

        if (!$contract->save()) {
            throw new InvalidRepositoryException('生成合同编号失败');
        }
        return $contract;
    }

    /**
     * 获取一个合同编号并绑定地理位置信息
     * @param $device_id
     * @param $company_id
     * @param $service_id
     * @return EntityContract
     * @throws InvalidRepositoryException
     */
    public function getLastLoanContractNum($device_id, $company_id, $service_id)
    {
        $contract = new EntityContract();
        $contract->setContractStatus(self::STATUS_IN_USE);
        $contract->setDeviceId($device_id);
        $contract->setCompanyId($company_id);
        $contract->setLongitude(0);
        $contract->setLatitude(0);
        $contract->setContractNum('');
        $contract->setServiceId($service_id);
        $contract->setLocation('');

        if (!$contract->save()) {
            throw new InvalidRepositoryException('生成合同编号失败');
        }
        return $contract;
    }

    /**
     * 生成合同号
     * @param int $weight 权重
     * @return string
     */
    public function getContractNum($weight = 0)
    {
        //生成新的合同编号
        $time = mktime(0, 0, 0, 5, 30, 2018);
        $contracts = $this->getTimeContracts($time);

        $exist_new_count = count($contracts);
        $prefix = str_pad($exist_new_count + 340 + $weight + 1, 7, 0, STR_PAD_LEFT);
        $end = str_pad($exist_new_count + $weight + 1, 4, 0, STR_PAD_LEFT);

        $contract_num = $prefix . "【".date('Y')."】年第" . $end . '号';

        $contract = EntityContract::findFirst([
            'conditions' => 'contract_num = ?0',
            'bind' => [$contract_num]
        ]);

        //以客户数量为基数生成，如果合同编号重复，则加权重新生成
        if ($contract === false) {
            return $contract_num;
        } else {
            $weight++;
            return $this->getContractNum($weight);
        }
    }

    public static function getInUseContractNum($company_id)
    {
        $contract = EntityContract::findFirst([
            'conditions' => 'company_id = ?0 and  contract_status = ?1',
            'bind' => [$company_id, Contract::STATUS_IN_USE], 'order' => 'id asc']);
        return $contract;
    }

    public static function getContractByServiceId($service_id)
    {
        $contract = EntityContract::findFirst([
            'conditions' => 'service_id = ?0 ',
            'bind' => [$service_id], 'order' => 'id asc']);
        return $contract;
    }


    /**
     * 保存合同签名
     * @param int $sign_id 废弃
     * @param $contract_id
     * @return EntityContract
     * @throws InvalidRepositoryException
     */
    public static function saveContractSignId($contract_id)
    {
        $contract = EntityContract::findFirst([
            'conditions' => 'contract_status = :status: and id = :id:',
            'bind' => ['status' => self::STATUS_TEMP, 'id' => $contract_id]
        ]);
        if ($contract === false) {
            throw new InvalidRepositoryException("获取合同信息失败");
        }
        $contract->setContractStatus(self::STATUS_IN_USE);
        if (!$contract->save()) {
            throw new InvalidRepositoryException("合同签名保存失败");
        }
        return $contract;
    }

    public static function getContractByCompanyId($company_id, $device_id)
    {
        return EntityContract::findFirst([
            'conditions' => 'contract_status = :status: and device_id = :device_id: and company_id = :company_id:',
            'bind' => ['status' => self::STATUS_IN_USE,'device_id' =>$device_id,'company_id' => $company_id],
            'order' => 'id asc'
        ]);
    }

    /**
     * @param $id
     * @return EntityContract
     */
    public static function getContractById($id)
    {
        /**
         * @var $admin EntityContract
         */
        $contract = EntityContract::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $contract;
    }

    static public function getMax()
    {
        return EntityContract::maximum(['column' => 'id']);
    }

    public function getLast()
    {
        return EntityContract::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $contract = new EntityContract();
        $contract->setContractNum($data["contract_num"]);

        if (!$contract->save()) {
            throw new InvalidRepositoryException($contract->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $contract = Contract::getContractById($id);
        $contract->setContractNum($data["contract_num"]);

        if (!$contract->save()) {
            throw new InvalidRepositoryException($contract->getMessages()[0]);
        }

        return true;
    }

    public static function deleteContract($id)
    {
        $contract = Contract::getContractById($id);
        if (!$contract) {
            throw new InvalidRepositoryException("合同编号没有找到");
        }

        if (!$contract->delete()) {
            throw new InvalidRepositoryException("合同编号删除失败");
        }

        return true;
    }

    /**
     * @param $service_id
     * @return EntityContract
     */
    public function getServiceContract($service_id)
    {
        $contract = EntityContract::findFirst([
            'conditions' => 'service_id = ?0 and contract_status = ?1',
            'bind' => [$service_id, self::STATUS_IN_USE],
        ]);

        return $contract;
    }

    /**
     * 获取某个时间之后的合同
     * @param null $time
     * @return mixed
     */
    public function getTimeContracts($time = null)
    {
        $builder = $this->modelsManager->createBuilder()
            ->from(['contract' => '\Wdxr\Models\Entities\Contracts'])
            ->join('\Wdxr\Models\Entities\CompanyService', 'contract.service_id = service.id', 'service')
            ->join('\Wdxr\Models\Entities\Companys', 'companys.id = service.company_id', 'companys')
            ->join('\Wdxr\Models\Entities\CompanyInfo', 'company_info.id = companys.info_id', 'company_info')
            ->where("contract.contract_status = ?0 and contract.contract_num IS NOT NULL and service.service_status = ?1", [self::STATUS_IN_USE, CompanyService::SERVICE_ENABLE]);
        if (is_null($time) === false) {
            $builder->andWhere("service.start_time > {$time}");
        }
        $contracts = $builder->columns(
            "distinct(contract.service_id) as service_id, contract.contract_num, companys.name, service.company_id, company_info.legal_name"
        )->orderBy('contract.time desc')
            ->getQuery()
            ->execute();

        return $contracts;
    }


    public static function getContractByNum($contract_num)
    {
        return EntityContract::findFirst([
            'conditions' => 'contract_num = :num:',
            'bind' => ['num' => $contract_num]
        ]);
    }

}