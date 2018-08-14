<?php
namespace Wdxr\Modules\Api\Controllers;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Models\Repositories\Follow;
use Wdxr\Models\Repositories\LoansInfo;
use Wdxr\Models\Repositories\Loan;
use Wdxr\Models\Services\Loan as SerLoan;
use Wdxr\Modules\Api\Forms\LoanOneForm;
use Wdxr\Modules\Api\Forms\LoanTwoForm;
use Wdxr\Modules\Api\Forms\LoanEditForm;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;

class LoanController extends ControllerBase
{

    //申请状态
    const STATUS_NEW = -1;         //当前状态为新建
    //申请期限
    const TERM_THREE=1;             //三个月
    const TERM_SIX=2;               //六个月
    const TERM_NINE=3;              //九个月
    const TERM_TWELVE=4;            //十二个月


    //获取待申请企业列表
    public function newAction()
    {
        $uid=JWT::getUid();
        $page = $this->request->getPost('page');
        if(!$page){
            $page = 1;
        }
        $numberPage = ($page-1)*10;
        //传递搜索条件
        $parameters = "";
        if($this->request->getPost('name')) {
            $parameters = $this->request->getPost('name');
        }
        $follow = Follow::getFollowByDeviceId($uid);
        if($follow){
            $getFloolw = $follow->getFollow();
        }else{
            $getFloolw = 0;
        }
        $data=(new SerLoan())->getLoanCompanyList($parameters,$numberPage,trim($getFloolw,",") ?: '0');
        if(empty($data)){
            return $this->json(self::RESPONSE_OK, $data, "没有更多的待申请企业！");
        }
        // 保存状态为新建
        $this->redis->save("loan".$uid,self::STATUS_NEW);
        return $this->json(self::RESPONSE_OK, $data, "初始化新建状态");
    }

    //第一步
    public function oneAction()
    {
        try {
            $data = $this->request->getPost();
            // 验证数据格式
            $form= new LoanOneForm();
            $serLoan = new SerLoan();
            $valids = $form->isValid($data);
            if($valids == false) {
                throw new Exception($form->getMessages()[0]);
            }
            $serLoan->setCurrentCompanyId($data['company_id']);
            //    验证短信验证码
            $valid=$serLoan->telCodeVerify($data);
            if($valid!==true){
                throw new Exception($valid);
            }

            $serLoan->one($data);
            return $this->json(self::RESPONSE_OK, $data['company_id'], '申请信息提交成功');
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED,null, $exception->getMessage());
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    //第二步提交
    public function twoAction()
    {
        try {
            $data = $this->request->getPost();
            // 验证数据格式
            $form= new LoanTwoForm();
            $serLoan = new SerLoan();
            $valid = $form->isValid($data);
            if($valid == false) {
                throw new Exception($form->getMessages()[0]);
            }
            $company_id = $serLoan->two($data);
            return $this->json(self::RESPONSE_OK, $company_id, '申请信息提交成功');
        } catch (InvalidServiceException $exception){
            return $this->json(self::RESPONSE_FAILED,null,$exception->getMessage());
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED,null, $exception->getMessage());
        }
    }
    //提交后查看确认
    public function confirmAction()
    {
        try {
                $serLoan = new SerLoan();
                $data=$serLoan->getAllData(3);
                // 验证数据格式
                $form= new LoanEditForm();
                $valid = $form->isValid($data);
                if($valid == false) {
                    throw new Exception($form->getMessages()[0]);
                }
                //提交确认
                $this->db->begin();
                $serLoan->confirm($data);
                $this->db->commit();
                return $this->json(self::RESPONSE_OK,null,'普惠审核信息提交成功');
        } catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }catch (InvalidServiceException $exception){
            return $this->json(self::RESPONSE_FAILED,null,$exception->getMessage());
        }catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }
    //获取信息
    public function selectAction()
    {
        $serLoan = new SerLoan();
        $id = $this->request->getPost('id');
        $step = $this->request->getPost('step');
        try {
            if(!$id) { throw new Exception("参数错误"); }
            if($step!=4){
                $company = Company::getCompanyById($id);
            }
        } catch (InvalidRepositoryException $exception) {
            $serLoan->deleteCompanyCache($id);
            return $this->json(self::RESPONSE_FAILED, null, '申请的企业不存在或已被删除');
        }
        try {
            if($step==4){
                $info = LoansInfo::getById($id);
                $loan=Loan::getLoanById($info->getUId());
                $company = Company::getCompanyById($loan->getCompanyId());
            }
        } catch (InvalidRepositoryException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, '申请的企业不存在或已被删除');
        }
        $company_id = $company->getId();
        $serLoan->setCurrentCompanyId($company_id);
        try{
            $data=$serLoan->getAllData($step,$id);
            return $this->json(self::RESPONSE_OK, $data, "信息查询成功");
        }catch (InvalidRepositoryException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        } catch (InvalidServiceException $exception){
            return $this->json(self::RESPONSE_FAILED,null,$exception->getMessage());
        }
    }

    //普惠未完成接口
    public function unfinishedAction()
    {
        try {
            //获取所有未完成企业ID
            $list = (new SerLoan())->getUnLonaList();
            return $this->json(self::RESPONSE_OK, $list, "未完成企业");
        } catch (InvalidRepositoryException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    //普惠申请期限接口
    public function termAction()
    {
            $data=[
                ['id' => self::TERM_THREE, 'name' => "三个月"],
                ['id' => self::TERM_SIX, 'name' => "六个月"],
                ['id' => self::TERM_NINE, 'name' => "九个月"],
                ['id' => self::TERM_TWELVE, 'name' => "十二个月"]
            ];
            return $this->json(self::RESPONSE_OK, $data, "申请期限");
    }

    //普惠申请用途接口
    public function purposeAction()
    {
        return $this->json(self::RESPONSE_OK, ['purpose'=> LoansInfo::PURPOSE], "申请用途");
    }
    

    //中途保存接口
    public function saveAction()
    {
        try {
                $SerLoan = new SerLoan();
                $uid=JWT::getUid();
                $data=$this->request->getPost();
                $data['device_id']=$uid;
                $data['state']=SerLoan::STATUS_UNFINISHED;
                if(!$data["step"]){
                    throw new Exception("请提交当前步骤！");
                }
                if(empty($data["name"]) && $data["step"]=="1"){
                    throw new Exception("您的姓名不能为空！");
                }
                $SerLoan->tempSave($data["step"],$data);
            return $this->json(self::RESPONSE_OK, null, '保存成功');
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    //普惠审核接口
    public function verifyAction()
    {
        try {
            $page = $this->request->getPost('page')?:1;
            $state = $this->request->getPost('status')?:0;
            $order = $this->request->getPost('order')?:1;
                $SerLoan = new SerLoan();
                $data=$SerLoan->getLoanVerifyList($state,$page,$order);
                if(empty($data)){
                    return $this->json(self::RESPONSE_OK, $data, "没有更多的审核申请！");
                }
                return $this->json(self::RESPONSE_OK, $data, null);
        } catch (InvalidRepositoryException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }


    //删除未完成接口
    public function deleteAction()
    {
        try {
            $company_id = $this->request->getPost('id');
            if ($company_id){
                (new SerLoan())->popApplyCompany($company_id);
            }else{
                return $this->json(self::RESPONSE_FAILED, null, "请选择您要删除的普惠申请");
            }
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
        return $this->json(self::RESPONSE_OK, null, '删除未完成的申请成功');
    }

    //驳回后修改接口
    public function editAction()
    {
        try {
                $data = $this->request->getPost();
                $SerLoan = new SerLoan();
                $data['company_id'] =$SerLoan->getCurrentCompanyId();
                // 验证数据格式
                $form= new LoanEditForm();
                $valid = $form->isValid($data);
                if($valid == false) {
                    throw new Exception($form->getMessages()[0]);
                }
                $this->db->begin();
                $SerLoan->edit($data);
                $this->db->commit();
                return $this->json(self::RESPONSE_OK, null, '信息提交成功');
        } catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }catch (InvalidServiceException $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

}