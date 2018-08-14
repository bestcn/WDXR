<?php
namespace Wdxr\Modules\Api\Controllers;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Models\Exception\ModelException;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Services\CompanyBank;
use Wdxr\Models\Services\CompanyPayment as ServiceCompanyPayment;
use Wdxr\Modules\Api\Forms\PaymentValidation;

class PaymentController extends ControllerBase
{

    public function newPaymentAction()
    {
        $data = $this->request->getPost();
        $validation = new PaymentValidation();
        $messages = $validation->validate($data);
        if($messages->valid()) {
            $message = $messages->current();
            return $this->json(self::RESPONSE_FAILED, null, $message->getMessage());
        }
        $id = $this->request->getPost('company_id');
//        $level_id = $this->request->getPost('level_id');
        $level = Level::getLevelByDefault();
        if($level === false){
            return $this->json(self::RESPONSE_FAILED, null, '获取不到默认级别设置!');
        }
        $level_id = $level->getId();
        $type = $this->request->getPost('type');
        $voucher = trim($this->request->getPost('voucher'), ',');
        $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($id);
        $loan = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($id);
        if($payment !== false && $loan !== false ){
            return $this->json(self::RESPONSE_FAILED, null, '当前企业已有缴费或普惠申请!');
        }
        try {
            $this->db->begin();
            $amount = Level::getLevelAmount($level_id);
            $company_id = Company::paymentCompany($id,JWT::getUid());
            $payment_id = CompanyPayment::addPaymentInfo($company_id, $amount, JWT::getUid(), $voucher,$type,$level_id );
            CompanyBank::paymentCompanyBank($company_id, $data);
            $this->db->commit();
            return $this->json(self::RESPONSE_OK, null, '缴费申请提交成功，请等待审核');
        } catch (Exception $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    public function paymentMoneyAction(){
        $level = Level::getLevelByDefault();
        if($level === false){
            return $this->json(self::RESPONSE_FAILED, null, '获取不到默认缴费金额');
        }
        return $this->json(self::RESPONSE_OK, ['money'=>$level->getLevelMoney().'元'], "申请缴费金额");
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function addPaymentAction()
    {
        try {
            $id = $this->request->getPost('company_id');
            $level_id = $this->request->getPost('level_id');
            $type = $this->request->getPost('type');
            $voucher = trim($this->request->getPost('voucher'), ',');
//            if(empty($name)) {
//                throw new Exception("请填写企业名称");
//            }
            if(empty($type)) {
                throw new Exception("请选择缴费方式");
            }
            if(!$voucher) {
                throw new Exception('请上传缴费凭证');
            }
            //根据企业级别确定缴费金额
            $amount = \Wdxr\Models\Repositories\Level::getLevelAmount($level_id);
            //事务
            $this->db->begin();
            //创建用户及企业
            $company_id = \Wdxr\Models\Repositories\Company::payAddNew($id, $level_id, $type, JWT::getUid());
            //添加缴费信息
            $company_data = (new Company())->getById($id);
            if($company_data->getPayment() != Company::PAYMENT_APPLY && $company_data->getPayment() != Company::PAYMENT_OK){
                $payment_id = CompanyPayment::addPayment($company_id, $amount, JWT::getUid(), $voucher, $type);
                Company::updateCompanyPaymentId($company_id, $payment_id);
            }else{
                throw new ModelException("该企业已经缴费");
            }

            $this->db->commit();
            return $this->json(self::RESPONSE_OK, ['company_id' => $company_id], '上传缴费凭证成功');
        } catch (ModelException $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取缴费方式
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getPaymentTypeAction()
    {
        $type = [
            ['id' => CompanyPayment::TYPE_TRANSFER, 'name' => CompanyPayment::getTypeName(CompanyPayment::TYPE_TRANSFER)],
            ['id' => CompanyPayment::TYPE_CASH, 'name' => CompanyPayment::getTypeName(CompanyPayment::TYPE_CASH)],
            ['id' => CompanyPayment::TYPE_POS, 'name' => CompanyPayment::getTypeName(CompanyPayment::TYPE_POS)],
        ];

        return $this->json(self::RESPONSE_OK, $type, '获取缴费方式成功');
    }

    /**
     * 获取缴费记录
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getPaymentListAction()
    {
        try {
            //获取筛选参数
            $payment_screen = $this->request->getPost('payment_screen') ? "status = ".$this->request->getPost('payment_screen') : '1=1';
            $level_screen = $this->request->getPost('level_screen') ? : false;
            $sort = $this->request->getPost('sort') ? : false;
            if($sort == 1){
                $sort = 'time asc';
            }else{
                $sort = 'time desc';
            }
            $page = $this->request->getPost('page') ? : 1;
            $list = ServiceCompanyPayment::getPaymentList(JWT::getUid(), $page ,$payment_screen,$sort);
            if(empty($list)) {
                return $this->json(self::RESPONSE_OK, $list, '缴费记录为空');
            }
            //获取筛选级别信息
            foreach($list as $key=>$val){
                    $level_data = (new Level())->getLevel($val['level_id']);
                    $list[$key]['level_name'] = $level_data->getLevelName();
                    $list[$key]['level_money'] = $level_data->getLevelMoney();
                    $company_verify_data = (new CompanyVerify())->getCompanyVerifyByPaymentId($val['payment_id'],CompanyVerify::TYPE_PAYMENT);
                    $list[$key]['remark'] = $company_verify_data ? $company_verify_data->getRemark() : '';
                if($level_screen){
                    if($level_data->getId() != $level_screen){
                        unset($list[$key]);
                    }
                }
            }

            //整理数据
            $ksort=array();
            foreach($list as $key=>$val){
                $ksort[]=$val["time"];
            }
            if($sort == 1){
                array_multisort($ksort,SORT_ASC, $list);
            }else{
                array_multisort($ksort,SORT_DESC, $list);
            }

            return $this->json(self::RESPONSE_OK, $list, '获取缴费记录成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 撤销缴费记录
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function cancelPaymentAction()
    {
        try {
            $payment_id = $this->request->getPost('payment_id');
            ServiceCompanyPayment::cancelPayment($payment_id);
            return $this->json(self::RESPONSE_OK, null, '缴费信息撤销成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

}