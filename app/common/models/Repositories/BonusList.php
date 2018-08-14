<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\BonusList as EntityBonusList;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class BonusList extends Repositories
{

    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;

    /**
     * @param $id
     * @return EntityBonusList
     */
    static public function getAchievementById($id)
    {
        /**
         * @var $admin EntityBonusList
         */
        $Achievement = EntityBonusList::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Achievement;
    }


    public function getAchievementByBranchId($id)
    {
        $Achievement = EntityBonusList::find(['conditions' => 'branch_id = :branch_id:', 'bind' => ['branch_id' => $id]]);
        return $Achievement;
    }

    /**
     * @param $branch_id
     * @return EntityBonusList
     */
    static public function getMoneyByBranchId($branch_id)
    {
        /**
         * @var $admin EntityBonusList
         */
        return EntityBonusList::findFirst(array(
            "columns"     => "sum(money) as amount",
            "conditions" => "status = 0 and branch_id = $branch_id",
        ));

    }

    /**
     * @param $admin_id
     * @return EntityBonusList
     */
    static public function getMoneyByAdminId($admin_id)
    {
        /**
         * @var $admin EntityBonusList
         */
        return EntityBonusList::findFirst(array(
            "columns"     => "sum(money) as amount",
            "conditions" => "status = 0 and admin_id = $admin_id",
        ));

    }

    static public function findFirstByName($name)
    {
        $Achievement = EntityBonusList::findFirst(['conditions' => 'admin_name = :name:', 'bind' => ['name' => $name]]);
        return $Achievement;
    }

    public function getLast()
    {
        return EntityBonusList::query()
            ->orderBy('id DESC')
            ->execute();
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidRepositoryException
     */
    public function addNew($data)
    {
        $Achievement = new EntityBonusList();
        $Achievement->setAdminName($data["admin_name"]);
        $Achievement->setTime($data["time"]);
        $Achievement->setContractNum($data["contract_num"]);
        $Achievement->setBranchId($data["branch_id"]);
        $Achievement->setMoney($data["money"]);
        $Achievement->setCompanyName($data["company_name"]);
        $Achievement->setRecommender($data["recommender"]);
        $Achievement->setCommission($data["commission"]);
        $Achievement->setAdminId($data["admin_id"]);
        $Achievement->setBonus($data['bonus']);
        $Achievement->setCompanyId($data['company_id']);
        if (!$Achievement->save()) {
            throw new InvalidRepositoryException($Achievement->getMessages()[0]);
        }
        return true;
    }

    public function getExportBetweenList($start_time,$end_time,$branch_id = null)
    {
        return EntityBonusList::query()
            ->where("$branch_id")
            ->betweenWhere('time', $start_time, $end_time)
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender','bonus','money','commission','time','company_id'])
            ->execute();
    }

    public function getExportBetweenListBybranch($start_time,$end_time,$branch_id)
    {
        return EntityBonusList::query()
            ->where("branch_id = $branch_id")
            ->betweenWhere('time', $start_time, $end_time)
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender','bonus','money','commission','time','company_id'])
            ->execute();
    }

    public function getExportList($branch_id = null)
    {
        return EntityBonusList::query()
            ->where("$branch_id")
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender','bonus','money','commission','time','company_id'])
            ->execute();
    }

    public function getMoneyList($branch_id = null)
    {
        return EntityBonusList::query()
            ->where("branch_id = $branch_id")
            ->orderBy('id asc')
            ->columns(['money'])
            ->execute()
            ->toArray();
    }

    public function getExportListBybranch($branch_id)
    {
        return EntityBonusList::query()
            ->where("branch_id = $branch_id")
            ->orderBy('id asc')
            ->columns(['id','admin_name', 'company_name', 'recommender','bonus','money','commission','time','company_id'])
            ->execute();
    }

    //获取分公司的总业绩
    static public function getBranchAmount($branch_id)
    {
        return EntityBonusList::sum(array(
            "column"     => "money",
            "conditions" => "branch_id = '{$branch_id}'"
        ));

    }

    //获取分公司的本月业绩
    static public function getBranchMonthAmount($branch_id)
    {
        $start_time=mktime(0,0,0,date('m'),1,date('Y'));
        $end_time=mktime(23,59,59,date('m'),date('t'),date('Y'));

        return EntityBonusList::sum(array(
            "column"     => "money",
            "conditions" => "branch_id = '{$branch_id}' and (time between $start_time and $end_time)"
        ));
    }

    //获取指定时间的业绩统计
    public function getAmount($start_time,$end_time)
    {
        return EntityBonusList::sum(array(
            "column"     => "money",
            "conditions" => "time between $start_time and $end_time"
        ));
    }

    /**
     * 客户数量统计
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function getCount($start_time, $end_time)
    {
        return EntityBonusList::count(array(
            "column"     => "money",
            "conditions" => "time between $start_time and $end_time"
        ));
    }

    public function getBranchAdminAchievement($branch_id)
    {
        return EntityBonusList::query()
            ->where("branch_id = $branch_id")
            ->groupBy("admin_name")
            ->columns(['admin_name','sum(money) as money',"sum(commission) as commission"])
            ->execute();
    }

    public function getBranchAdminAchievementMonth($branch_id)
    {
        $start_time=mktime(0,0,0,date('m'),1,date('Y'));
        $end_time=mktime(23,59,59,date('m'),date('t'),date('Y'));

        return EntityBonusList::query()
            ->where("branch_id = $branch_id")
            ->betweenWhere('time', $start_time, $end_time)
            ->groupBy("admin_name")
            ->columns(['admin_name','sum(money) as month_money',"sum(commission) as month_commission"])
            ->execute();
    }

    /**
     * 业务员总业绩
     * @param $admin_id
     * @return mixed
     */
    public function getAdminAmount($admin_id)
    {
        $sum = EntityBonusList::sum(array(
            "column"     => "money",
            "conditions" => "admin_id = ?0",
            'bind' => [$admin_id]
        )) ? : 0;

        return $sum;
    }

    public function stopAchievementByServiceId($service_id)
    {
        $achievements = EntityBonusList::find([
            'conditions' => 'service_id = ?0',
            'bind' => [$service_id]
        ]);

        return $achievements->update([
            'status' => self::STATUS_DISABLE
        ]);
    }

    /**
     * 业务员/推荐人绩效
     * @param $payment_id
     * @param $contract_id
     * @return bool
     * @throws InvalidRepositoryException
     */
    public static function newAchievement($payment_id, $contract_id, $service_id)
    {
        //企业信息
        $company_payment = CompanyPayment::getPaymentById($payment_id);
        $company_id = $company_payment->getCompanyId();
        $company = Company::getCompanyById($company_id);
        //业绩
        $contract = Contract::getContractById($contract_id);
        if ($contract === false) {
            throw new InvalidRepositoryException('获取合同信息失败');
        }

        $bonus = new EntityBonusList();
        $bonus->setContractNum($contract->getContractNum());
        $admin_data = Admin::getAdminById($company->getAdminId());
        $bonus->setAdminName($admin_data->getName());
        $bonus->setAdminId($admin_data->getId());
        $bonus->setBranchId((new Salesman())->getAdminBranchId($admin_data->getId()));
        $bonus->setMoney($company_payment->getAmount());
        $bonus->setTime(time());
        $bonus->setCompanyName($company->getName());
        $bonus->setCompanyId($company_id);
        $bonus->setServiceId($service_id);

        //推荐人
        if ($company->getRecommendId() &&
            ($service = CompanyService::getCompanyService($company->getRecommendId()))) {
            //如果有推荐企业,查看企业的缴费类型
            $recommend_payment = CompanyPayment::getCompanyPaymentByServiceId(
                $service->getId(),
                CompanyPayment::STATUS_OK
            );

            //推荐企业的公司信息
            $recommender = Company::getCompanyById($company->getRecommendId());
            $bonus->setRecommenderId($recommender->getId());

            //推荐人类别
            if ($recommend_payment->getType() != CompanyPayment::TYPE_LOAN) {
                $bonus->setRecommender($recommender->getName()."(合伙人)");
                $recommend_type = BonusSystem::SHIYE;
            } else {
                $bonus->setRecommender($recommender->getName()."(普惠)");
                $recommend_type = BonusSystem::PUHUI;
            }
            /**
             * 当期推荐客户数量
             * @var $company_recommend CompanyRecommend
             */
            $company_recommend = Repositories::getRepository('CompanyRecommend');
            $recommend_count = count($company_recommend->getValidRecommend($company->getRecommendId()));
            //新客户类别
            $customer_type = $company_payment->getType() != CompanyPayment::TYPE_LOAN ? BonusSystem::SHIYE : BonusSystem::PUHUI;
            $amount = BonusSystem::getBonusByType($recommend_type, $customer_type, $recommend_count);
            $bonus->setBonus($amount);
        } else {
            $bonus->setBonus(0);
            $commission = (new CommissionList())->getRatio(
                $company_payment->getType(),
                $company_payment->getAmount(),
                $company->getAdminId(),
                UserAdmin::TYPE_ADMIN
            );
            $bonus->setCommission($commission);
        }
        return $bonus->save();
    }
}