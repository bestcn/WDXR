<?php
namespace Wdxr\Models\Services;

use Lcobucci\JWT\JWT;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Entities\Contracts;
use Wdxr\Models\Exception\ModelException;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\Contract as RepoContract;
use Wdxr\Models\Repositories\Devices;
use Wdxr\Models\Repositories\Regions as RepoRegions;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\CompanyInfo as RepoCompanyInfo;
use Wdxr\Models\Repositories\CompanyRecommend as RepoCompanyRecommend;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;
use Wdxr\Models\Repositories\CompanyReport as RepoCompanyReport;
use Wdxr\Models\Services\Contract as ServiceContract;
use Wdxr\Models\Repositories\CompanyBank;
use Wdxr\Models\Services\CompanyRecommends;
use Wdxr\Models\Repositories\CompanyPayment as RepoCompanyPayment;

class ApplyService extends Services
{

    private function getTokenKey()
    {
        if (!(new UserAuth())->getTokenKey()) {
            throw new InvalidServiceException('未登录');
        }
        return (new UserAuth())->getTokenKey();
    }

    /**
     * 当前企业ID
     * @return bool
     */
    public function delCurrentCompanyId()
    {
        return self::getRedis()->delete('apply_company_'.$this->getTokenKey().'_id');
    }

    public function getCurrentCompanyId($company_id = null)
    {
        $company_id = $company_id ? : self::getRedis()->get('apply_company_'.$this->getTokenKey().'_id');
        if (is_null($company_id)) {
            throw new InvalidServiceException("当前企业标示丢失或未确定企业标示");
        }
        return $company_id;
    }

    public function setCurrentCompanyId($company_id)
    {
        return self::getRedis()->save('apply_company_'.$this->getTokenKey().'_id', $company_id, -1);
    }

    private function getCompanyContractIdKey()
    {
        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_contract_id';
    }

    public function setCompanyContractId($contract_id)
    {
        return self::getRedis()->save($this->getCompanyContractIdKey(), $contract_id, -1);
    }

    public function getCompanyContractId()
    {
        return self::getRedis()->get($this->getCompanyContractIdKey());
    }

    /**
     * 某一个企业当前进行到第几步
     * @param null $company_id
     * @return string
     * @throws InvalidServiceException
     */
    private function getRedisCurrentStepKey($company_id = null)
    {
        $company_id || $company_id = $this->getCurrentCompanyId();
        if (!$company_id) {
            throw new InvalidServiceException('企业ID参数错误');
        }
        //多设备修改dh20180322
//        return 'apply_company_'.$this->getTokenKey().'_'.$company_id.'_step';
        return 'apply_company_'.JWT::getUid().'_'.$company_id.'_step';
    }

    public function getCurrentStep($company_id = null)
    {
        return self::getRedis()->get($this->getRedisCurrentStepKey($company_id));
    }

    public function setCurrentStep($step)
    {
        return self::getRedis()->save($this->getRedisCurrentStepKey($this->getCurrentCompanyId()), $step, -1);
    }

    /**
     * 每一步是否完成
     * @param $step
     * @return string
     */
    private function getRedisStepCompleteKey($step)
    {
        //多设备修改dh20180322
//        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_complete-'.$step;
        return 'apply_company_'.JWT::getUid().'_'.$this->getCurrentCompanyId().'_complete-'.$step;
    }

    public function setStepComplete($step, $is_complete)
    {
        return self::getRedis()->save($this->getRedisStepCompleteKey($step), $is_complete, -1);
    }

    public function isStepComplete($step)
    {
        return self::getRedis()->get($this->getRedisStepCompleteKey($step));
    }

    /**
     * 每一步数据
     * @param $step
     * @return string
     */
    private function getRedisStepDataKey($step)
    {
        //多设备修改dh20180322
//        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'-'.$step;
        return 'apply_company_'.JWT::getUid().'_'.$this->getCurrentCompanyId().'-'.$step;
    }

    public function setCompanyStepData($step, $data)
    {
        //多设备修改dh20180322
//        if(isset($data['token'])) unset($data['token']);
        return self::getRedis()->save($this->getRedisStepDataKey($step), $data, -1);
    }

    public function getCompanyStepData($step)
    {
        $data = self::getRedis()->get($this->getRedisStepDataKey($step));
        $params = $this->getStepParams($step);
        $r_data = [];
        foreach ($params as $param) {
            $param_array = explode('|', $param);
            $param = $param_array[0];
            if (isset($data[$param]) === false) {
                $r_data[$param] = '';
                if (isset($param_array[1])) {
                    $r_data[$param] = $param_array[1];
                }
                continue;
            }
            $r_data[$param] = $data[$param];
            if ($this->hasAttachment($param) && $data[$param]) {
                $images = [];
                if (strpos($data[$param], ',') !== false) {
                    $items = explode(',', trim($data[$param], ','));
                    foreach ($items as $value) {
                        array_push($images, UploadService::getAttachmentUrl($value));
                    }
                } elseif (is_array($data[$param])) {
                    foreach ($data[$param] as $value) {
                        array_push($images, UploadService::getAttachmentUrl($value));
                    }
                } else {
                    $images = UploadService::getAttachmentUrl($data[$param]);
                }
                $r_data[$param."_image"] = $images;
            }
            if ((strpos($param, 'province') !== false && $data[$param])
                || (strpos($param, 'city') !== false && $data[$param])
                || (strpos($param, 'district') !== false && $data[$param])
            ) {
                $r_data[$param."_data"] = RepoRegions::getRegionName($data[$param])->name ? : '';
            }
            if ((strpos($param, 'top_category') !== false && $data[$param])
                || (strpos($param, 'sub_category') !== false && $data[$param])
            ) {
                $service = Services::Hprose('Category');
                $top_category = $service->getByCode($data[$param]);
                $r_data[$param."_name"] = $top_category['name'];
            }
            if (strpos($param, 'recommend') !== false && $data[$param]) {
                $company_data = (new \Wdxr\Models\Repositories\Company())->Byid($data[$param]);
                if ($company_data) {
                    $r_data[$param . "_name"] = $company_data->getName();
                }
            }
        }

        return $r_data;
    }

    //获取对应步数的字段 图片应在图片ID之前
    public function getStepParams($step)
    {
        switch ($step) {
            case 1:
                return ['name', 'type', 'province', 'city', 'district', 'address', 'legal_name', 'scope', 'period', 'period', 'intro', 'account_permit_image','account_permit',  'credit_code_image', 'credit_code', 'licence_image', 'licence', 'licence_num'];
            case 2:
                return ['type|'.$this->getCompanyType(), 'idcard_up_image', 'idcard_up', 'idcard_down_image', 'idcard_down', 'photo_image', 'photo','shop_img_image','shop_img' , 'idcard', 'contacts', 'contact_title', 'contact_phone','top_category','sub_category', 'recommend', 'recommend_id', 'verify_code', 'zipcode'];
            case 3:
                return ['bank_type', 'bankcard_photo_image', 'bankcard_photo', 'bankcard', 'bank_province', 'bank_city', 'bank_name', 'bank', 'work_bankcard_photo_image', 'work_bankcard_photo', 'work_bankcard', 'work_bank_province', 'work_bank_city', 'work_bank_name', 'work_bank','account_holder','work_account_holder'];
            default:
                return [];
        }
    }

    /**
     * 企业类型
     * @param null $company_id
     * @return string
     */
    public function getRedisTypeKey($company_id = null)
    {
        //多设备修改dh20180322
//        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId($company_id).'_type';
        return 'apply_company_'.JWT::getUid().'_'.$this->getCurrentCompanyId($company_id).'_type';
    }

    public function setCompanyType($type)
    {
        return self::getRedis()->save($this->getRedisTypeKey(), $type, -1);
    }

    public function getCompanyType($company_id = null)
    {
        return self::getRedis()->get($this->getRedisTypeKey($company_id));
    }

    /**
     * 全部数据
     * @return string
     */
    private function getRedisDataKey()
    {
        //多设备修改dh20180322
//        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_data';
        return 'apply_company_'.JWT::getUid().'_'.$this->getCurrentCompanyId().'_data';
    }

    public function setCompanyData($data)
    {
        return self::getRedis()->save($this->getRedisDataKey(), $data, -1);
    }

    public function getCompanyData()
    {
        return self::getRedis()->get($this->getRedisDataKey());
    }

    /**
     * 地理位置信息
     * @return string
     */
    private function getRedisGeoKey()
    {
        //多设备修改dh20180322
//        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_geo';
        return 'apply_company_'.JWT::getUid().'_'.$this->getCurrentCompanyId().'_geo';
    }

    public function setCompanyGeo($address)
    {
        return self::getRedis()->save($this->getRedisGeoKey(), $address, -1);
    }

    public function getCompanyGeo()
    {
        return self::getRedis()->get($this->getRedisGeoKey());
    }

    /**
     * 签名附件ID
     * @return string
     */
    private function getRedisSignKey()
    {
        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_sign';
    }

    public function setSignId($sign_id)
    {
        if(!$sign_id) {
            throw new InvalidServiceException('请上传签名ID');
        }
        return self::getRedis()->save($this->getRedisSignKey(), $sign_id, -1);
    }

    public function getSignId()
    {
        return self::getRedis()->get($this->getRedisSignKey());
    }

    /**
     * 短信发送状态
     * @return string
     */
    private function getRedisSmsKey()
    {
        //多设备修改dh20180322
//        return 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_sms';
        return 'apply_company_'.JWT::getUid().'_'.$this->getCurrentCompanyId().'_sms';
    }

    public function setCompanySmsStatus($phone)
    {
        return self::getRedis()->save($this->getRedisSmsKey(), $phone, -1);
    }

    public function getCompanySmsStatus()
    {
        return self::getRedis()->get($this->getRedisSmsKey());
    }

    /**
     * 验证手机号发送短信的状态，在申请流程中已经验证过的手机号不需要重复验证
     * @param $phone
     * @return bool
     */
    public function verifyCompanySmsStatus($phone)
    {
        return $phone ? $this->getCompanySmsStatus() == $phone : false;
    }

    /**
     * 获取未完成企业列表
     * @return array
     */
    public function getApplyList()
    {
        //多设备修改dh20180322
//        $key = 'apply_company_'.$this->getTokenKey().'_list';
        $key = 'apply_company_'.JWT::getUid().'_list';
        $keys = self::getRedis()->queryKeys($key);
        $apply = [];
        if(is_array($keys)) {
            foreach ($keys as $key) {
                //正在申请中的企业不允许其他业务员介入
                $value = self::getRedis()->get($key);
                if(Devices::getByToken($value['token']) && $value){
                    $value['step'] = $this->getCurrentStep($value['id']);
                    $value['date'] = $value['time'];
                    $value['time'] = TimeService::humanTime($value['time']);
                    $value['type'] = $this->getCompanyType($value['id']);
                    $apply[] = $value;
                }
            }
        }

        return $apply;
    }


    /**
     * 将企业加入未完成企业列表
     * @param $company_id
     * @return bool
     */
    public function pushApplyCompany($company_id)
    {
        //多设备修改dh20180322
//        $key = 'apply_company_'.$this->getTokenKey().'_list-'.$company_id;
        $token = (new UserAuth())->getToken();
        $key = 'apply_company_'.JWT::getUid().'_list-'.$company_id;
        if(self::getRedis()->exists($key)) {
            return true;
        }
        $company = RepoCompany::getCompanyById($company_id);
        //$is_partner = RepoCompanyPayment::isPaymentLoan($company_id) ? 0 : 1;
        $is_partner = 0;
        $apply = ['id' => $company_id, 'name' => $company->getName(), 'time' => time(), 'is_partner' => $is_partner,'token'=>$token];
        return self::getRedis()->save($key, $apply, -1);
    }

    /**
     * 是否是正在申请中的企业
     * @param $company_id
     * @return bool
     */
    public function isApplyCompany($company_id)
    {
        //多设备修改dh20180322
//        $key = 'apply_company_'.$this->getTokenKey().'_list-'.$company_id;
        $key = 'apply_company_'.JWT::getUid().'_list-'.$company_id;
        if (self::getRedis()->exists($key)) {
            return true;
        }
        return false;
    }

    /**
     * 从未完成企业列表中将企业移除
     * @param $company_id
     * @return bool
     * @throws InvalidServiceException
     */
    public function popApplyCompany($company_id)
    {
        //多设备修改dh20180322
//        $key = 'apply_company_'.$this->getTokenKey().'_list-'.$company_id;
        $key = 'apply_company_'.JWT::getUid().'_list-'.$company_id;
        //检测该企业是否正在处理当中
        if(!$this->checkRidesToken($key)){
            throw new InvalidServiceException('其他业务员正在操作该企业');
        }
        if($this->getRedis()->exists($key) === false) {
            throw new InvalidServiceException('未完成的企业列表中不存在该企业');
        }
        if($this->getRedis()->delete($key) === false) {
            throw new InvalidServiceException('删除未完成的企业失败');
        }
        //删除该企业的所有相关缓存
        //多设备修改dh20180322
//        $prefix = 'apply_company_'.$this->getTokenKey().'_'.$company_id;
        $prefix = 'apply_company_'.JWT::getUid().'_'.$company_id;
        $keys = self::getRedis()->queryKeys($prefix);
        foreach ($keys as $key) {
            self::getRedis()->delete($key);
        }
        return true;
    }

    /**
     * 保存第一步数据
     * @param $data
     * @return bool
     * @throws InvalidServiceException
     */
    public function one($data)
    {
        $company_id = $this->getCurrentCompanyId();
        $company = RepoCompany::getCompanyById($company_id);
        $this->applyStatus($company);
        $this->setCurrentCompanyId($company_id);

        if ($this->setCompanyStepData(1, $data) === false) {
            throw new InvalidServiceException('公司基本信息保存失败');
        }
        $this->pushApplyCompany($company->getId());
        $this->setCompanyType($data['type']);

        $this->setStepComplete(1, 1);
        $this->setCurrentStep(2);

        return true;
    }

    public function two($data)
    {
        $data['type'] = $this->getCompanyType();
        $company_id = $this->getCurrentCompanyId();
        //推荐人或企业
        if (($recommend = CompanyRecommends::getRecommendId($company_id, $data['recommend'])) !== false) {
            $data = array_merge($data, ['recommend' => $recommend]);
        }
        //判断手机验证码
        if ($this->verifyCompanySmsStatus($data['contact_phone']) === false) {
            if (SMS::verifyPhone($data['verify_code'], $data['token']) === false) {
                throw new InvalidServiceException('手机验证码错误');
            } else {
                $this->setCompanySmsStatus($data['contact_phone']);
            }
        }

        if ($this->setCompanyStepData(2, $data) === false) {
            throw new InvalidServiceException('公司信息保存失败');
        }

        $this->setStepComplete(2, 1);
        $this->setCurrentStep(2);

        return true;
    }

    public function three($data)
    {
        if($this->setCompanyStepData(3, $data) === false) {
            throw new InvalidServiceException('公司信息保存失败');
        }

        $this->setStepComplete(3, 1);
        $this->setCurrentStep(2);

        return true;
    }

    /**
     * @return array
     * @throws InvalidServiceException
     */
    public function getAllData()
    {
        if(($step = $this->checkComplete()) !== true) {
            $step = implode(',', $step);
            throw new InvalidServiceException("第{$step}尚未完成");
        }
        $data_1 = $this->getCompanyStepData(1);
        $data_2 = $this->getCompanyStepData(2);
//        $data_3 = $this->getCompanyStepData(3);
//        $data_4 = $this->getCompanyStepData(4);

        $company = array_merge($data_1, $data_2);//, $data_3
        $company['company_id'] = $this->getCurrentCompanyId();

        if($this->setCompanyData($company) === false) {
            throw new InvalidServiceException('企业信息合并失败，请重试');
        }

        return $company;
    }

//    public function four($data)
//    {
//        $this->setStepComplete(4, 1);
//        $this->setCurrentStep(4);
//
//        $contract = RepoContract::getLastContractNum(JWT::getUid());
//        $data['contract_num'] = $contract->getContractNum();
//        $data['contract_id'] = $contract->getId();
//        if($this->setCompanyStepData(4, $data) === false) {
//            throw new InvalidServiceException('合同信息保存失败');
//        }
//
//        return true;
//    }

    public function tempSave($step, $data)
    {
        $this->setStepComplete($step, 0);
        $this->setCurrentStep($step);
        unset($data['step']);
        if($this->setCompanyStepData($step, $data) === false) {
            throw new InvalidServiceException('保存失败');
        }
        $this->pushApplyCompany($this->getCurrentCompanyId());
        return true;
    }

    /**
     * 检查第几步没有完成
     * @return bool|array
     */
    public function checkComplete()
    {
        $current = $this->getCurrentStep();
        if(is_null($current)) {
            return false;
        }
        $step = 1;
        $un_complete = [];
        while ($step < 3) {//由原来的4改为3,舍去银行卡步骤
            if($this->isStepComplete($step) == 0) {
                array_push($un_complete, $step);
            }
            $step++;
        }
        return empty($un_complete) ? : $un_complete;
    }

    /**
     * 提交申请信息
     * @return Contracts
     * @throws InvalidServiceException
     * @throws ModelException
     */
    public function submitApply()
    {
        if (($step = $this->checkComplete()) !== true) {
            $step = implode(',', $step);
            throw new InvalidServiceException("第{$step}尚未完成");
        }
        $company_id = $this->getCurrentCompanyId();
        $company = RepoCompany::getCompanyById($company_id);
        $this->applyStatus($company);
        $data = $this->getAllData();

        $info_id = RepoCompanyInfo::addCompanyInfoData($company->getInfoId(), $data);
        //添加推荐记录
        if ($data['recommend']) {
            $recommend_id = intval($data['recommend']);
            RepoCompanyRecommend::addNew($recommend_id, $company_id, JWT::getUid());
            $company->setRecommendId($recommend_id);
        }
        $company->setAuditing(RepoCompany::AUDIT_APPLY);
        $company->setCategory($data['sub_category']);
        $company->setInfoId($info_id);
        $company->setDeviceId(JWT::getUid());
        $company->setAdminId(UserAdmin::getAdminId(JWT::getUid()));
        $company->setCategory($data['sub_category']);
        if (!$company->save()) {
            throw new ModelException("企业信息保存失败");
        }
        //添加审核信息
        return RepoCompanyVerify::newVerify($company_id, JWT::getUid(), RepoCompanyVerify::TYPE_DOCUMENTS, $info_id);
    }

    /**
     * 提交申请信息
     * @return Contracts
     * @throws InvalidServiceException
     * @throws ModelException
     */
    public function submitApplyInfo($company_id,$data)
    {
        $company = RepoCompany::getCompanyById($company_id);
        if($company->getAuditing() != RepoCompany::AUDIT_NOT) {
            throw new InvalidServiceException("该企业已经申请审核了");
        }
//        $contract = RepoContract::getLastContractNum($data['device_id'], $company_id, 114.475785, 38.0308436);
//        RepoContract::saveContractLocation($contract->getId(),$data['location']);
//        $data['contract_num'] = $contract->getContractNum();
        //添加企业详细信息**dh修改不再创建企业证件信息记录,在扫描时已经存在
        $info_id = RepoCompanyInfo::addCompanyInfo($company->getInfoId(),$data);
        //添加票据、征信记录
        $master_data['bankcard_photo']=$data['bankcard_photo'];
        $master_data['bank_type']=$data['bank_type'];
        $master_data['number']=$data['bankcard'];
        $master_data['bank']=$data['bank'];
        $master_data['province']=$data['bank_province'];
        $master_data['city']=$data['bank_city'];
        $master_data['address']=$data['bank_name'];
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
            CompanyBank::saveCompanyBank($company_id,$master_data,CompanyBank::CATEGORY_WORK);
        }
        //修改企业信息
        $company->setAuditing(RepoCompany::AUDIT_APPLY);
        $company->setCategory($data['sub_category']);
        $company->setInfoId($info_id);
        $company->setDeviceId($data['device_id']);
        $company->setAdminId($data['admin_id']);
        if(!$company->save()) {
            throw new InvalidServiceException("企业信息保存失败");
        }
        //添加审核信息
        return RepoCompanyVerify::newVerify($company_id, $data['device_id'], RepoCompanyVerify::TYPE_DOCUMENTS, $info_id);
    }

    /**
     * 判断企业状态
     * @param EntityCompany $company
     * @return bool
     * @throws InvalidServiceException
     */
    public function applyStatus(EntityCompany $company)
    {
        if($company->getAuditing() != RepoCompany::AUDIT_NOT) {
            $this->deleteCompanyCache($company->getId());
            throw new InvalidServiceException('该企业已经提交申请或已经通过审核');
        }

        return true;
    }

    public function deleteCompanyCache($company_id = null)
    {
        $prefix = 'apply_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId($company_id);
        $keys = self::getRedis()->queryKeys($prefix);
        foreach ($keys as $key) {
            self::getRedis()->delete($key);
        }
        $this->popApplyCompany($this->getCurrentCompanyId($company_id));
        $this->delCurrentCompanyId();
        return true;
    }

    /**
     * @param $param
     * @return bool
     */
    public function hasAttachment($param)
    {
        return in_array($param, [
            'licence', 'account_permit', 'credit_code', 'idcard_up', 'idcard_down', 'photo','shop_img'
        ]);
    }

    /**
     * @param $key
     * @return bool
     */
    private function checkRidesToken($key)
    {
        $rides = self::getRedis()->get($key);
        $device = Devices::getByToken($rides['token']);
        return $device;
    }

    /**
     * @param $key
     * 将临时保存的企业信息绑定正在操作的用户
     */
    public function binding($company_id)
    {
        $key = 'apply_company_'.JWT::getUid().'_list-'.$company_id;
        $rides = self::getRedis()->get($key);
        if($rides) {
            if(Devices::getByToken($rides['token']) === false) {
                return false;
            }
            $rides['token'] = (new UserAuth())->getToken();
            if(self::getRedis()->save($key,$rides,3600)){
                return true;
            }
            return false;
        }
        return true;
    }


}