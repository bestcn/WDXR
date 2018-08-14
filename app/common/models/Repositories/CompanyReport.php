<?php

namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\CompanyReport as EntityCompanyReport;
use Wdxr\Models\Entities\CompanyVerify as EntityCompanyVerify;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\Model\Transaction\Failed as TransactionFailed;
use Wdxr\Modules\Admin\Controllers\SettingController;

class CompanyReport extends Repositories
{

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    const TYPE_PERSONAL = 1;
    const TYPE_COMPANY = 2;

    /**
     * @param $id
     * @return EntityCompanyReport
     */
    public static function getCompanyReportById($id)
    {
        /**
         * @var $admin EntityCompanyReport
         */
        $company = EntityCompanyReport::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $company;
    }

    /**
     * 获取一个企业的全部征信
     * @param $company_id
     * @return EntityCompanyReport|EntityCompanyReport[]
     */
    public function getCompanyReportByCompanyId($company_id)
    {
        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $service = $company_service->getCompanyServiceByCompanyId($company_id);
        return EntityCompanyReport::find([
            'conditions' => 'service_id = :service_id:',
            'bind' => ['id' => $service->getId()]
        ]);
    }


    public function getLast()
    {
        return EntityCompanyReport::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $company = new EntityCompanyReport();
        $company->setCompanyId($data["company_id"]);//企业ID
        $company->setReport($data["report"]);//征信报告
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return $company -> getWriteConnection() -> lastInsertId($company -> getSource());
    }

    /**
     * 添加企业征信报告
     * @param $company_id
     * @param $service_id
     * @param int $type
     * @return EntityCompanyReport
     * @throws InvalidRepositoryException
     */
    public static function addCompanyReport($company_id, $service_id, $type = self::TYPE_PERSONAL)
    {
        $company_report = new EntityCompanyReport();
        $company_report->setCompanyId($company_id);
        $company_report->setServiceId($service_id);
        $company_report->setStatus(self::STATUS_DISABLE);
        $company_report->setType($type);
        $company_report->setUserSubmit(0);
        $company_report->setCreateAt(date('Y-m-d H:i:s', time()));
        if (!$company_report->save()) {
            throw new InvalidRepositoryException($company_report->getMessages()[0]);
        }
        return $company_report;
    }

    public function getCompanyReport($service_id, $type = self::TYPE_PERSONAL)
    {
        return EntityCompanyReport::findFirst([
            'conditions' => "service_id = ?0 and type = ?1",
            'bind' => [$service_id, $type]
        ]);
    }

    /**
     * 根据订阅服务设置征信期限
     * @param $service_id
     * @param $type
     * @param $end_time
     * @return bool
     * @throws InvalidRepositoryException
     */
    public function setReportTimeByServiceId($service_id, $type, $end_time)
    {
        $service = (new CompanyService())->getService($service_id);
        $report = $this->getCompanyReport($service_id, $type);
        if ($report === false) {
            $report = self::addCompanyReport($service->getCompanyId(), $service_id, $type);
        }

        $report->setEndTime($end_time);

        if ($report->save() === false) {
            $error = $report->getMessages()[0] ? : "征信报告期限保存失败";
            throw new InvalidRepositoryException($error);
        }
        return true;
    }

    /**
     * 更新企业征信的状态
     * @param $report_id
     * @param $status
     * @return bool
     */
    public static function updateReportStatus($report_id, $status)
    {
        $report = self::getCompanyReportById($report_id);
        $report->setStatus($status);
        return $report->save();
    }

    public function setReportTime($payment_type, $service_id)
    {
        $service = (new CompanyService())->getService($service_id);
        $company_id = $service->getCompanyId();
        $company = Company::getCompanyById($company_id);
        $term_data = (new Rterm())->getTermByPayment($payment_type);//期限数据
        if ($term_data != false) {
            $array = array();
            $array['company_name'] = $company->getName();//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = $term_data->getTerm();
            $array['type'] = $term_data->getType();
            $array['time'] = $service->getStartTime();
            $report_term = new ReportTerm();
            $report_term->addNew($array);
        } else {
            $array = array();
            $array['company_name'] = $company->getName();//公司名称
            $array['company_id'] = $company_id;
            $array['payment'] = $payment_type;
            $array['term'] = SettingController::DATE_DEFAULT;
            $array['type'] = SettingController::DATE_TYPE_MONTH;
            $array['time'] = $service->getStartTime();
            $report_term = new ReportTerm();
            $report_term->addNew($array);
        }

        //征信截止时间
        $report_end_time = CompanyReport::setReportEndTime($array['term'], $array['type'], $service->getStartTime());

        $this->setReportTimeByServiceId($service_id, self::TYPE_PERSONAL, $report_end_time);
        $this->setReportTimeByServiceId($service_id, self::TYPE_COMPANY, $report_end_time);

        return true;
        //设置征信截止日期**dh20170909修改*不生成新的征信信息*修改已有的征信信息
        /*$report = CompanyReport::getCompanyReportById($report_id);
        $report->setEndTime($report_end_time);
        if ($report->save() === false) {
            $error = $report->getMessages()[0] ? : "企业征信报告审核设置失败";
            throw new Exception($error);
        }*/
    }


    //修改审核人ID
    public function save_auditing($id,$verify_id)
    {
        $company = self::getCompanyReportById($id);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }

        return true;
    }

    /**
     * 提交企业征信报告
     * @param $company_id
     * @param $device_id
     * @param $attachments
     * @return int
     * @throws InvalidRepositoryException
     */
    static public function newReport($company_id, $device_id, $attachments,$type)
    {
        $company = Company::getCompanyById($company_id);
        if($company->getAuditing() == RepoCompany::AUDIT_NOT) {
            throw new InvalidRepositoryException("该企业尚未提交申请，请在申请之后再补交票据");
        }
        if($company->getAdminId() != UserAdmin::getAdminId($device_id)) {
            throw new InvalidRepositoryException("该企业不是您的客户，不能使用当前账号操作该企业");
        }
        $service = CompanyService::getCompanyService($company_id);
        if($service === false){
            throw new InvalidRepositoryException("查找不到企业服务信息");
        }
        $report = EntityCompanyReport::findFirst(['conditions' => 'service_id = :service_id: and type = :type:', 'bind' => ['service_id' => $service->getId(),'type'=>$type]]);
        if($report === false){
            $report = new EntityCompanyReport();
            $report->setType($type);
            $report->setCompanyId($company_id);
            $report->setCreateAt(date('Y-m-d H:i:s',time()));
            $report->setStatus(self::STATUS_DISABLE);
            $report->setServiceId($service->getId());
            $report_term =(new ReportTerm())->getReportTermByCompanyId($company_id);
            if($report_term === false){
                throw new InvalidRepositoryException("查找不到征信期限数据！");
            }
            $report_end_time = CompanyReport::setReportEndTime($report_term->getTerm(),$report_term->getType(), $service->getStartTime());
            $report->setEndTime($report_end_time);
        }else{
            $verify = CompanyVerify::getVerifyInfoByDataId($report->getId(), CompanyVerify::TYPE_CREDIT);
            if($verify) {
                if($verify->getStatus() == RepoCompanyVerify::STATUS_NOT) {
                    throw new InvalidRepositoryException("征信报告已经提交，请等待审核结果");
                }
                if($verify->getStatus() == RepoCompanyVerify::STATUS_OK) {
                    throw new InvalidRepositoryException("征信报告已经通过, 无需重复提交");
                }
            }

        }
        $report->setReport($attachments);
        if(!$report->save()) {
            $msg = $report->getMessages()[0] ?  : "保存征信报告失败";
            throw new InvalidRepositoryException($msg);
        }
        CompanyVerify::newVerify($company_id, $device_id, RepoCompanyVerify::TYPE_CREDIT, $report->getId());

        return $report->getId();
    }

    static public function setReportEndTime($term,$type, $start_time)
    {
        switch ($type){
            case 0:
                $data = 'day';
                break;
            case 1:
                $data = 'month';
                break;
            case 2:
                $data = 'year';
                break;
            default:
                $data = 'month';
                break;
        }
        return strtotime("+$term $data", $start_time) - 86400;
    }

    public function setReportEnd($term,$type, $start_time)
    {
        switch ($type){
            case 0:
                $data = 'day';
                break;
            case 1:
                $data = 'month';
                break;
            case 2:
                $data = 'year';
                break;
            default:
                $data = 'month';
                break;
        }
        return strtotime("+$term $data", $start_time) - 86400;
    }

    /*
     * 获取企业的征信状态
     */
    public function getCompanyReportStatus($company_id)
    {
        $report_personal = EntityCompanyReport::findFirst([
            'conditions' => 'company_id = :company_id: and type = :type: and status = :status: ',
            'bind' => ['company_id' => $company_id, 'type' => self::TYPE_PERSONAL, 'status' => self::STATUS_ENABLE],
            'order' => 'createAt desc'
        ]);
        $report_company = EntityCompanyReport::findFirst([
            'conditions' => 'company_id = :company_id: and type = :type: and status = :status: ',
            'bind' => ['company_id' => $company_id, 'type' => self::TYPE_COMPANY, 'status' => self::STATUS_ENABLE],
            'order' => 'createAt desc'
        ]);
        if ($report_personal ===false || $report_company === false) {
            return false;
        }
        return true;
    }

}