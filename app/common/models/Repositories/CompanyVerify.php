<?php
namespace Wdxr\Models\Repositories;

use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\CompanyVerify as EntityCompanyVerify;
use Wdxr\Models\Entities\CompanyBill as EntityCompanyBill;
use Wdxr\Models\Services\VerifyMessages;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class CompanyVerify extends Repositories
{
    const TYPE_DOCUMENTS = 1;//证件信息
    const TYPE_BILL = 2;//票据信息
    const TYPE_CREDIT = 3;//征信报告
    const TYPE_PAYMENT = 4;//缴费信息
    const TYPE_LOAN = 5;//普惠信息

    const STATUS_NOT = 1;//未审核
    const STATUS_FAIL = 2;//未通过
    const STATUS_OK = 3;//通过
    const STATUS_CANCEL = 4;//已取消
    const STATUS_RE_APPLY = 5;  //已处理的驳回信息
    const STATUS_LOAN_FAIL = 6;  //普惠申请通过银行驳回
    const STATUS_LOAN_OK = 7;  //普惠申请通过银行通过

    const USED_IS = 1;//是否开启
    const USED_NOT = 0;

    static private $_instance = null;

    public static function getStatusName($status)
    {
        switch ($status) {
            case self::STATUS_OK:
                return "通过";
            case self::STATUS_NOT:
                return "未审核";
            case self::STATUS_FAIL:
                return "未通过";
            case self::STATUS_CANCEL:
                return "已取消";
            case self::STATUS_RE_APPLY:
                return "已驳回";
            default:
                return "错误";
        }
    }

    public static function getVerifyStatusName()
    {
        return [
            ['key' => self::STATUS_NOT, 'name' => '待审核'],
            ['key' => self::STATUS_FAIL, 'name' => '被驳回'],
            ['key' => self::STATUS_OK, 'name' => '初审通过'],
            ['key' => self::STATUS_CANCEL, 'name' => '已取消'],
            ['key' => self::STATUS_RE_APPLY, 'name' => '已处理'],
            ['key' => self::STATUS_LOAN_FAIL, 'name' => '已关闭'],
            ['key' => self::STATUS_LOAN_OK, 'name' => '已完成'],
        ];
    }

    public static function getTypeName($type)
    {
        switch ($type) {
            case self::TYPE_DOCUMENTS:
                return "证件信息";
            case self::TYPE_BILL:
                return "票据信息";
            case self::TYPE_CREDIT:
                return "征信报告";
            case self::TYPE_PAYMENT:
                return "缴费信息";
            case self::TYPE_LOAN:
                return "普惠信息";
            default:
                return "错误";
        }
    }

    public static function getVerifyEntity($type)
    {
        switch ($type) {
            case self::TYPE_PAYMENT:
                return 'Wdxr\Models\Entities\CompanyPayment';
            case self::TYPE_DOCUMENTS:
                return 'Wdxr\Models\Entities\CompanyInfo';
            case self::TYPE_BILL:
                return 'Wdxr\Models\Entities\CompanyBill';
            case self::TYPE_CREDIT:
                return 'Wdxr\Models\Entities\CompanyReport';
            default:
                throw new InvalidRepositoryException('类型参数错误');
        }
    }

    /**
     * 获取最近一个尚未审核的补交申请
     * @param $company_id
     * @param $type
     * @return EntityCompanyVerify
     * @throws InvalidRepositoryException
     */
    public static function getUnVerify($company_id, $type)
    {
        $verify = EntityCompanyVerify::findFirst([
            'conditions' => 'company_id = :company_id: and type = :type: and status = :status:',
            'bind' => ['company_id' => $company_id, 'type' => $type, 'status' => self::STATUS_NOT],
            'order' => 'apply_time desc']);

        return $verify;
    }

    /**
     * @param $verify_id
     * @return EntityCompanyVerify
     */
    public static function getCompanyVerifyById($verify_id)
    {
        if (isset(self::$_instance[$verify_id]) === false) {
            self::$_instance[$verify_id] = EntityCompanyVerify::findFirst([
                'conditions' => 'id = :id:',
                'bind' => ['id' => $verify_id]
            ]);
        }

        return self::$_instance[$verify_id];
    }

    public static function getCompanyVerifyBuIdAndType($verify_id, $type)
    {
        return EntityCompanyVerify::findFirst([
            'conditions' => 'id = :id: and type = :type:',
            'bind' => ['id' => $verify_id,'type' => $type]
        ]);
    }

    public function getCompanyVerifyByDataIdByLoan($data_id)
    {
        $result = EntityCompanyVerify::findFirst([
            'conditions' => 'data_id = :data_id: and type = :type:',
            'bind' => ['data_id' => $data_id, 'type' => self::TYPE_LOAN]
        ]);
        return $result;
    }

    public static function getLastCompanyVerify($company_id, $type, $status)
    {
        $verify = EntityCompanyVerify::findFirst([
            'conditions' => 'company_id = :company_id: and type = :type: and status = :status:',
            'bind' => ['company_id' => $company_id, 'type' => $type, 'status' => $status],
            'order' => 'apply_time desc'
        ]);

        return $verify;
    }

    //获取指定分类未审核数量
    public static function getUnCompanyVerify($type)
    {
        $verify = EntityCompanyVerify::find([
            'conditions' => 'type = :type: and status = :status:',
            'bind' => ['type' => $type, 'status' => self::STATUS_NOT],
            'order' => 'apply_time desc'
        ]);
        return count($verify->toArray());
    }

    //获取普惠当前用户审核列表
    static public function getLoanVerifyList($uid,$status,$time,$limit)
    {
        $order="desc";
        $conditions = "1=1";
        if($time=="1"){
            $order="desc";
        }elseif($time=="2"){
            $order="asc";
        }
        if(!empty($status) && $status!="0"){
            $conditions="status = $status ";
        }
        $limit=($limit-1)*10;
        $result = EntityCompanyVerify::query()
            ->where("device_id = $uid")
            ->andWhere("type = :type:",["type"=>self::TYPE_LOAN])
            ->andWhere("status != ".self::STATUS_RE_APPLY)
            ->andWhere($conditions)
            ->limit('10',$limit)
            ->orderBy('apply_time '.$order)
            ->execute();
        if($result === false){
            throw new InvalidRepositoryException("没有任何申请");
        }
        return $result->toArray();
    }

    static public function newVerify($company_id, $device_id, $type, $data_id, $status = self::STATUS_NOT)
    {
        $company_verify = new EntityCompanyVerify();
        $company_verify->setCompanyId($company_id);
        $company_verify->setDeviceId($device_id);
        $company_verify->setType($type);
        $company_verify->setStatus($status);
        $company_verify->setApplyTime(time());
        $company_verify->setDataId($data_id);
        $company_verify->setIsUsed(self::USED_IS);
        $company_verify->setAdminId(UserAuth::getAdminId($device_id));
        if($status == CompanyVerify::STATUS_OK) {
            $auditor_id = \Phalcon\Di::getDefault()->get('session')->get('auth-identity')['id'];
            $company_verify->setAuditorId($auditor_id);
        }
        if($company_verify->save() === false) {
            foreach ($company_verify->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加公司缴费审核信息失败');
        }
        //添加审核消息到消息列表
        VerifyMessages::newVerifyMessages($company_id,$company_verify->getId(),$type);
        return $company_verify->getId();
    }

    //根据企业ID获取
    public function getCompanyVerifyByCompanyId($id,$type,$screen = false)
    {
        if ($screen){
            return EntityCompanyVerify::findFirst(['conditions' => 'company_id = :company_id: and type = :type: and status = :status:',
                'bind' => ['company_id' => $id, 'type' => $type , 'status' => $screen]
                , 'order' => 'apply_time desc'
            ]);
        }else{
            return EntityCompanyVerify::findFirst(['conditions' => 'company_id = :company_id: and type = :type: and status != :status:',
                'bind' => ['company_id' => $id, 'type' => $type , 'status' => 5]
                , 'order' => 'apply_time desc'
            ]);
        }
    }

    //根据缴费表ID获取
    public function getCompanyVerifyByPaymentId($id,$type,$screen = false)
    {
        if ($screen){
            return EntityCompanyVerify::findFirst(['conditions' => 'data_id = :data_id: and type = :type: and status = :status:',
                'bind' => ['data_id' => $id, 'type' => $type , 'status' => $screen]
                , 'order' => 'apply_time desc'
            ]);
        }else{
            return EntityCompanyVerify::findFirst(['conditions' => 'data_id = :data_id: and type = :type: and status != :status:',
                'bind' => ['data_id' => $id, 'type' => $type , 'status' => 5]
                , 'order' => 'apply_time desc'
            ]);
        }
    }

    static public function WebNewVerify($company_id, $device_id, $type, $data_id,$admin_id)
    {
        $company_verify = new EntityCompanyVerify();
        $company_verify->setCompanyId($company_id);
        $company_verify->setDeviceId($device_id);
        $company_verify->setType($type);
        $company_verify->setStatus(self::STATUS_NOT);
        $company_verify->setApplyTime(time());
        $company_verify->setDataId($data_id);
        $company_verify->setIsUsed(self::USED_IS);
        $company_verify->setAdminId($admin_id);

        if($company_verify->save() === false) {
            foreach ($company_verify->getMessages() as $message) {
                throw new InvalidRepositoryException($message->getMessage());
            }
            throw new InvalidRepositoryException('添加公司缴费审核信息失败');
        }
//        添加审核消息到消息列表
        VerifyMessages::newVerifyMessages($company_id,$company_verify->getId(),$type);

        return $company_verify->getId();
    }

    public static function getStatus($id, $type)
    {
        return EntityCompanyVerify::findFirst([
            'conditions' => 'company_id = :company_id: and type = :type: ',
            'bind' => ['company_id' => $id , 'type' => $type],
            'order' => 'apply_time desc'
        ]);
    }


    public function getLast()
    {
        return EntityCompanyVerify::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        if (isset($data['device_id']) === false) {
            $data['device_id'] = 0;
        }
        $company = new EntityCompanyVerify();
        $company->setVerifyTime(time());//审核时间
        $company->setApplyTime(time());//提交时间
        $company->setDeviceId($data['device_id']);//业务员ID
        $company->setCompanyId($data["company_id"]);//企业ID
        $company->setAuditorId($data["auditor_id"]);//审核人ID
        $company->setStatus($data["company_auditing"]);//审核状态
        $company->setType($data["type"]);//审核种类
        $company->setIsUsed(1);//是否正在使用
        $company->setDataId($data["data_id"]);//材料ID
        $company->setRemark($data["remark"]);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return true;
    }

    public function edit($id, $data)
    {
        $company = self::getCompanyVerifyById($id);
        $company->setVerifyTime(time());//审核时间
        $company->setAuditorId($data['auditor']);//审核人ID
        $company->setStatus($data["status"]);//审核状态
        $company->setRemark($data["remark"]);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return true;
    }

    public function editVerify($id, $data)
    {
        $company = self::getCompanyVerifyById($id);
        $company->setVerifyTime(time());//审核时间
        $company->setAuditorId($data['auditor']);//审核人ID
        $company->setStatus($data["status"]);//审核状态
        $company->setRemark($data["remark"]);
        $company->setVerifyTime(time());
        if (!$company->save()) {
            return false;
        }
        return true;
    }

    /**
     * 审核企业信息
     * @param $id
     * @param $auditor_id
     * @param $status
     * @param null $remark
     * @return bool
     * @throws InvalidRepositoryException
     */
    public static function verifyCompany($id, $auditor_id, $status, $remark = null)
    {
        $company_verify = self::getCompanyVerifyById($id);
        if ($company_verify === false) {
            throw new InvalidRepositoryException('企业审核信息获取失败');
        }
        $company_verify->setVerifyTime(time());//审核时间
        $company_verify->setAuditorId($auditor_id);//审核人ID
        $company_verify->setStatus($status);//审核状态
        $company_verify->setRemark($remark);
        if ($company_verify->save() === false) {
            throw new InvalidRepositoryException('企业审核信息保存失败');
        }
        return true;
    }

    /**
     * @param $data_id
     * @param $type
     * @return EntityCompanyVerify
     */
    public static function getVerifyInfoByDataId($data_id, $type)
    {
        $verify = \Wdxr\Models\Entities\CompanyVerify::findFirst([
            'data_id = :data_id: and type = :type:',
            'bind' => ['data_id' => $data_id, 'type' => $type],
            'order'=> 'apply_time desc'
        ]);

        return $verify;
    }

    /**
     * 审核是否通过
     * @param $data_id
     * @param $type
     * @return EntityCompanyVerify
     */
    public static function isVerifyOkByDataId($data_id, $type)
    {
        $verify = \Wdxr\Models\Entities\CompanyVerify::findFirst([
            'data_id = :data_id: and type = :type: and status = :status:',
            'bind' => ['data_id' => $data_id, 'type' => $type, 'status' => self::STATUS_OK],
        ]);

        return $verify;
    }

    /**
     * @param $data_id
     * @param $type
     * @return bool|EntityCompanyVerify
     */
    public function getCompanyVerifyByDataId($data_id, $type)
    {
        $verify = self::getVerifyInfoByDataId($data_id, $type);
        if ($verify === false) {
            return false;
        }
        /**
         * @var $admin Admin
         */
        $admin = Repositories::getRepository('Admin');
        $verify->auditor = $verify === false ? '无' : $admin->getAdminName($verify->getAuditorId());
        $verify->admin_name = $verify === false ? '无' : $admin->getAdminName($verify->getAdminId());
        $verify->device_name = $verify === false ? '无' : UserAdmin::getNameByDeviceId($verify->getDeviceId());

        return $verify;
    }

    /**
     * 隐藏或找回审核记录
     * @param $verify_id
     * @param $status
     * @return bool
     */
    public static function hiddenVerify($verify_id, $status)
    {
        $verify = self::getCompanyVerifyById($verify_id);
        if ($verify->getIsHidden() == $status) {
            return true;
        } else {
            $verify->setIsHidden($status);
            return $verify->save();
        }
    }

}