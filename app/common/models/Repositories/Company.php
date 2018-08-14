<?php
namespace Wdxr\Models\Repositories;

use Lcobucci\JWT\JWT;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\CompanyBillInfo;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Entities\UserLogins;
use Wdxr\Models\Repositories\User as RepoUser;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\ApplyService;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Services\Services;
use Wdxr\Models\Services\TimeService;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class Company extends Repositories
{
    //公司状态
    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;
    const STATUS_BLACK_LIST = 3;//黑名单

    //审核状态
    const AUDIT_NOT = 0;//未提交
    const AUDIT_APPLY = 1;//已提交
    const AUDIT_OK = 2;//通过
    const AUDIT_REVOKED = 3;//驳回

    //缴费状态
    const PAYMENT_NOT = 0;//未支付
    const PAYMENT_OK = 1;//支付成功
    const PAYMENT_APPLY = 2;//待核实
    const PAYMENT_CANCEL = 3;//已撤销
    const PAYMENT_FAIL = 4;//未通过
    const PAYMENT_LOAN = 5;//普惠状态

    //公司类型
    const TYPE_NOT = 0;
    const TYPE_COMPANY = 1;
    const TYPE_SELF_EMPLOYED = 2;

    //银行账户类型
    const BANK_TYPE_PUB = 1;
    const BANK_TYPE_PRI = 2;

    const DELETED = 1;
    const DELETE_NO = 0;

    static private $_instance = [];

    static public function getAuditName($audit)
    {
        switch ($audit) {
            case self::AUDIT_NOT:
                return '未提交';
            case self::AUDIT_APPLY:
                return '已提交';
            case self::AUDIT_OK:
                return '已通过';
            case self::AUDIT_REVOKED:
                return '被驳回';
            default:
                return '状态错误';
        }
    }

    /**
     * @param $id
     * @return EntityCompany
     * @throws InvalidRepositoryException
     */
    public static function getCompanyById($id)
    {
        if (isset(self::$_instance[$id]) === false) {
            self::$_instance[$id] = EntityCompany::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
            if (self::$_instance[$id] === false) {
                throw new InvalidRepositoryException("查询的企业不存在");
            }
        }
        return self::$_instance[$id];
    }

    public function getCompanyByAdminIdAndCompanyId($id, $company_id)
    {
        $result = EntityCompany::findFirst([
            'conditions' => 'id = :id: and admin_id = :admin_id:',
            'bind' => ['id' => $company_id , 'admin_id' => $id]
        ]);
        if ($result === false) {
            return '';
        }
        return $result;
    }


    public function getById($id)
    {
        $data = EntityCompany::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if ($data === false) {
            throw new InvalidRepositoryException("企业基本信息获取失败");
        } else {
            return $data;
        }
    }

    public function getByIdNew($id)
    {
        $data = EntityCompany::findFirst(['conditions' => 'id = :id: and status = :status: and auditing = :auditing:', 'bind' => ['id' => $id , 'status' => '1' , 'auditing' => '2']]);
        if($data === false) {
            return false;
        }else{
            return $data;
        }
    }

    public function Byid($id)
    {
        return  EntityCompany::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
    }

    static public function ByInfoId($info_id)
    {
        return  EntityCompany::findFirst(['conditions' => 'info_id = :info_id:', 'bind' => ['info_id' => $info_id]]);
    }

    public function getPaymentCompany()
    {
        return EntityCompany::find(['conditions' => 'payment = :payment: ',
            'bind' => ['payment' => self::PAYMENT_APPLY ],
            'order' => 'time asc'
        ]);
    }

    //获取符合deviceID集合的所有企业
    public function getCompanyByDeviceId($where)
    {
        return EntityCompany::query()
            ->where("$where")
            ->execute();
    }

    //获取时间区间内的所有企业
    public function getCompanyByTime($where,$start,$end)
    {
        return EntityCompany::query()
            ->where("$where")
            ->betweenWhere('time', $start, $end)
            ->orderBy('id asc')
            ->execute();
    }

    public function getByDeviceId($device_id)
    {
        return EntityCompany::query()
            ->where("device_id = $device_id")
            ->columns(['id','name'])
            ->execute();
    }

    /*
     * 获取deviceid下所有为合伙人的企业
     */
    public function getPartnerCompany($device_id)
    {
        return Services::getStaticModelsManager()->createBuilder()
            ->where("company.device_id = $device_id and company.status = 1 and company.auditing = 2")
            ->andWhere("user.is_partner = :is_partner:" , ['is_partner' => 1])
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->leftJoin('Wdxr\Models\Entities\Users','user_id = user.id','user')
            ->columns(['company.id as id','company.name as name'])
            ->getQuery()->execute();
    }

    /*
     * 获取admin_id下的所有企業
     */
    public function getAdminIdCompany($admin_id,$name)
    {
        $result = Services::getStaticModelsManager()->createBuilder()
            ->where("company.admin_id = $admin_id and company.status = 1 and company.auditing = 2")
            ->andWhere($name)
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','company.info_id = info.id','info')
            ->columns(['company.id as id','company.name as name','info.legal_name as legal_name'])
            ->getQuery()->execute();
        if($result == false){
            throw new InvalidRepositoryException("没有任何企业");
        }
        return $result;
    }

    /*
     * 获取deviceID下的所有企业
     */
    public function getCompanyByDevice($device_id, $limit)
    {
        $result = $this->modelsManager->createBuilder()
            ->where('company_service.service_status = '.CompanyService::SERVICE_ENABLE)
            ->andWhere('company.status = ?0 and device_id = ?1 and auditing = ?2', [self::STATUS_ENABLE, $device_id, self::AUDIT_OK])
            ->from(['company_service' => 'Wdxr\Models\Entities\CompanyService'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = company_service.company_id', 'company')
            ->columns(['company.id as id', 'company.name as name', 'company.auditing as auditing', 'company.status', 'company_service.payment_status as payment'])
            ->limit('10', $limit)
            ->orderBy('id asc ')
            ->getQuery()
            ->execute();
        if($result == false){
            throw new InvalidRepositoryException("没有任何企业");
        }
        return $result;
    }

    /*
     * 获取业务员下的所有企业
     */
    public function getCompanyByAdminId($admin_id,$limit)
    {
        $result = EntityCompany::query()
            ->where("admin_id = $admin_id and status = 1 and auditing = 2")
            ->limit('10',$limit)
            ->orderBy('id asc ')
            ->columns('id,name,auditing,status')
            ->execute();
        if($result == false){
            throw new InvalidRepositoryException("没有任何企业");
        }
        return $result;
    }

    /*
     * 获取我的客户
     */
    public function getCustomerByDevice($device_id,$admin_id)
    {
        $result = EntityCompany::query()
            ->where("device_id = $device_id or admin_id = $admin_id")
            ->andWhere("status = 1 and auditing = 2")
            ->orderBy('id asc ')
            ->columns('id,name,auditing,status,time')
            ->execute();
        if($result == false){
            throw new InvalidRepositoryException("没有任何企业");
        }
        return $result;
    }

    public function getCustomerByCompany($company_id)
    {
        $result = EntityCompany::query()
            ->where("recommend_id = $company_id or manager_id = $company_id")
            ->andWhere("status = 1 and auditing = 2")
            ->orderBy('id asc ')
            ->columns('id,name,auditing,status,time')
            ->execute();
        if($result == false){
            throw new InvalidRepositoryException("没有任何企业");
        }
        return $result;
    }

    /*
     * 搜索企业
     */
    public function getSelectCompany($device_id,$where)
    {
        $result = EntityCompany::query()
            ->where("device_id = $device_id and status = 1 and auditing = 2")
            ->andWhere("name like '%".$where."%'")
            ->orderBy('id asc')
            ->columns('id,name,time,auditing')
            ->execute();
        if($result == false){
            throw new InvalidRepositoryException("没有任何企业");
        }
        return $result;
    }

    //获取用户相对应的企业
    public static function getCompanyByUserId($userid)
    {
        return EntityCompany::findFirst(['conditions' => 'user_id = :user_id: ',
            'bind' => ['user_id' => $userid ]
        ]);
    }

    //获取合伙人本人和他下级所有的企业集合
    public function getAllRecommendCompany($id)
    {
        return EntityCompany::query()
            ->where(" status = ".Company::STATUS_ENABLE." and auditing = ".Company::AUDIT_OK)
            ->andWhere(" id = $id ")
            ->orWhere(" recommend_id = $id ")
            ->columns('id,name,info_id')
            ->execute();
    }

    public function getLast()
    {
        return EntityCompany::query()
            ->orderBy('id DESC')
            ->execute();
    }

    static public function getTrueCompany()
    {
        return EntityCompany::find(['conditions' => 'status = :status: ',
            'bind' => ['status' => self::STATUS_ENABLE ]
        ]);
    }

    public function getOkCompany()
    {
        return EntityCompany::find(['conditions' => 'status = :status: and auditing = :auditing:',
            'bind' => ['status' => self::STATUS_ENABLE , 'auditing' => self::AUDIT_OK ]
        ]);
    }

    public function addNew($data)
    {
        $company = new EntityCompany();
        $company->setName($data["name"]);
//        $company->setType($data["type"]);
        $company->setStatus($data["status"]);
//        $company->setAuditing($data["auditing"]);
        $company->setUserId($data["user_id"]);
//        $company->setBillId($data["bill_id"]);
//        $company->setReportId($data["report_id"]);
//        $company->setInfoId($data["info_id"]);
//        $company->setLevelId($data["level_id"]);
//        $company->setPayment($data["payment"]);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $company = Company::getCompanyById($id);
        $company->setName($data["name"]);
//        $company->setType($data["type"]);
        $company->setStatus($data["status"]);
//        $company->setAuditing($data["auditing"]);
        $company->setUserId($data["user_id"]);
//        $company->setBillId($data["bill_id"]);
//        $company->setReportId($data["report_id"]);
//        $company->setInfoId($data["info_id"]);
//        $company->setLevelId($data["level_id"]);
//        $company->setPayment($data["payment"]);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }

        return true;
    }

    static public function deleteCompany($id)
    {
        $company = self::getCompanyById($id);

        if ($company->getStatus() == self::STATUS_ENABLE) {
            throw new InvalidRepositoryException("正常的企业无法删除");
        }
        $service = CompanyService::getCompanyService($id);
        if ($service !== false) {
            throw new InvalidRepositoryException("服务中的企业无法删除");
        }
        //删除缴费信息
        $payments = \Wdxr\Models\Entities\CompanyPayment::find(["company_id = :company_id:", 'bind' => ['company_id' => $id]]);
        //if(is_array($payments)) {
            foreach ($payments as $payment) {
                $payment->delete();
            }
        //}
        //删除审核信息
        $verifies = \Wdxr\Models\Entities\CompanyVerify::find(['company_id = :company_id:', 'bind' => ['company_id' => $id]]);
        //if(is_array($verifies)) {
            foreach ($verifies as $verify) {
                $verify->delete();
            }
        //}
        //删除用户信息
        $users = \Wdxr\Models\Entities\Users::find(['id = :id:', 'bind' => ['id' => $company->getUserId()]]);
        //if(is_array($users)) {
            foreach ($users as $user) {
                $devices = \Wdxr\Models\Entities\UserAdmin::find(['user_id = :user_id: and type = :type:', 'bind' => ['type' => UserAuth::AUTH_TYPE_USER, 'user_id' => $user->getId()]]);
                foreach ($devices as $device) {
                    $device->delete();
                }
                $logs = UserLogins::find(['usersId = :usersId:', 'bind' => ['usersId' => $user->getId()]]);
                if(is_array($logs)) {
                    foreach ($logs as $log) {
                        $log->delete();
                    }
                }
                $user->delete();
            }
        //}
        //删除票据信息
        $bills = \Wdxr\Models\Entities\CompanyBill::find(['company_id = :company_id:', 'bind' => ['company_id' => $id]]);
        //if(is_array($bills)) {
            foreach ($bills as $bill) {
                $bill_infos = CompanyBillInfo::find(['bill_id = :bill_id:', 'bind' => ['bill_id' => $bill->getId()]]);
                foreach ($bill_infos as $bill_info) {
                    $bill_info->delete();
                }
                $bill->delete();
            }
        //}
        //删除票据期限信息
        $bill_terms = \Wdxr\Models\Entities\BillTerm::find(['company_id = :company_id:', 'bind' => ['company_id' => $id]]);
        //if(is_array($bill_terms)) {
            foreach ($bill_terms as $bill_term) {
                $bill_term->delete();
            }
        //}
        //删除征信信息
        $reports = \Wdxr\Models\Entities\CompanyReport::find(['company_id = :company_id:', 'bind' => ['company_id' => $id]]);
        //if(is_array($reports)) {
            foreach ($reports as $report) {
                $report->delete();
            }
        //}
        //删除征信期限信息
        $report_terms = \Wdxr\Models\Entities\ReportTerm::find(['company_id = :company_id:', 'bind' => ['company_id' => $id]]);
        //if(is_array($report_terms)) {
            foreach ($report_terms as $report_term) {
                $report_term->delete();
            }
        //}
        //删除企业证件信息
        $infos = \Wdxr\Models\Entities\CompanyInfo::find(['id = :id:', 'bind' => ['id' => $company->getInfoId()]]);
        //if(is_array($infos)) {
            foreach ($infos as $info) {
                $info->delete();
            }
        //}
        //删除企业关系表
        $recommends = \Wdxr\Models\Entities\CompanyRecommend::find(['recommend_id = :recommend_id: or recommender = :recommender:' , 'bind' => ['recommend_id' => $id,'recommender' => $id]]);
        //if(is_array($recommends)) {
            foreach ($recommends as $recommend) {
                $recommend->delete();
            }
        //}
        //删除企业服务时间信息
        $services = \Wdxr\Models\Entities\CompanyService::find(['company_id = :company_id:', 'bind' => ['company_id' => $id]]);
        //if(is_array($services)) {
            foreach ($services as $service) {
                $service->delete();
            }
        //}
        //删除企业合同信息
        $contracts = \Wdxr\Models\Entities\Contracts::find(['company_id = :company_id:', 'bind' => ['company_id' => $id]]);
        //if(is_array($contracts)) {
            foreach ($contracts as $contract) {
                $contract->delete();
            }
        //}
        //删除合伙人信息
        $statistics = \Wdxr\Models\Entities\Statistics::find(['company_name = :company_name:', 'bind' => ['company_name' => $company->getName()]]);
        //if(is_array($statistics)) {
            foreach ($statistics as $statistic) {
                $statistic->delete();
            }
        //}
        //删除与企业相关业务员业绩信息
        $achievements = \Wdxr\Models\Entities\Achievement::find(['company_name = :company_name:', 'bind' => ['company_name' => $company->getName()]]);
        //if(is_array($achievement)) {
            foreach ($achievements as $achievement) {
                $achievement->delete();
            }
        //}
        //删除企业基本信息
        if (!$company->delete()) {
            throw new InvalidRepositoryException("企业删除失败");
        }

        return true;
    }

    /**
     * 获取某一个业务员的某一个企业
     * @param $name
     * @param $device_id
     * @return EntityCompany
     */
    static public function getDeviceCompanyByName($name, $device_id)
    {
        return EntityCompany::findFirst([
            'conditions' => 'name = :name: and device_id = :device_id:',
            'bind' => ['name' => $name, 'device_id' => $device_id],
            'order' => 'id desc'
        ]);
    }

    static public function getCompanyByIdDevice($id , $device_id)
    {
        return EntityCompany::findFirst([
            'conditions' => 'id = :id: and ( device_id = :device_id: or device_id = :user_id: )',
            'bind' => ['id' => $id , 'device_id' => $device_id , 'user_id' => 0]
        ]);
    }

    /**
     * 根据企业名称获取已缴费企业
     * @param $name
     * @return EntityCompany
     * @throws InvalidRepositoryException
     */
    static public function getPaymentCompanyByName($name)
    {
        $uid = JWT::getUid();
        $company = EntityCompany::findFirst(['conditions' => '(payment = :payment: or payment = :ok_payment:) and name = :name: and device_id = :device_id:',
            'bind' => ['payment' => self::PAYMENT_APPLY, 'ok_payment' => self::PAYMENT_OK, 'name' => $name, 'device_id' => $uid],
            'order' => 'id desc'
        ]);
        if($company === false) {
            throw new InvalidRepositoryException("该企业尚未缴费或上传缴费凭证");
        }
        if($company->getAuditing() == self::AUDIT_APPLY) {
            throw new InvalidRepositoryException('该企业已经提交了申请，请等待审核结果');
        }

        return $company;
    }

    /**
     * 获取已缴费未完成申请企业列表
     * @return array
     */
    static public function getPaymentCompanyList()
    {
        $uid = JWT::getUid();
        $companys = EntityCompany::find(['conditions' => '(payment = :payment_not: or payment = :payment_ok:) and auditing = :auditing: and device_id = :device_id:',
            'bind' => ['payment_not' => self::PAYMENT_APPLY, 'payment_ok' => self::PAYMENT_OK, 'auditing' => self::AUDIT_NOT, 'device_id' => $uid],
            'columns' => ['id', 'name', 'time', 'level_id'],
            'order' => 'time desc',
        ])->toArray();
        $apply = new ApplyService();
        $_company = [];
        foreach ($companys as $key => $company) {
            if($apply->isApplyCompany($company['id']) === false) {
                $company['level'] = Level::getLevelName($company['level_id']);
                $company['time'] = TimeService::humanTime($company['time']);
                $_company[] = $company;
            }
        }
        return $_company;
    }

    /**
     * 获取未完成 申请|缴费 企业列表
     */
    static public function getNotCompanyList($type = null,$where = null,$follow)
    {
        if($type == 'payment'){
            $uid = JWT::getUid();
            $companys =  EntityCompany::query()
                ->where("device_id in (0,$uid)")
                ->andWhere("status <> 3")
                ->andWhere("add_people = $uid or id in ($follow)")
                ->andWhere($where)
                ->orderBy('time desc')
                ->columns(['id', 'name', 'time'])
                ->execute()
                ->toArray();
            $_company = [];
            foreach ($companys as $key => $company) {
                $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($company['id']);
                $loan = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($company['id']);
                if($payment === false && $loan === false){
                    $company['time'] = TimeService::humanTime($company['time']);
                    $_company[] = $company;
                }
            }
            return $_company;
        }else{
            $uid = JWT::getUid();
            $companys =  EntityCompany::query()
                ->where("device_id in (0,$uid)")
                ->andWhere("auditing = 0")
                ->andWhere("add_people = $uid or id in ($follow)")
                ->andWhere($where)
                ->orderBy('time desc')
                ->columns(['id', 'name', 'time'])
                ->execute()
                ->toArray();
            $apply = new ApplyService();
            $_company = [];
            foreach ($companys as $key => $company) {
                if($apply->isApplyCompany($company['id']) === false) {
                    //$company['level'] = Level::getLevelName($company['level_id']);
                    $company['time'] = TimeService::humanTime($company['time']);
                    $_company[] = $company;
                }
            }
            return $_company;
        }
    }

    /**
     * 缴费时添加一个新企业
     * @param $name
     * @param $level_id
     * @param int $type 缴费方式
     * @return int
     * @throws InvalidRepositoryException
     * @internal param $device_id
     */
    static public function payAddNew($id, $level_id, $type, $device_id)
    {
        //$company = self::getDeviceCompanyByName($name, $device_id);
        $company = self::getCompanyByIdDevice($id,$device_id);

        if($company instanceof EntityCompany) {
            //缴费核实失败及撤销缴费时才可以重新申请
            if($company->getPayment() == self::PAYMENT_FAIL || $company->getPayment() == self::PAYMENT_CANCEL) {
                $company->setPayment(self::PAYMENT_APPLY);
                $company->setLevelId($level_id);
            }elseif($company->getPayment() == 0){
                //创建一个新企业及其账号
                $user_id = RepoUser::addDefaultUser($type, $level_id);
                $company->setUserId($user_id);
                $company->setLevelId($level_id);
                $company->setStatus(self::STATUS_DISABLE);
                $company->setPayment(self::PAYMENT_APPLY);
                $company->setDeviceId($device_id);
                $company->setAdminId(UserAuth::getAdminId($device_id));
                $company->setPartnerId(UserAuth::getPartnerId($device_id));
            } else {
                throw new InvalidRepositoryException('该公司已经存在');
            }
        } else {
            //创建一个新企业及其账号
            $user_id = RepoUser::addDefaultUser($type, $level_id);
            $company = new EntityCompany();
            $company->setUserId($user_id);
            $company->setLevelId($level_id);
            $company->setAuditing(self::AUDIT_NOT);
            $company->setStatus(self::STATUS_DISABLE);
            $company->setPayment(self::PAYMENT_APPLY);
            $company->setDeviceId($device_id);
            $company->setAdminId(UserAuth::getAdminId($device_id));
            $company->setPartnerId(UserAuth::getPartnerId($device_id));
        }

        if(!$company->save()) {
            foreach ($company->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加新公司失败');
        }
        return $company->getId();
    }

    static public function paymentCompany($id,$device_id)
    {
        $company = self::getCompanyByIdDevice($id,$device_id);
        if($company === false){
            throw new InvalidRepositoryException('当前企业申请业务员不匹配');
        }
        $company = self::getCompanyByIdDevice($id, $device_id);
        if($company === false) {
            throw new InvalidRepositoryException("未找到缴费的企业或者该企业不是你的客户");
        }
        $company->setStatus(self::STATUS_DISABLE);
        $company->setDeviceId($device_id);
        $company->setAdminId(UserAuth::getAdminId($device_id));
        $company->setPartnerId(UserAuth::getPartnerId($device_id));
        if(!$company->save()) {
            foreach ($company->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('保存企业信息失败');
        }
        return $company->getId();
    }

    /**
     * 更新企业缴费ID
     * @param $company_id
     * @param $payment_id
     * @return bool
     * @throws InvalidRepositoryException
     */
    static public function updateCompanyPaymentId($company_id, $payment_id)
    {
        $company = self::getCompanyById($company_id);
        $company->setPaymentId($payment_id);

        if(!$company->save()) {
            throw new InvalidRepositoryException("企业缴费信息更新失败");
        }
        return true;
    }

    /**
     * 更新企业缴费ID并绑定device_id
     * @param $company_id
     * @param $payment_id
     * @return bool
     * @throws InvalidRepositoryException
     */
    static public function updateCompanyPayment($company_id,$device_id)
    {
        $company = self::getCompanyByIdDevice($company_id,$device_id);
        if($company === false){
            throw new InvalidRepositoryException('当前企业申请业务员不匹配');
        }
        $company->setStatus(self::STATUS_DISABLE);
        $company->setDeviceId($device_id);
        $company->setAdminId(UserAuth::getAdminId($device_id));
        $company->setPartnerId(UserAuth::getPartnerId($device_id));
        if(!$company->save()) {
            foreach ($company->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加新公司失败');
        }

        return $company->getId();
    }

    /**
     * 更新企业缴费ID并绑定device_id
     * @param $company_id
     * @param $payment_id
     * @return bool
     * @throws InvalidRepositoryException
     */
    static public function updateCompanyPaymentInfo($company_id,$device_id,$data)
    {
        $company = self::getCompanyByIdDevice($company_id,$device_id);
        if($company === false){
            throw new InvalidRepositoryException('当前企业申请业务员不匹配');
        }
        $companyInfo = CompanyInfo::getCompanyInfoById($company->getInfoId());
        $master_data['bankcard_photo']=$data['bankcard_photo'];
        $master_data['bank_type']=$data['bank_type'];
        $master_data['number']=$data['bankcard'];
        $master_data['bank']=$data['bank'];
        $master_data['province']=$data['bank_province'];
        $master_data['city']=$data['bank_city'];
        $master_data['address']=$data['bank_name'];
        $master_data['account']=$companyInfo->getLegalName();
        CompanyBank::saveCompanyBank($company_id,$master_data,CompanyBank::CATEGORY_MASTER);
        if(!empty($data['work_bankcard']))
        {
            $work_data['bankcard_photo']=$data['work_photo'];
            $work_data['bank_type']=CompanyBank::TYPE_PRIVATE;
            $work_data['number']=$data['work_bankcard'];
            $work_data['bank']=$data['work_bank'];
            $work_data['province']=$data['work_bank_province'];
            $work_data['city']=$data['work_bank_city'];
            $work_data['address']=$data['work_bank_name'];
            $work_data['account']=$companyInfo->getLegalName();
            CompanyBank::saveCompanyBank($company_id,$work_data,CompanyBank::CATEGORY_WORK);
        }
        $company->setStatus(self::STATUS_DISABLE);
        $company->setDeviceId($device_id);
        $company->setAdminId(UserAuth::getAdminId($device_id));
        $company->setPartnerId(UserAuth::getPartnerId($device_id));
        if(!$company->save()) {
            foreach ($company->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加新公司失败');
        }
        return $company->getId();
    }



    //获取客户数量
    public function getCount()
    {
        return EntityCompany::count(array(
            "conditions" => "auditing = 2 and status = 1"
        ));
    }

    static public function getCountByCompanyId($id)
    {
        return EntityCompany::count(array(
            "conditions" => "auditing = 2 and status = 1 and recommend_id = $id"
        ));
    }

    //获取未审核企业的数量
    public function UnAuditing()
    {
        return EntityCompany::count(array(
            "conditions" => "auditing = ".self::AUDIT_APPLY
        ));
    }

    //获取审核被驳回的企业数量
    public function getRevoked()
    {
        return EntityCompany::count(array(
            "conditions" => "auditing = ".self::AUDIT_REVOKED
        ));
    }
    //获取已经审核通过的
    public function getOk()
    {
        return EntityCompany::count(array(
            "conditions" => "auditing = ".self::AUDIT_OK
        ));
    }

    static public function getRecommendCompany()
    {
        $recommend_company = EntityCompany::query()
            ->where("recommend_id IS NOT NULL")
            ->andWhere("auditing = :auditing:", ['auditing' => self::AUDIT_OK])
            ->andWhere("status = :status:", ['status' => self::STATUS_ENABLE])
//            ->andWhere("payment = :payment:", ['payment' => self::PAYMENT_OK])
            ->groupBy('recommend_id')
            ->columns(['count(recommend_id) as count, recommend_id as company_id'])
            ->innerJoin('Wdxr\Models\Entities\CompanyService', 'service.company_id = Wdxr\Models\Entities\Companys.id and service.service_status = '.CompanyService::SERVICE_ENABLE, 'service')
            ->execute();

        return $recommend_company;
    }

    /**
     * 获取一个企业推荐的企业列表，需要按生效时间排序
     * @param $company_id
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    static public function getRecommend($company_id)
    {
        $recommender = EntityCompany::query()
            ->where('Wdxr\Models\Entities\Companys.recommend_id = ?0', [$company_id])
            ->andWhere("Wdxr\Models\Entities\Companys.auditing = :auditing:", ['auditing' => self::AUDIT_OK])
            ->andWhere("Wdxr\Models\Entities\Companys.status = :status:", ['status' => self::STATUS_ENABLE])
            ->andWhere("Wdxr\Models\Entities\Companys.payment = :payment:", ['payment' => self::PAYMENT_OK])
            ->columns(['DISTINCT(Wdxr\Models\Entities\Companys.id) as id, payment.type'])
            ->innerJoin('Wdxr\Models\Entities\CompanyRecommend', 'recommend.recommend_id = Wdxr\Models\Entities\Companys.id and recommend.recommend_id = Wdxr\Models\Entities\Companys.id', 'recommend')
            ->innerJoin('Wdxr\Models\Entities\CompanyPayment', 'payment.id = Wdxr\Models\Entities\Companys.payment_id', 'payment')
            ->innerJoin('Wdxr\Models\Entities\CompanyService', 'service.company_id = Wdxr\Models\Entities\Companys.id', 'service')
            ->orderBy('service.start_time asc')
            ->execute();

        return $recommender;
    }

    static public function getRecommendTask($company_id)
    {
        $recommender = EntityCompany::query()
            ->where('Wdxr\Models\Entities\Companys.recommend_id = ?0', [$company_id])
            ->andWhere("Wdxr\Models\Entities\Companys.auditing = :auditing:", ['auditing' => self::AUDIT_OK])
            ->andWhere("Wdxr\Models\Entities\Companys.status = :status:", ['status' => self::STATUS_ENABLE])
//            ->andWhere("Wdxr\Models\Entities\Companys.payment = :payment:", ['payment' => self::PAYMENT_OK])
            ->columns(['DISTINCT(Wdxr\Models\Entities\Companys.id) as id, payment.type'])
            ->innerJoin('Wdxr\Models\Entities\CompanyService', 'service.company_id = Wdxr\Models\Entities\Companys.id and service.service_status = '.CompanyService::SERVICE_ENABLE, 'service')
            ->innerJoin('Wdxr\Models\Entities\CompanyRecommend', 'recommend.recommend_id = Wdxr\Models\Entities\Companys.id', 'recommend')
            ->innerJoin('Wdxr\Models\Entities\CompanyPayment', 'payment.service_id = service.id and payment.status = '.CompanyPayment::STATUS_OK.' and payment.type <> '.CompanyPayment::TYPE_REFUND, 'payment')
            ->orderBy('service.start_time asc')
            ->execute();

        return $recommender;
    }

    /**
     * 获取一个企业的推荐奖金
     * @param $company_id
     * @return int
     */
    static public function getRecommendMoney($company_id)
    {
        $money = 0;
//        $recommends = Company::getRecommendTask($company_id);
        /**
         * @var $company_recommend \Wdxr\Models\Repositories\CompanyRecommend
         */
        $company_recommend = Repositories::getRepository('CompanyRecommend');
        $recommends = $company_recommend->getRecommendId($company_id);

        for($i = 0; $i < 12 && isset($recommends[$i]); $i++) {
            try {
                $company_data = (new Company())->getById($recommends[$i]['id']);
                //新客户不再有推荐管理关系dh20180601修改
                $service = CompanyService::getCompanyService($company_data->getId());
                if($service === false){
                    continue;
                }
                if($service->getStartTime() > 1527609600){
                    continue;
                }
                $MainTask = \Wdxr\Models\Services\Company::getCompanyBenefitsForMainTask($company_data);
                if($MainTask['status'] != 1){
                    continue;
                }
            } catch (InvalidServiceException $exception) {
                Services::getStaticDi()->get('logger')->error($exception->getMessage().'--'.$recommends[$i]['id'].'--Recommend');
                continue;
            } catch (InvalidRepositoryException $e){
                Services::getStaticDi()->get('logger')->error($e->getMessage().'--'.$recommends[$i]['id'].'--Recommend');
                continue;
            }
            if($recommends[$i]['type_id'] == 2) {
                $money += 5;
            } else {
                $money += 10;
            }
        }
        return $money;
    }

    static public function getManageCompany()
    {
        $recommend_company = EntityCompany::query()
            ->where("manager_id IS NOT NULL")
            ->andWhere("auditing = :auditing:", ['auditing' => self::AUDIT_OK])
            ->andWhere("status = :status:", ['status' => self::STATUS_ENABLE])
//            ->andWhere("payment = :payment:", ['payment' => self::PAYMENT_OK])
            ->groupBy('manager_id')
            ->columns(['count(manager_id) as count, manager_id as company_id'])
            ->innerJoin('Wdxr\Models\Entities\CompanyService', 'service.company_id = Wdxr\Models\Entities\Companys.id and service.payment_status = '.self::PAYMENT_OK, 'service')
            ->execute();

        return $recommend_company;
    }

    /**
     * 获取所有的被管理人
     * @param $company_id
     * @return array
     */
    static public function getValidManaged($company_id)
    {
        $valid_manages = [];
        $recommends = Company::getRecommend($company_id);
        foreach ($recommends as $recommend) {
            $manageds = Company::getRecommend($recommend['id']);
            foreach ($manageds as $managed) {
                array_push($valid_manages, $managed);
            }
        }
        return $valid_manages;
    }

    static public function getManageMoney($company_id)
    {
        $money = 0;
        $payment = CompanyPayment::getPaymentByCompanyId($company_id, CompanyPayment::STATUS_OK);
        $recommends = Company::getRecommendTask($company_id);
        for($i = 0;  $i < 12 && isset($recommends[$i]); $i++) {
            try {
                $recommends_company_data = (new Company())->getById($recommends[$i]['id']);
                //新客户不再有推荐管理关系dh20180601修改
                $service = CompanyService::getCompanyService($recommends_company_data->getId());
                if($service === false){
                    continue;
                }
                if($service->getStartTime() > 1527609600){
                    continue;
                }
                $MainTask = \Wdxr\Models\Services\Company::getCompanyBenefitsForMainTask($recommends_company_data);
                if($MainTask['status'] != 1){
                    continue;
                }
            } catch (InvalidServiceException $exception) {
                Services::getStaticDi()->get('logger')->error($exception->getMessage().'--'.$recommends[$i]['id'].'--Manage');
                continue;
            } catch (InvalidRepositoryException $e){
                Services::getStaticDi()->get('logger')->error($e->getMessage().'--'.$recommends[$i]['id'].'--Manage');
                continue;
            }
            $manageds = Company::getRecommendTask($recommends[$i]['id']);
            for($j = 0; $j < 12 && isset($manageds[$j]); $j++) {
                try {
                    $manageds_company_data = (new Company())->getById($manageds[$j]['id']);
                    //新客户不再有推荐管理关系dh20180601修改
                    $service = CompanyService::getCompanyService($manageds_company_data->getId());
                    if($service === false){
                        continue;
                    }
                    if($service->getStartTime() > 1527609600){
                        continue;
                    }
                    //dh20180127修改 判断管理企业manageID是否为空
                    if($manageds_company_data->getManagerId() != $company_id){
                        continue;
                    }
                    $MainTask = \Wdxr\Models\Services\Company::getCompanyBenefitsForMainTask($manageds_company_data);
                    if($MainTask['status'] != 1){
                        continue;
                    }
                } catch (InvalidServiceException $exception) {
                    Services::getStaticDi()->get('logger')->error($exception->getMessage().'--'.$manageds[$j]['id'].'--Manage');
                    continue;
                } catch (InvalidRepositoryException $e){
                    Services::getStaticDi()->get('logger')->error($e->getMessage().'--'.$manageds[$j]['id'].'--Manage');
                    continue;
                }
                $is_loan = $payment->getType() == \Wdxr\Models\Repositories\CompanyPayment::TYPE_LOAN;
                $is_loan = $is_loan ?: $recommends[$i]['type'] == \Wdxr\Models\Repositories\CompanyPayment::TYPE_LOAN;
                $is_loan = $is_loan ?: $manageds[$j]['type'] == \Wdxr\Models\Repositories\CompanyPayment::TYPE_LOAN;
                $money += ($is_loan ? 1.5 : 3);
            }
        }
        return $money;
    }

    public function getAdminCompany($admin_id)
    {
        return $this->modelsManager->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->join('Wdxr\Models\Entities\CompanyService', "company.id = company_service.company_id", 'company_service')
            ->where('company_service.service_status = :status: and company.admin_id = :admin_id:', ['status' => CompanyService::SERVICE_ENABLE, 'admin_id' => $admin_id])
            ->getQuery()
            ->execute();
    }

    /**
     * 潜在客户列表
     * @param int $numberPage
     * @return PaginatorQueryBuilder
     */
    public function getNewCompanyList($search = null, $numberPage = 1)
    {
        $builder = $this->modelsManager->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->leftJoin('Wdxr\Models\Entities\CompanyService', "company.id = company_service.company_id", 'company_service')
            ->leftJoin('Wdxr\Models\Entities\Admins', 'admin.id = company.admin_id', 'admin')
            ->join('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
            ->where('company_service.id IS NULL and company.auditing = ?0', [self::AUDIT_NOT])
            ->columns(['company.id as id', 'company.name as name', 'company_info.licence_num', 'company_info.legal_name as legal_name', 'company.time', 'admin.name as admin_name', 'company_info.address', 'company_info.province', 'company_info.city', 'company_info.district']);

        if($search) {
            $builder->andWhere("company.name like '%".$search."%' or
                    company_info.legal_name like '%".$search."%' or
                    company_info.licence_num like '%".$search."%' or
                    company_info.contact_phone like '%".$search."%'");
        }

        $builder->orderBy('company.time desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 20,
            'page' => $numberPage
        ]);
    }

    /**
     * 获取企业列表(不区分服务状态)
     * @param null $search
     * @return \Phalcon\Mvc\Model\Query\Builder
     */
    public function getCompanyList($search = null)
    {
        $builder = $this->modelsManager->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->join('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
            ->leftJoin('Wdxr\Models\Entities\Admins', 'admin.id = company.admin_id', 'admin')
            ->where('')
            ->columns(['company.id as id', 'company.name as name', 'company_info.type', 'company_info.legal_name as legal_name', 'company.time', 'admin.name as admin_name', 'company_info.scope', 'company_info.licence_num', 'company_info.address', 'company_info.province', 'company_info.city', 'company_info.district', 'company.status']);

        if($search) {
            $builder->andWhere("company.name like '%".$search."%' or
                    company_info.legal_name like '%".$search."%' or
                    company_info.licence_num like '%".$search."%' or
                    company_info.contact_phone like '%".$search."%' or
                    admin.name like '%".$search."%'");
        }

        return $builder->orderBy('company.time desc');
    }

    public static function enableCompany($company_id)
    {
        $company = Company::getCompanyById($company_id);
        if ($company->getAuditing() != Company::AUDIT_OK) {
            throw new InvalidRepositoryException('企业工商信息未通过审核');
        }
        $company->setStatus(Company::STATUS_ENABLE);
        if ($company->save() === false) {
            throw new InvalidRepositoryException("保存企业信息失败");
        }
        return true;
    }

}