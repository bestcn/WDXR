<?php
namespace Wdxr\Models\Repositories;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Models\Services\Loan as SerLoan;
use Wdxr\Models\Entities\Loan as EntityLoan;
use Wdxr\Models\Entities\CompanyVerify as EntityCompanyVerify;
use Wdxr\Models\Entities\LoansInfo as EntityLoansInfo;
use Wdxr\Models\Entities\Users as EntityUser;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\CompanyVerify;

class Loan
{
    const SEX_MAN = 1;         //男
    const SEX_WOMAN = 2;         //女

    static private $_instance = null;

    static public function edit($id,$state)
    {
        $apply = EntityLoan::findFirst(['conditions' => 'id = :id:',
            'bind' => ['id' => $id ]]);
        if($apply){
            $apply->setState($state);
        }else{
            throw new InvalidRepositoryException("查询不到当前申请人");//$flag->getMessages()[0]
        }
        if ($apply->save()){
            return $apply->getId();
        }else{
            throw new InvalidRepositoryException($apply->getMessages()[0]);
        }
    }


    public static function getLoanById($u_id)
    {
        $data = EntityLoan::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $u_id]]);
        if ($data === false) {
            throw new InvalidRepositoryException("查询的信息不存在！");
        }
        return $data;
    }

    public function getById($u_id)
    {
        return EntityLoan::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $u_id]]);
    }

    static public function getByCompanyIdStatus($company_id)
    {
        return EntityLoan::findFirst('( state = 1 or state = 2 or state = 4 or state = 5 or state = 6 ) and company_id = '.$company_id);
    }

    static public function getLoanByCompanyId($company_id)
    {
        $res = EntityLoan::findFirst(['conditions' => 'company_id = :company_id:', 'bind' => ['company_id' => $company_id]]);
        if($res !== false){
            return $res;
        }else{
            throw new InvalidRepositoryException("查询不到您的普惠申请！");
        }
    }

    //通过企业ID获取普惠未完成
    static public function getUnLoanByCompanyId($company_id)
    {
        return EntityLoan::findFirst(['conditions' => 'company_id = :company_id: and state = :state:', 'bind' => ['company_id' => $company_id,'state'=>SerLoan::STATUS_UNFINISHED]]);
    }


    static public function editTel($id,$tel)
    {
        $info = EntityLoansInfo::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        $loan = EntityLoan::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $info->getUId()]]);
        $loan->setTel($tel);

        if($loan->save()) {
            return $loan->getId();
        } else {
            throw new InvalidRepositoryException($loan->getMessages()[0]);
        }
    }

    static public function editCompanyId($id,$company_id)
    {
        $info =EntityLoansInfo::findFirst(['conditions' => 'id = :id:',
            'bind' => ['id' => $id ]]);
        $loan = EntityLoan::findFirst(['conditions' => 'id = :id:',
            'bind' => ['id' => $info->getUId()]]);
        $loan->setCompanyId($company_id);
        if ($loan->save()) {
            return $loan->getId();
        } else {
            throw new InvalidRepositoryException($loan->getMessages()[0]);
        }
    }


    static public function Presentation($id,$data)
    {
        /**
         * @var $apply \Wdxr\Models\Entities\Loan
         */
        $apply = EntityLoan::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id ]]);
        if(isset($data["system_loan"]) && !empty($data["system_loan"])){
            $apply->setSystemLoan($data["system_loan"]);
        }
        if(isset($data["sponsion"]) && !empty($data["sponsion"])){
            $apply->setSponsion($data["sponsion"]);
        }
        if(isset($data["other_loan"]) && !empty($data["other_loan"])){
            $apply->setOtherLoan($data["other_loan"]);
        }
        if(isset($data["unhealthy"]) && !empty($data["unhealthy"])){
            $apply->setUnhealthy($data["unhealthy"]);
        }
        if(isset($data["last_year"]) && !empty($data["last_year"])){
            $apply->setLastYear($data["last_year"]);
        }
        if(isset($data["this_year"]) && !empty($data["this_year"])){
            $apply->setThisYear($data["this_year"]);
        }
        if(isset($data["quota"]) && !empty($data["quota"])){
            $apply->setQuota($data["quota"]);
        }
        if(isset($data["remarks"]) && !empty($data["remarks"])){
            $apply->setRemarks($data["remarks"]);
        }
        $apply->setState($data['state']);
        if ($apply->save()) {
            return $apply->getId();
        } else {
            throw new InvalidRepositoryException($apply->getMessages()[0]);
        }
    }


    //获取待补录数量
    static public function unfinishenCount()
    {
        $uid=JWT::getUid();
        $apply = EntityCompanyVerify::find(['conditions' => 'device_id = :device_id: and status = :status: and type = :type:','bind' => ['device_id' => $uid ,'status'=>CompanyVerify::STATUS_FAIL,'type' =>CompanyVerify::TYPE_LOAN]]);
        if ($apply){
            return count($apply->toArray());
        }else{
            return false;
        }
    }

    //获取普惠待补录列表
    static public function unfinishenList()
    {
        $uid=JWT::getUid();
        return EntityCompanyVerify::find(['conditions' => 'device_id = :device_id: and status = :status: and type = :type:','bind' => ['device_id' => $uid ,'status'=>CompanyVerify::STATUS_FAIL,'type' =>CompanyVerify::TYPE_LOAN]]);
    }



    static public function addNew($data)
    {
        $apply = new EntityLoan();
        $apply->setState($data['state']);
        $apply->setCompanyId($data['company_id']);
        $apply->setDeviceId($data['device_id']);
        if($data['payment_id']) {
            $apply->setPaymentId($data['payment_id']);
        }
        if(isset($data['tel']) && !empty($data['tel'])){
            $apply->setTel($data['tel']);
        }
        if ($apply->save()){
            return $apply->getId();
        }else{
            throw new InvalidRepositoryException("新建普惠申请失败！");

        }
    }

    static public function editPaymentId($id,$payment_id)
    {
        $info = LoansInfo::getLoanInfoById($id);
        if($info === false){
            throw new InvalidRepositoryException("查找不到普惠详细信息！");
        }
        $loan = self::getLoanById($info->getUId());
        $loan->setPaymentId($payment_id);
        if ($loan->save()) {
            $array['loan_id'] = $loan->getId();
            return $array;
        }else{
            throw new InvalidRepositoryException("关联缴费ID失败！");
        }
    }


    static public function getApplyById($u_id)
    {
        $data=EntityLoan::find(['conditions' => 'device_id = :device_id: and state =:state:',
            "columns"=>"id",
            'bind' => ['device_id' => $u_id ,'state'=>SerLoan::STATUS_UNFINISHED],
            'order' => 'id desc'
        ]);
        if ($data){
            return $data->toArray();
        }else{
            throw new InvalidRepositoryException("企业基本信息获取失败");
        }

    }

    //删除普惠未完成申请
    static public function deleteApply($id)
    {
        $apply = EntityLoan::findFirst(['conditions' => 'id = :id:',
            'bind' => ['id' => $id ]
        ]);
        if (!$apply) {
            throw new InvalidRepositoryException("申请没有找到");
        }

        if (!$apply->delete()) {
            throw new InvalidRepositoryException("申请删除失败");
        }

        return true;
    }

    /**
     * 普惠信息状态
     * @param $status
     * @return string
     */
    public function getStatusName($status)
    {
        switch ($status)
        {
            case 1:
                return '待提交';
            case 2:
                return '待审核';
            case 3:
                return '已驳回';
            case 4:
                return '初审通过';
            case 5:
                return '银行驳回';
            case 6:
                return '已完成';
            default:
                return '普惠状态错误';
        }
    }


    public function getLoanByPaymentId($payment_id)
    {
        return \Wdxr\Models\Entities\Loan::findFirst([
            'conditions' => 'payment_id = ?0',
            'bind' => [$payment_id]
        ]);
    }

    public function getLoanInfoByLoanId($loan_id)
    {
        return \Wdxr\Models\Entities\LoansInfo::findFirst([
            'conditions' => 'u_id = ?0',
            'bind' => [$loan_id]
        ]);
    }

    /**
     * 根据状态修改普惠信息
     * @param EntityLoan $loan
     * @param $verify_status
     * @return bool
     * @throws InvalidRepositoryException
     */
    public function setLoanStatus(\Wdxr\Models\Entities\Loan $loan, $verify_status)
    {
        $loan_info = $this->getLoanInfoByLoanId($loan->getId());
        if ($verify_status == CompanyVerify::STATUS_LOAN_FAIL) {
            $loan_info->setState(\Wdxr\Models\Services\Loan::STATUS_FAIL);
            if ($loan_info->save() === false) {
                throw new InvalidRepositoryException('普惠审核提交失败');
            }
            $loan->setState(\Wdxr\Models\Services\Loan::STATUS_FAIL);
            if ($loan->save() === false) {
                throw new InvalidRepositoryException('普惠审核提交失败');
            }
        } elseif ($verify_status == CompanyVerify::STATUS_LOAN_OK) {
            //通过后修改普惠信息与企业信息
            $loan_info->setState(\Wdxr\Models\Services\Loan::STATUS_OK);
            if ($loan_info->save() === false) {
                throw new InvalidRepositoryException('普惠审核提交失败');
            }
            $loan->setState(\Wdxr\Models\Services\Loan::STATUS_OK);
            if ($loan->save() === false) {
                throw new InvalidRepositoryException('普惠审核提交失败');
            }
        }

        return true;
    }
}