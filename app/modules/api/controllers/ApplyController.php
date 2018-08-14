<?php
namespace Wdxr\Modules\Api\Controllers;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Models\Entities\CompanyPayment as EntityCompanyPayment;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\CompanyPayment as RepoCompanyPayment;
use Wdxr\Models\Entities\CompanyInfo as EntityRepoCompanyInfo;
use Wdxr\Models\Exception\ModelException;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Repositories\CompanyInfo as RepoCompanyInfo;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\CompanyService as RepoCompanyService;
use Wdxr\Models\Repositories\CompanyRecommend;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Follow;
use Wdxr\Models\Repositories\Level as RepoLevel;
use Wdxr\Models\Repositories\Regions as RepoRegions;
use Wdxr\Models\Repositories\Contract;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;
use Wdxr\Models\Repositories\CompanyReport as RepoCompanyReport;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\ApplyService;
use Wdxr\Models\Services\Company;
use Wdxr\Models\Services\CompanyRecommends;
use Wdxr\Models\Services\CompanyVerify as ServiceCompanyVerify;
use Wdxr\Models\Services\Contract as ServiceContract;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Services\SMS;
use Wdxr\Models\Services\Services;
use Wdxr\Models\Services\UploadService;
use Wdxr\Modules\Api\Forms\ApplyForm;
use Wdxr\Models\Repositories\BankList as RepoBankList;
use Wdxr\Modules\Api\Forms\ApplyManualInputForm;
use Wdxr\Modules\Api\Forms\ApplyOneForm;
use Wdxr\Modules\Api\Forms\ApplyOneValidation;
use Wdxr\Modules\Api\Forms\ApplySixForm;
use Wdxr\Modules\Api\Forms\ApplyThreeForm;
use Wdxr\Modules\Api\Forms\ApplyTwoForm;
use Wdxr\Modules\Api\Forms\ApplyTwoValidation;
use Wdxr\Modules\Api\Forms\ApplyValidation;
use Wdxr\Modules\Api\Forms\ReApplyForm;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;

class ApplyController extends ControllerBase
{
    const TYPE_NEW = 1;
    const TYPE_RE = 2;

    /**
     * 获取新申请企业列表
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getApplyPaymentListAction()
    {
        $type = $this->request->getPost('type');
        $page = $this->request->getPost('page');
        $where = $this->request->getPost('name');
        $where = $where ? " name like '%".$where."%' " : " 1 = 1 ";
        if(!$page){
            $page = 1;
        }
        $limit = ($page-1)*10;
        try {
            $uid = JWT::getUid();
            $follow = Follow::getFollowByDeviceId($uid);
            if($follow){
                $getFloolw = $follow->getFollow();
            }else{
                $getFloolw = 0;
            }
            $list = RepoCompany::getNotCompanyList($type,$where,trim($getFloolw,",") ?: '0');
            if(empty($list)) {
                return $this->json(self::RESPONSE_OK, $list, '记录为空');
            }
            $list = array_slice($list,$limit,10);
            return $this->json(self::RESPONSE_OK, $list, '获取记录成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取未完成的企业列表
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getApplyCompanyListAction()
    {
        $list = (new ApplyService())->getApplyList();

        if($list) {
            //排序
            foreach ($list as $key=>$val){
                $sort[] = $val['date'];
            }
            array_multisort($sort,SORT_DESC,$list);
            return $this->json(self::RESPONSE_OK, $list, '获取未完成的企业列表成功');
        }
        return $this->json(self::RESPONSE_OK, [], '未完成的企业列表为空');
    }

    /**
     * 删除未完成的企业
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function deleteApplyCompanyAction()
    {
        $company_id = $this->request->getPost('company_id');
        try {
            (new ApplyService())->popApplyCompany($company_id);
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
        return $this->json(self::RESPONSE_OK, null, '删除未完成的企业成功');
    }

    /**
     * 申请刚开始时必须首先调用该接口，获取企业ID，判断有无调取地理位置信息的权限
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     * @throws Exception
     */
    public function getCompanyStepAction()
    {
        $apply = new ApplyService();
        $id = $this->request->getPost('id');
        try {
            if(!$id) { throw new Exception("参数错误"); }
            $company = RepoCompany::getCompanyById($id);
        } catch (InvalidRepositoryException $exception) {
            $apply->deleteCompanyCache($id);
            return $this->json(self::RESPONSE_FAILED, null, '申请的企业不存在或已被删除');
        }
        $company_id = $company->getId();

        //绑定用户
        if($apply->binding($company_id) === false){
            return $this->json(self::RESPONSE_FAILED, null, '其他业务员正在操作');
        }

        try {
            $apply->applyStatus($company);
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }

        $apply->setCurrentCompanyId($company_id);
        $step = $apply->checkComplete();
        if($step) {
            $step = $step ? $apply->getCurrentStep() : array_shift($step);
            return $this->json(self::RESPONSE_OK, ['step' => $step, 'is_partner' => 0, 'company_id' => $company_id, 'contract_num' => '审核通过后生成'], "已经进行到第{$step}步");
        }
        //获取企业的工商基本信息
        $data['step'] = 0;
        $data['company_id'] = $company_id;
        $data['company_name'] = $company->getName();
        $data['is_partner'] = 0;
        $data['contract_num'] = '审核通过后生成';
        //获取企业工商信息
        $company_info = (new CompanyInfo())->getCompanyInfo($company->getInfoId());
        $data['type'] = $company_info->getType();
        $data['legal_name'] = $company_info->getLegalName();
        $data['licence_num'] = $company_info->getLicenceNum();
        $data['period'] = $company_info->getPeriod();
        $data['address'] = $company_info->getAddress();
        $data['province'] = $company_info->getProvince() ?: '';
        $data['province_data'] = RepoRegions::getRegionName($company_info->getProvince())->name ? : '';
        $data['city'] = $company_info->getCity() ?: '';
        $data['city_data'] = RepoRegions::getRegionName($company_info->getCity())->name ? : '';
        $data['district'] = $company_info->getDistrict() ?: '';
        $data['district_data'] = RepoRegions::getRegionName($company_info->getDistrict())->name ? : '';
        $data['scope'] = $company_info->getScope();
        return $this->json(self::RESPONSE_OK, $data, "");
    }

    /**
     * 第一步
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function onesAction()
    {
        $data = $this->request->getPost();
        $apply = new ApplyService();
        $validation = new ApplyOneValidation();
        try {
            if ($validation->validate($data) == false) {
                throw new Exception($validation->getMessages()[0]);
            }
            $apply->one($data);
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }

        return $this->json(self::RESPONSE_OK, null, '公司基本信息保存成功');
    }


    /**
     * 第二步
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function twoAction()
    {
        $data = $this->request->getPost();

        $apply = new ApplyService();
        $phone = isset($data['contact_phone']) ? $data['contact_phone'] : '';

        $validation = new ApplyTwoForm(null, ['is_verified' => $apply->verifyCompanySmsStatus($phone)]);
        try {
            if($validation->isValid($data) == false) {
                throw new Exception($validation->getMessages()[0]);
            }
            $apply->two($data);
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }

        return $this->json(self::RESPONSE_OK, null, '公司信息保存成功');
    }

    /**
     * 第三步(废弃)
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function threeAction()
    {
        $data = $this->request->getPost();
        $apply = new ApplyService();
//        $is_payment = CompanyPayment::isPaymentLoan($apply->getCurrentCompanyId());
        $is_payment = 0;
        $form = new ApplyThreeForm(null, ['bank_type' => $data['bank_type'],'is_payment' => $is_payment]);
        try {
            $valid = $form->isValid($this->request->getPost());
            if($valid == false) {
                throw new Exception($form->getMessages()[0]);
            }
            $apply->three($data);
            $un_complete = $apply->checkComplete();
            if(is_array($un_complete)) {
                $un_complete = implode(',', $un_complete);
                throw new Exception("第{$un_complete}步尚未确定(仅临时保存)，请完成后再提交");
            }
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }

        return $this->json(self::RESPONSE_OK, null, '公司开户行信息保存成功');
    }

    /**
     * 第四步(弃用)
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
//    public function fourAction()
//    {
//        $data = $this->request->getPost();
//        $apply = new ApplyService();
//
//        $form = new ApplySixForm();
//        try {
//            $valid = $form->isValid($this->request->getPost());
//            if($valid == false) {
//                throw new Exception($form->getMessages()[0]);
//            }
//
//            $apply->four($data);
//            $un_complete = $apply->checkComplete();
//            if(is_array($un_complete)) {
//                $un_complete = implode(',', $un_complete);
//                throw new Exception("第{$un_complete}步尚未确定(仅临时保存)，请完成后再提交");
//            }
//        } catch (InvalidServiceException $exception) {
//            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
//        } catch (Exception $exception) {
//            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
//        }
//        return $this->json(self::RESPONSE_OK, null, '公司合同信息保存成功');
//    }

    /**
     * 获取前2步获取的企业信息
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getCompanyDataAction()
    {
        $apply = new ApplyService();
        $data = $apply->getAllData();

        if (!$data) {
            return $this->json(self::RESPONSE_FAILED, null, '获取企业信息失败');
        }
        return $this->json(self::RESPONSE_OK, $data, '获取企业信息成功');
    }


    /**
     * 合同签名
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function signAction()
    {
        try {
            $this->db->begin();
            $apply = new ApplyService();
            $apply->submitApply();
            $apply->deleteCompanyCache();
            $this->db->commit();
            return $this->json(self::RESPONSE_OK, '', '申请信息已经提交，请等待审核结果');
        } catch (Exception $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取每一步的企业数据
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getStepDataAction()
    {
        $company_id = $this->request->getPost('company_id');
        $step = $this->request->getPost('step');

        $apply = new ApplyService();
        if (!$apply->getCurrentCompanyId() && !$company_id) {
            return $this->json(self::RESPONSE_FAILED, null, '参数错误，请添加企业ID参数');
        }
        if ($company_id) {
            $apply->setCurrentCompanyId($company_id);
        }
//        if($apply->setCurrentStep($step) === false) {
//            return $this->json(self::RESPONSE_FAILED, null, '当前步数保存失败');
//        }
        if (($data = $apply->getCompanyStepData($step)) === false) {
            return $this->json(self::RESPONSE_FAILED, null, '企业信息获取失败');
        }
        return $this->json(self::RESPONSE_OK, $data, '企业信息获取成功');
    }

    /**
     * 临时保存
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function tempSaveAction()
    {
        $step = $this->request->getPost('step');
        $data = $this->request->getPost();
        try {
            $apply = new ApplyService();
            $apply->tempSave($step, $data);
        } catch (InvalidServiceException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
        return $this->json(self::RESPONSE_OK, null, '保存成功');
    }

    /**
     * 获取最近可用的合同编号
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getContractNumAction()
    {
        $longitude = $this->request->getPost('longitude');
        $latitude = $this->request->getPost('latitude');
        try {
            $apply = new ApplyService();
            $company_id = $apply->getCurrentCompanyId();

            return $this->json(self::RESPONSE_OK, ['num' => '审核通过后生成', 'id' => 0], '获取合同编号成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 从request获取企业数据
     * @return array
     */
    public function getCompanyData()
    {
        $data = [];
        $data['licence_num'] = $this->request->getPost('licence_num');
        $data['scope'] = $this->request->getPost('scope');
        $data['period'] = $this->request->getPost('period');
        $data['legal_name'] = $this->request->getPost('legal_name');
        $data['province'] = $this->request->getPost('province');
        $data['city'] = $this->request->getPost('city');
        $data['district'] = $this->request->getPost('district');
        $data['address'] = $this->request->getPost('address');
        $data['contacts'] = $this->request->getPost('contacts');
        $data['contact_title'] = $this->request->getPost('contact_title');
        $data['contact_phone'] = $this->request->getPost('contact_phone');
        $data['licence'] = $this->request->getPost("licence");
        $data['type'] = $this->request->getPost("type");
        if ($this->request->getPost('type') == RepoCompany::TYPE_COMPANY) {
            $data['account_permit'] = $this->request->getPost("account_permit");
            $data['credit_code'] = $this->request->getPost("credit_code");
        }
        $data['idcard_up'] = $this->request->getPost("idcard_up");
        $data['idcard_down'] = $this->request->getPost("idcard_down");
        $data['photo'] = $this->request->getPost("photo");
        $data['intro'] = $this->request->getPost("intro");

        //推荐人
        $data['recommend'] = $this->request->getPost('recommend');
        //身份证号/邮政编码
        $data['idcard'] = $this->request->getPost('idcard');
        $data['sub_category'] = $this->request->getPost('sub_category');
        $data['zipcode'] = $this->request->getPost('zipcode');
        $data['shop_img'] = $this->request->getPost('shop_img');
        return $data;
    }

    /**
     * 企业补录列表
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getApplyListAction()
    {
        $device_id = JWT::getUid();
        $page = $this->request->getPost("page") ? : 1;
        try {
            $list = ServiceCompanyVerify::getCompanyVerifyInfoList($device_id, $page);
            return $this->json(self::RESPONSE_OK, $list, '获取企业补录列表名成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取企业详细信息
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getCompanyInfoAction()
    {
        try {
            $company_id = $this->request->getPost("company_id");
            $company = RepoCompany::getCompanyById($company_id);
            if ($company === false) {
                throw new Exception('企业基本信息获取失败');
            }
            $info = RepoCompanyInfo::getCompanyInfoById($company->getInfoId());
            if ($info === false) {
                throw new Exception('企业工商信息获取失败');
            }
            $info = $info->toArray();
            $info['company_id'] = $company_id;
            $info['company_name'] = $company->getName();
            $info['account_permit_pic'] = $info['account_permit'] ? UploadService::getAttachmentUrl($info['account_permit']) : '';
            $info['credit_code_pic'] = $info['credit_code'] ? UploadService::getAttachmentUrl($info['credit_code']) : '';
            $info['idcard_up_pic'] = $info['idcard_up'] ? UploadService::getAttachmentUrl($info['idcard_up']) : '';
            $info['idcard_down_pic'] = $info['idcard_down'] ? UploadService::getAttachmentUrl($info['idcard_down']) : '';
            $info['photo_pic'] = $info['photo'] ? UploadService::getAttachmentUrl($info['photo']) : '';
            $info['licence_pic'] = $info['licence'] ? UploadService::getAttachmentUrl($info['licence']) : '';
            $info['shop_img_pic'] = $info['shop_img'] ? UploadService::getAttachmentUrl($info['shop_img']) : '';
            $service = Services::Hprose('Category');
            $sub_category = $service->getByCode($company->getCategory());
            $first_category = $service->getByCode($sub_category['top_category']);
            $info['top_category'] = $first_category['code'];
            $info['top_category_name'] = $first_category['name'];
            $info['sub_category'] = $sub_category['code'];
            $info['sub_category_name'] = $sub_category['name'];

            $info['contract_location'] = "";
            //获取推荐人
            if ($company->getRecommendId()) {
                $recommend_data = RepoCompany::getCompanyById($company->getRecommendId());
                $info['recommend_name'] = $recommend_data->getName();
                $info['recommend'] = $company->getRecommendId();
            } else {
                $info['recommend_name'] = '';
                $info['recommend'] = '';
            }

            return $this->json(self::RESPONSE_OK, $info, '获取企业详细成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取企业的审核状态
     * @throws Exception
     */
    public function company_auditAction()
    {
        $company_id = $this->request->getPost('company_id') ? : (new ApplyService())->getCurrentCompanyId();
        $company = RepoCompany::getCompanyById($company_id);
        if ($company === false) {
            return $this->json(self::RESPONSE_FAILED, null, '获取企业基本信息失败');
        }
        return $this->json(
            self::RESPONSE_OK,
            ['audit' => $company->getAuditing(),
                'audit_name' => RepoCompany::getAuditName($company->getAuditing())],
            '获取企业审核状态成功'
        );
    }

    /**
     * 提交补录信息
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function reApplyAction()
    {
        $company_id = $this->request->getPost("company_id");

        try {
            $this->db->begin();
            $company = RepoCompany::getCompanyById($company_id);
            if ($company->getAuditing() != RepoCompany::AUDIT_REVOKED) {
                throw new Exception('该企业尚未被驳回，无需提交补录申请');
            }
            $type = $_POST['type'] = $company->company_info->getType();
            $data = $this->getCompanyData();
            $validation = new ReApplyForm(null, ['company_type' => $type, 'form_type' => self::TYPE_RE]);
            if ($validation->isValid($data) == false) {
                throw new Exception($validation->getMessages()[0].'form');
            }
            (new RepoCompanyInfo())->reApplyCompanyInfo($company, $data);
            //修改上一个驳回的企业的状态dh20170922
            $last_verify = CompanyVerify::getLastCompanyVerify(
                $company_id,
                CompanyVerify::TYPE_DOCUMENTS,
                CompanyVerify::STATUS_FAIL
            );
            if ($last_verify) {
                $last_verify->setStatus(CompanyVerify::STATUS_RE_APPLY);
                $last_verify->save();
            }

            $this->db->commit();

            return $this->json(self::RESPONSE_OK, [$company->getId()], '补录信息提交成功');
        } catch (ModelException $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage().'ModelException');
        } catch (Exception $exception) {
            $this->db->rollback();
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage().'Exception');
        }
    }

    public function getBankListAction()
    {
        $list = RepoBankList::getList();

        return $this->json(self::RESPONSE_OK, $list, '获取银行列表成功');
    }

    //扫描营业执照获取基本信息
    public function scanAction()
    {
        if ($this->request->isPost()) {
            try {
                /**
                 * @var $company_info CompanyInfo
                 */
                $company_info = Repositories::getRepository('CompanyInfo');
                //判断企业是否存在
                $is_company_info = $company_info->getCompanyInfoByUrl($this->request->getPost('url'));
                if ($is_company_info) {
                    $company_data = (new \Wdxr\Models\Repositories\Company())->ByInfoId($is_company_info->getId());
                    Follow::follow($company_data->getId());
                    $data['name'] = $company_data->getName();
                    $data['type'] = $is_company_info->getType();
                    $data['licence_num'] = $is_company_info->getLicenceNum();
                    $data['legal_name'] = $is_company_info->getLegalName();
                    $data['period'] = $is_company_info->getPeriod();
                    $data['address'] = $is_company_info->getAddress();
                    $data['province'] = $is_company_info->getProvince() ?: '';
                    $data['city'] = $is_company_info->getCity() ?: '';
                    $data['district'] = $is_company_info->getDistrict() ?: '';
                    $data['address_all'] = Regions::getAddress($data['province'], $data['city'], $data['district'], '');
                    $data['scope'] = $is_company_info->getScope();
                    $data['url'] = $is_company_info->getUrl();
                    $data['company_id'] = $company_data->getId();
                    $data['is_follow'] = Follow::checkFollow($data['company_id']);
                    $data['is_settled'] = $company_data->getAuditing() ? '是' : '否';
                    $data['admin'] = $company_data->getDeviceId() ? UserAdmin::getNameByDeviceId($company_data->getDeviceId()) : '无';
                    return $this->json(self::RESPONSE_OK, $data, '扫描成功');
                }
                $url_data = Company::addCompanyFromUrl($this->request->getPost('url'));
                return $this->json(self::RESPONSE_OK, $url_data, '扫描成功');
            } catch (Exception $exception) {
                return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
            }
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }

    //手动输入社会信用代码
    public function inputLicenceNumAction()
    {
        if ($this->request->isPost()) {
            $licence_num = $this->request->getPost('licence_num');
            try {
                /**
                 * @var $company_info CompanyInfo
                 */
                $company_info = Repositories::getRepository('CompanyInfo');
                //判断企业是否存在
                $is_company_info = $company_info->getCompanyInfoByLicenceNum($licence_num);
                if ($is_company_info) {
                    $company_data = (new \Wdxr\Models\Repositories\Company())->ByInfoId($is_company_info->getId());
                    Follow::follow($company_data->getId());
                    $data['name'] = $company_data->getName();
                    $data['type'] = $is_company_info->getType();
                    $data['licence_num'] = $is_company_info->getLicenceNum();
                    $data['legal_name'] = $is_company_info->getLegalName();
                    $data['period'] = $is_company_info->getPeriod();
                    $data['address'] = $is_company_info->getAddress();
                    $data['province'] = $is_company_info->getProvince() ?: '';
                    $data['city'] = $is_company_info->getCity() ?: '';
                    $data['district'] = $is_company_info->getDistrict() ?: '';
                    $data['address_all'] = Regions::getAddress($data['province'], $data['city'], $data['district'], '');
                    $data['scope'] = $is_company_info->getScope();
                    $data['url'] = $is_company_info->getUrl();
                    $data['company_id'] = $company_data->getId();
                    $data['is_follow'] = Follow::checkFollow($data['company_id']);
                    $data['is_settled'] = $company_data->getAuditing() ? '是' : '否';
                    $data['admin'] = $company_data->getDeviceId() ? UserAdmin::getNameByDeviceId($company_data->getDeviceId()) : '无';
                    return $this->json(self::RESPONSE_OK, $data, '公司基本信息获取成功');
                }
                return $this->json(self::RESPONSE_FAILED, '', '企业未找到');
            } catch (Exception $exception) {
                return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
            }
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }

    //手写输入基本信息
    public function manualInputAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form = new ApplyManualInputForm();
            try {
                $valid = $form->isValid($this->request->getPost());
                if ($valid == false) {
                    throw new Exception($form->getMessages()[0]);
                }
                /**
                 * @var $company_info CompanyInfo
                 */
                $company_info = Repositories::getRepository('CompanyInfo');
                //判断企业是否存在
                $is_company_info = $company_info->getCompanyInfoByLicenceNum($data['licence_num']);
                if ($is_company_info) {
                    $company = (new \Wdxr\Models\Repositories\Company())->ByInfoId($is_company_info->getId());
                    if ($company === false) {
                        throw new Exception('对应的企业ID错误');
                    }
                    $data['company_id'] = $company->getId();
                    $data['is_follow'] = Follow::checkFollow($data['company_id']);
                    $data['is_settled'] = $company->getAuditing() ? '是' : '否';
                    $data['admin'] = $company->getDeviceId() ? UserAdmin::getNameByDeviceId($company->getDeviceId()) : '无';
                    return $this->json(self::RESPONSE_OK, $data, '公司基本信息保存成功');
                }
                $company_id = Company::manualAddCompanyForm($data);
                $company = (new \Wdxr\Models\Repositories\Company())->getById($company_id);
                $data['company_id'] = $company_id;
                Follow::follow($company_id);
                $data['is_follow'] = Follow::checkFollow($company_id);
                $data['is_settled'] = $company->getAuditing() ? '是' : '否';
                $data['admin'] = $company->getDeviceId() ? UserAdmin::getNameByDeviceId($company->getDeviceId()) : '无';
            } catch (Exception $exception) {
                return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
            }
            return $this->json(self::RESPONSE_OK, $data, '公司基本信息保存成功');
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }

    //关注企业
    public function followAction()
    {
        if ($this->request->isPost()) {
            $company_id = $this->request->getPost('company_id');
            try {
                Follow::follow($company_id);
            } catch (InvalidRepositoryException $exception) {
                return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
            }
            return $this->json(self::RESPONSE_OK, '', '关注成功');
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }

    //取消关注
    public function unfollowAction()
    {
        if($this->request->isPost()){
            $company_id = $this->request->getPost('company_id');
            try {
                Follow::unfollow($company_id);
            } catch (InvalidRepositoryException $exception) {
                return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
            }
            return $this->json(self::RESPONSE_OK, '', '取消关注成功');
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }

    //关注列表
    public function followListAction()
    {
        if($this->request->isPost()){
            $page = $this->request->getPost('page');
            if(!$page){
                $page = 1;
            }
            $limit = ($page-1)*10;
            try {
                $list = Follow::followList();
            } catch (InvalidRepositoryException $exception) {
                return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
            }
            //分页
            $list = array_slice($list,$limit,10);
            return $this->json(self::RESPONSE_OK, $list, '获取列表成功');
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }


    //获取推荐人列表
    public function getRecommendAction()
    {
        if($this->request->isPost()) {
        try{
            $name = $this->request->getPost('name') ? "company.name like '%".$this->request->getPost('name')."%' or info.legal_name like '%".$this->request->getPost('name')."%' " : '1=1';
            $user_id = JWT::getUid();
            $user_admin = UserAdmin::getUser($user_id);
            if ($user_admin->getType() == UserAdmin::TYPE_ADMIN) {
                $array[0] = array('id'=>'','name'=>'无','legal_name'=>'');
                $company_data = (new \Wdxr\Models\Repositories\Company())->getAdminIdCompany($user_admin->getUserId(),$name);
                return $this->json(self::RESPONSE_OK, $company_data ? array_merge($array,$company_data->toArray()) : null, '获取列表成功');
            } else {
                $company_data = (new \Wdxr\Models\Repositories\Company())->getCompanyByUserId($user_admin->getUserId());
                if($company_data){
                    //获取合伙人本人和他下级所有的企业集合
                    $all_recommend_company_data = (new \Wdxr\Models\Repositories\Company())->getAllRecommendCompany($company_data->getId());
                    if($all_recommend_company_data){
                        $all_recommend_array = $all_recommend_company_data->toArray();
                        foreach($all_recommend_array as $key=>$val){
                            $all_recommend_array[$key]['legal_name'] = (new RepoCompanyInfo)->getCompanyInfo($val['info_id'])->getLegalName();
                        }
                        $data = $all_recommend_array;
                    }else{
                        $data = null;
                    }
                    /*$data[0]['id'] = $company_data->getId();
                    $data[0]['name'] = $company_data->getName();
                    $data[0]['legal_name'] = (new RepoCompanyInfo)->getCompanyInfo($company_data->getInfoId())->getLegalName();*/
                }else{
                    $data = null;
                }
                return $this->json(self::RESPONSE_OK, $data, '获取列表成功');
            }
        }catch(InvalidRepositoryException $exception){
            return $this->json(self::RESPONSE_FAILED, null, '获取列表成功');
        }
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }

    //获取管理人列表
    public function getManagerAction()
    {
        if($this->request->isPost()) {
            try{
            $id = $this->request->getPost('id');
            $company_data = (new \Wdxr\Models\Repositories\Company())->getByIdNew($id);
            $data = null;
            if($company_data){
                $recommend_data = (new \Wdxr\Models\Repositories\Company())->getById($company_data->getRecommendId());
                if($recommend_data){
                    $data['id'] = $recommend_data->getId();
                    $data['name'] = $recommend_data->getName();
                    $data['legal_name'] = (new RepoCompanyInfo)->getCompanyInfo($recommend_data->getInfoId())->getLegalName();
                }
            }
                return $this->json(self::RESPONSE_OK, $data, '获取列表成功');
            }catch(InvalidRepositoryException $exception){
                return $this->json(self::RESPONSE_OK, null, '获取列表成功');
            }
        }
        return $this->json(self::RESPONSE_FAILED, null, '非法访问');
    }


}