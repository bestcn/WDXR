<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Exception;
use Phalcon\Mvc\Model\Criteria;
use Wdxr\Auth\Auth;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\ApplyService;
use Wdxr\Models\Services\Company as SerCompany;
use Wdxr\Models\Services\CompanyRecommends;
use Wdxr\Modules\Admin\Forms\ApplyForm;
use Wdxr\Modules\Admin\Forms\PaymentForm;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;

class ApplyController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->tag->prependTitle("企业申请");
    }

    public function paymentAction($company_id)
    {
        $form = new PaymentForm();
        $company = (new Company())->Byid($company_id);
        if($company === false){
            $this->flash->error('查询不到当前企业');
            return $this->response->redirect('admin/apply/list');
        }
        //创建用户及企业
        $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($company_id);
        $loan = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($company_id);
        if($payment !== false && $loan !== false ){
            $this->flash->error('当前企业已有缴费或普惠申请!');
            return $this->response->redirect('admin/apply/list');
        }

        $this->view->setVar('form', $form);
        $this->view->setVar('company', $company);
        if($this->request->isPost()) {
            $data = $this->request->getPost();
            if($form->isValid($data) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $device_id = UserAdmin::getDeviceId($data['admin_id'], UserAdmin::TYPE_ADMIN);
                //根据企业级别确定缴费金额
                $amount = \Wdxr\Models\Repositories\Level::getLevelAmount($data['level_id']);
                $files = (new ToolsController())->upload('payment', $device_id);
                $data['bankcard_photo'] = $files['bankcard_photo'];
                unset($files['bankcard_photo']);
                if(isset($data['work_photo']) && !empty($data['work_photo'])){
                    $data['work_photo'] = $files['work_photo'];
                    unset($files['work_photo']);
                }
                $voucher = implode(',', $files);
                try {
                    //事务
                    $this->db->begin();
                    //添加缴费信息
                    $payment_id = CompanyPayment::addPaymentInfo($company_id, $amount, $device_id, $voucher, $data['level_id'], $data['payment_type']);
                    Company::updateCompanyPaymentInfo($company_id,$device_id,$data);
                    $this->db->commit();
                    $this->flash->success('企业缴费信息提交成功');
                    return $this->response->redirect('admin/apply/list');
                }catch (InvalidRepositoryException $exception) {
                    $this->db->rollback();
                    $this->flash->error($exception->getMessage());
                }
            }
        }
    }

    public function listAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Companys', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $paginator = SerCompany::getUnApplyListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function infoAction($company_id)
    {
        try {
            if(empty($company_id)) {
                $this->flash->error("无效的企业标志");
                return $this->response->redirect('admin/apply/list');
            }
            $company = Company::getCompanyById($company_id);
            $company_info = CompanyInfo::getCompanyInfoById($company->getInfoId());
            $address['province']=Regions::getRegionName($company_info->getProvince())->name;
            $address['city']=Regions::getRegionName($company_info->getCity())->name;
            $address['district']=Regions::getRegionName($company_info->getDistrict())->name;
            $form = new ApplyForm();
            $this->view->setVars([
                'company_id' => $company_id,
                'info' => $company_info,
                'address' => $address,
                'form' => $form,
                'company' => $company,
            ]);

            if($this->request->isPost()) {
                $admin_id = $this->request->getPost('admin_id');
                $device_id = UserAdmin::getDeviceId($admin_id, UserAdmin::TYPE_ADMIN);
                $files = (new ToolsController())->upload('apply', $device_id);
                $data = array_merge($this->request->getPost(), $files);
                $data['company_id'] = $company_id;
                $data['device_id'] = $device_id;
                if ($form->isValid($data) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->db->begin();
                    (new ApplyService())->submitApplyInfo($company_id,$data);
                    $this->db->commit();
                    $this->flash->success("企业申请成功");
                    return $this->response->redirect('admin/companys/auditing');
                }
            }
        } catch (InvalidServiceException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
            return $this->response->redirect('admin/apply/list');
        } catch (InvalidRepositoryException $exception) {
            $this->db->rollback();
            $this->flash->error($exception->getMessage());
            return $this->response->redirect('admin/apply/list');
        } catch (Exception $exception) {
            $this->flash->error($exception->getMessage());
            return $this->response->redirect('admin/apply/list');
        }
    }

}