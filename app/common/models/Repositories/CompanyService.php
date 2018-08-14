<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CompanyService as EntityCompanyService;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\CompanyPayment as RepoCompanyPayment;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;

class CompanyService extends Repositories
{
    const SERVICE_UN_ACTIVE = 0;
    const SERVICE_ENABLE = 1;
    const SERVICE_DISABLE = 2;
    const SERVICE_REFUND = 3;
    const SERVICE_EXPIRE = 4;

    const TYPE_PARTNER = 1;
    const TYPE_ORDINARY = 2;

    const BILL_STATUS_NOT = 0;
    const BILL_STATUS_OK = 1;
    const BILL_STATUS_FAILED = 2;

    const REPORT_STATUS_NOT = 0;
    const REPORT_STATUS_OK = 1;
    const REPORT_STATUS_FAILED = 2;

    static private $_instance = null;

    /**
     * 获取服务状态名称
     * @param $status
     * @return bool|string
     */
    public static function getStatusName($status)
    {
        switch ($status) {
            case self::SERVICE_UN_ACTIVE:
                return '未生效';
            case self::SERVICE_ENABLE:
                return '正常';
            case self::SERVICE_DISABLE:
                return '已失效';
            case self::SERVICE_REFUND:
                return '已退费';
            case self::SERVICE_EXPIRE:
                return '已过期';
        }
        return '状态错误';
    }

    /**
     * 根据ID获取服务
     * @param $id
     * @param null $status
     * @return EntityCompanyService
     * @throws InvalidRepositoryException
     */
    public function getService($id, $status = null)
    {
        $status = is_null($status) ? '' : " and status = {$status}";
        $service = EntityCompanyService::findFirst([
            'conditions' => 'id = :id:'.$status,
            'bind' => ['id' => $id]
        ]);
        if ($service === false) {
            throw new InvalidRepositoryException("服务获取失败");
        }
        return $service;
    }

    /**
     * 获取指定公司的服务状态，一个企业同时只能有一个服务处于生效状态
     * @param $company_id
     * @return bool|EntityCompanyService
     * @throws InvalidRepositoryException
     */
    public static function getCompanyService($company_id)
    {
        if (isset(self::$_instance[$company_id]) === false) {
            self::$_instance[$company_id] = EntityCompanyService::findFirst([
                'conditions' => 'company_id = :company_id: and service_status = :status:',
                'bind' => ['company_id' => $company_id, 'status' => self::SERVICE_ENABLE],
                'order' => 'createAt asc, id asc'
            ]);
            if (self::$_instance[$company_id] === false) {
                return false;
            }
        }
        return self::$_instance[$company_id];
    }

    public function getCompanyServiceById($company_id)
    {
        $result = EntityCompanyService::findFirst([
            'conditions' => 'company_id = :company_id:',
                'bind' => ['company_id' => $company_id]
        ]);
        return $result;
    }
    /**
     * 一个企业同时只能有一个服务处于生效状态
     * @param $id
     * @return EntityCompanyService
     */
    public function getCompanyServiceByCompanyId($id)
    {
        return EntityCompanyService::findFirst([
            'conditions' => 'company_id = :company_id: and service_status = :service_status:',
            'bind' => ['company_id' => $id, 'service_status' => self::SERVICE_ENABLE],
            'order' => 'createAt asc, id asc'
        ]);
    }

    /**
     * 查看服务中的企业列表
     * @param array $search
     * @return \Phalcon\Mvc\Model\Query\BuilderInterface
     */
    public function getServiceCompany($search = [])
    {
        $builder = $this->modelsManager->createBuilder()->where('service_status = '.self::SERVICE_ENABLE)
            ->from(['company_service' => 'Wdxr\Models\Entities\CompanyService'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = company_service.company_id', 'company')
            ->join('Wdxr\Models\Entities\Levels', 'level.id = company_service.level_id', 'level')
            ->join('Wdxr\Models\Entities\CompanyInfo', 'company_info.id = company.info_id', 'company_info')
            ->join('Wdxr\Models\Entities\Users', 'user.id = company.user_id', 'user')
            ->join('Wdxr\Models\Entities\Admins', 'admin.id = company.admin_id', 'admin')
            ->join(
                'Wdxr\Models\Entities\Contracts',
                'company_service.id = contracts.service_id and contracts.contract_status = 1',
                'contracts'
            )->columns([
                'company.id as id', 'company_service.id as service_id', 'company.name as name', 'level.level_money', 'level.day_amount',
                'company_info.legal_name as legal_name', 'level.level_name as level_name', 'company_info.contact_phone',
                'user.is_partner as is_partner', 'company_service.bill_status as bill_status', 'company.device_id',
                'company_service.report_status as report_status','admin.name as admin_name', 'company_info.licence_num',
                //标注5月30日（5月31日生效）签订的客户
                'if(company_service.start_time > 1527696000 - 1, 1, 0) as is_new', 'company_service.start_time',
                'company_info.province', 'company_info.city', 'company_info.district'
            ]);

        if (isset($search['city']) && $search['city']) {
            $builder->andWhere('company_info.city = :city:', ['city' => $search['city']]);
        }
        if (isset($search['time']) && $search['time']) {
            list($start_time, $end_time) = explode(' - ', $search['time']);
            $builder->betweenWhere('company_service.start_time', strtotime($start_time), strtotime($end_time));
        }
        if (isset($search['level']) && $search['level']) {
            $builder->andWhere("company_service.level_id = :level_id:", ['level_id' => $search['level']]);
        }
        if (isset($search['type']) && $search['type']) {
            $builder->andWhere("company_service.type = ?0", [$search['type']]);
        }
        if (isset($search['name']) && $search['name']) {
            $builder->andWhere("company.name like '%".$search['name']."%' or
                    company_info.legal_name like '%".$search['name']."%' or
                    company_info.licence_num like '%".$search['name']."%' or
                    company_info.contact_phone like '%".$search['name']."%' or
                    contracts.contract_num like '%".$search['name']."%' or
                    admin.name like '%".$search['name']."%' or
                    user.number like '%".$search['name']."%' ");
        }

        //todo
        $builder->orderBy('id desc');

        return $builder;
    }

    /**
     * 获取服务的开始时间与结束时间
     * @param $company_id
     * @param null $payment_type
     * @return array
     */
    public static function getCompanyServiceTime($company_id, $payment_type = null)
    {
        if (is_null($payment_type)) {
            $payment = RepoCompanyPayment::getPaymentByCompanyId($company_id, RepoCompanyPayment::STATUS_OK);
            $payment_type = $payment->getType();
        }
        if ($payment_type == \Wdxr\Models\Repositories\CompanyPayment::TYPE_LOAN) {
            $start_time = '';
            $end_time = '';
        } else {
            $service = self::getCompanyService($company_id);
            $start_time = $service->getStartTime();
            $end_time = $service->getEndTime();
        }
        return [$start_time, $end_time];
    }

    /**
     * 指定公司是否在服务期内
     * @param $company_id
     * @return bool|\Wdxr\Models\Entities\CompanyService
     */
    public static function isInService($company_id)
    {
        $service = CompanyService::getCompanyService($company_id);
        if ($service instanceof EntityCompanyService &&
            ($service->getEndTime() > time() && time() > $service->getStartTime())) {
            return $service;
        }
        return false;
    }

    /**
     * 指定公司的服务天数
     * @param $company_id
     * @return bool|float|int
     */
    public static function getServiceDays($company_id)
    {
        if ($service = self::isInService($company_id)) {
            $time = strtotime(date("Ymd", $service->getStartTime()));
            return floor((time() - $time) / 86400);
        }
        return false;
    }

    /**
     * 添加新的服务记录
     * @param $company_id
     * @param $payment_id
     * @param $type
     * @return EntityCompanyService
     * @throws InvalidRepositoryException
     */
    public function addService($company_id, $payment_id, $type)
    {
        $payment = CompanyPayment::getPaymentById($payment_id);
        if ($payment === false) {
            throw new InvalidRepositoryException('获取缴费信息失败');
        }

        if ($type == CompanyService::TYPE_PARTNER) {
            $status = CompanyService::SERVICE_ENABLE;
            $payment_status = CompanyPayment::STATUS_OK;
            $start = strtotime(date('Y-m-d', strtotime('+1 day')));
            $end = strtotime('+365 days', $start) - 1;
        } elseif ($type == CompanyService::TYPE_ORDINARY) {
            $status = CompanyService::SERVICE_UN_ACTIVE;
            $payment_status = $payment->getStatus();
            $start = $end = null;
        } else {
            throw new InvalidRepositoryException('服务类别参数错误');
        }

        $service = new EntityCompanyService();
        $service->setCompanyId($company_id);
        $service->setStartTime($start);
        $service->setEndTime($end);
        $service->setPaymentStatus($payment_status);
        $service->setLevelId($payment->getLevelId());
        $service->setType($type);
        $service->setBillId(RepoCompanyBill::addCompanyBill($company_id));
        $service->setBillStatus(RepoCompanyBill::STATUS_DISABLE);
        $service->setReportStatus(CompanyReport::STATUS_DISABLE);
        $service->setServiceStatus($status);
        $service->setCreateAt(date('Y-m-m H:i:s', time()));
        if ($service->save() === false) {
            $message = isset($service->getMessages()[0]) ? $service->getMessages()[0] : '服务记录保存失败';
            throw new InvalidRepositoryException($message);
        }

        $payment->setServiceId($service->getId());

        if ($payment->save() === false) {
            throw new InvalidRepositoryException('缴费信息保存失败');
        }

        return $service;
    }

    public function enableCompanyService($company_id, $payment_id, $service_type)
    {
        $service = $this->getCompanyServiceById($company_id);
        if ($service === false) {
            $service = $this->addService(
                $company_id,
                $payment_id,
                $service_type
            );
        }
        if ($service->getServiceStatus() == CompanyService::SERVICE_ENABLE) {
            return $service;
        }

        $start = strtotime(date('Y-m-d', strtotime('+1 day')));
        $end = strtotime('+365 days', $start) - 1;
        $service->setStartTime($start);
        $service->setEndTime($end);
        $service->setServiceStatus(CompanyService::SERVICE_ENABLE);
        $service->setPaymentStatus(CompanyPayment::STATUS_OK);
        if ($service->save() === false) {
            $error = isset($service->getMessages()[0]) ? $service->getMessages()[0] : '普惠企业服务状态保存失败';
            throw new InvalidRepositoryException($error);
        }

        return $service;
    }

    /**
     * 生成服务记录
     * @param $service_info
     * @param int $status
     * @return EntityCompanyService
     * @throws InvalidRepositoryException
     */
    public function setService($service_info, $status = self::SERVICE_ENABLE)
    {
        if ($status != self::SERVICE_ENABLE) {
            $time = null;
            $end = null;
        } else {
            $time = strtotime(date('Y-m-d', strtotime('+1 day')));
            $end = strtotime('+365 days', $time) - 1;
        }
        $service = new EntityCompanyService();
        $service->setCompanyId($service_info['company_id']);
        $service->setStartTime($time);
        $service->setEndTime($end);
        $service->setPaymentStatus($service_info['payment_status']);
        $service->setLevelId($service_info['level_id']);
        $service->setType($service_info['type']);
        $service->setBillId($service_info['bill_id']);
        $service->setBillStatus($service_info['bill_status']);
        $service->setReportStatus($service_info['report_status']);
        $service->setServiceStatus($status);
        if ($service->save()) {
            return $service;
        } else {
            $message = isset($service->getMessages()[0]) ? $service->getMessages()[0] : '服务记录保存失败';
            throw new InvalidRepositoryException($message);
        }
    }

    public function getServiceData($service_id)
    {
        $service = $this->getService($service_id);
        if($service === false) {
            return [[], [], []];
        }

        $payment = [];
        if($service->company_payment instanceof \Wdxr\Models\Entities\CompanyPayment) {
            $payment = $service->company_payment->toArray();
            $payment['type_name'] = \Wdxr\Models\Repositories\CompanyPayment::getTypeName($payment['type']);
            $payment['status_name'] = \Wdxr\Models\Repositories\CompanyPayment::getStatusName($payment['status']);
        }

        $level = [];
        if($service->level instanceof \Wdxr\Models\Entities\Levels) {
            $level = $service->level->toArray();
        }

        //获取合同签订地址
        $contract = [];
        if($service->contracts instanceof \Wdxr\Models\Entities\Contracts) {
            $contract = $service->contracts->toArray();
        }

        $contract['start_time'] = date('Y-m-d', $service->getStartTime());
        $contract['end_time'] = date('Y-m-d', $service->getEndTime());
        $contract['status'] = $service->getServiceStatus();
        $contract['bill'] = $service->getBillStatus() === 1 ? '正常' : '待交票据';
        $contract['report'] = $service->getReportStatus() ? '正常' : '待交征信';

        return [$payment, $level, $contract];
    }

    /**
     * 获取企业的服务列表
     * @param $company_id
     * @return EntityCompanyService|EntityCompanyService[]
     */
    public function getCompanyServices($company_id)
    {
        return EntityCompanyService::find([
            'conditions' => 'company_id = :company_id:',
            'bind' => ['company_id' => $company_id],
            'order' => 'createAt desc, id desc'
        ]);
    }

    /**
     * 获取某企业最近一个订阅服务
     * @param $company_id
     * @return EntityCompanyService
     */
    public function getLastCompanyService($company_id)
    {
        return EntityCompanyService::findFirst([
            'conditions' => 'company_id = :company_id:',
            'bind' => ['company_id' => $company_id],
            'order' => 'start_time desc, id desc'
        ]);
    }

    public static function getServiceCount()
    {
        return EntityCompanyService::count([
            'conditions' => 'service_status = ?0',
            'bind' => [self::SERVICE_ENABLE],
        ]);
    }


    public function getCompanyCount($start_time, $end_time, $is_partner)
    {
        $options = [
            'time' => date('Y-m-d', $start_time).' - '.date('Y-m-d', $end_time),
            'type' => $is_partner ? CompanyService::TYPE_PARTNER : CompanyService::TYPE_ORDINARY
        ];
        return count($this->getServiceCompany($options)->getQuery()->execute());
    }

}