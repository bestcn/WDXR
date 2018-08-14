<?php
namespace Wdxr\Models\Services;

use Phalcon\Exception;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Entities\ContractLog;
use Wdxr\Models\Entities\Contracts;
use Wdxr\Models\Repositories\CompanyReport;
use Wdxr\Models\Repositories\Devices;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\LoansInfo;
use Wdxr\Models\Repositories\Loan as RepLoan;
use Wdxr\Models\Repositories\CompanyVerify as RepCompanyVerify;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Repositories\Repositories;
use Wdxr\Models\Repositories\Temp;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Entities\CompanyPayment as EntityCompanyPayment;
use Wdxr\Models\Exception\ModelException;
use Wdxr\Models\Repositories\Level;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyPayment;
use Wdxr\Models\Repositories\CompanyBank;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Models\Services\TimeService;
use Lcobucci\JWT\JWT;
use Wdxr\Modules\Admin\Controllers\FinanceController;
use Wdxr\Modules\Admin\Forms\PresentationForm;
use Wdxr\Models\Repositories\Contract;
use Wdxr\Models\Repositories\BonusList;

class Loan extends Services
{
    const VERIFIED = -1;  //状态为电话号码已验证
    //申请状态
    const STATUS_NEW = -1;         //当前状态为新建

    const STATUS_DISABLE = 0;       //未定义
    const STATUS_UNFINISHED = 1;    //未完成
    const STATUS_UNREVIEWED= 2;     //未审核
    const STATUS_REJECT = 3;        //已驳回
    const STATUS_ADOPT = 4;         //已通过
    const STATUS_FAIL = 5;            //已通过银行驳回
    const STATUS_OK = 6;         //已通过银行通过驳回
    const STATUS_RE_APPLY = 7;   //已处理的驳回信息



    private function getTokenKey()
    {
        if(!(new UserAuth())->getTokenKey()) {
            throw new InvalidServiceException('未登录');
        }
        return (new UserAuth())->getTokenKey();
    }

    /**
     * 获取当前Token
     * @return string
     */
    private function getToken()
    {
        if(!(new UserAuth())->getToken()) {
            throw new InvalidServiceException('未登录');
        }
        return (new UserAuth())->getToken();
    }

    /**
     * 从未完成企业列表中将企业移除
     * @param $company_id
     * @return bool
     * @throws InvalidServiceException
     */
    public function popApplyCompany($company_id)
    {
        $device_id = JWT::getUid();
        $key = 'Loan_company_'.$device_id.'_list-'.$company_id;
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
        $prefix = 'Loan_company_'.$device_id.'_'.$company_id;
        $keys = self::getRedis()->queryKeys($prefix);
        foreach ($keys as $key) {
            self::getRedis()->delete($key);
        }
        $this->delCompanySmsStatus();
        return true;
    }

    /**
     * 删除当前企业ID
     * @return bool
     */
    public function delCurrentCompanyId()
    {
        return self::getRedis()->delete('Loan_company_'.$this->getTokenKey().'_id');
    }

    //删除申请信息缓存
    public function deleteCompanyCache($company_id = null)
    {
        $device_id = JWT::getUid();
        $prefix = 'Loan_company_'.$device_id.'_'.$this->getCurrentCompanyId($company_id);
        $keys = self::getRedis()->queryKeys($prefix);
        foreach ($keys as $key) {
            self::getRedis()->delete($key);
        }
        $this->popApplyCompany($this->getCurrentCompanyId($company_id));
        $this->delCurrentCompanyId();
        return true;

    }

    public function one($data){
        $uid = JWT::getUid();
        $company = Company::getCompanyById($data['company_id']);
        $data['device_id'] = $uid;
        $data['state'] = self::STATUS_UNFINISHED;
        $data["step"]=1;
        $this->setCurrentCompanyId($company->getId());
        if($this->setLoanStepData(1, $data) === false) {
            throw new InvalidServiceException('公司基本信息保存失败');
        }
        $this->pushLoanCompany($company->getId());
        $this->setStepComplete(1, 1);
        $this->setCurrentStep(2);
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
     * 短信发送状态
     * @return string
     */
    private function getRedisSmsKey()
    {
        return 'Loan_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_sms';
    }

    public function setCompanySmsStatus($phone)
    {
        return self::getRedis()->save($this->getRedisSmsKey(), $phone, -1);
    }

    public function delCompanySmsStatus()
    {
        return self::getRedis()->delete($this->getRedisSmsKey());
    }

    public function getCompanySmsStatus()
    {
        return self::getRedis()->get($this->getRedisSmsKey());
    }

    //保存电话通过验证状态
     public function setTelVerify($tel){
        $key = 'loan'.$this->getTokenKey().'info'.$this->getCurrentCompanyId().'tel'.$tel;
         return self::getRedis()->save($key,self::VERIFIED,-1);
    }



    /**
     * 将企业加入未完成企业列表
     * @param $company_id
     * @return bool
     */
    public function pushLoanCompany($company_id)
    {
        $device_id = JWT::getUid();
//        $key = 'Loan_company_'.$this->getTokenKey().'_list-'.$company_id;
        $key = 'Loan_company_'.$device_id.'_list-'.$company_id;
        if(self::getRedis()->exists($key)) {
            return true;
        }
        $data = $this->getCompanyStepData(1);
        $loan = ['id' => $company_id, 'name' => $data['name'], 'time' => time(),'token'=>$this->getToken()];
        return self::getRedis()->save($key, $loan, -1);
    }

    /**
     * 替换未完成企业列表Token
     * @param $company_id
     * @return bool
     */
    public function replaceLoanCompany($company_id)
    {
        $device_id = JWT::getUid();
        $key = 'Loan_company_'.$device_id.'_list-'.$company_id;
        if(!$this->checkRidesToken($key)){
            throw new InvalidServiceException("其他业务员正在操作该企业");
        }
        $data = $this->getCompanyStepData(1);
        $loan = ['id' => $company_id, 'name' => $data['name'], 'time' => time(),'token'=>$this->getToken()];
        return self::getRedis()->save($key, $loan, -1);

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
        if(!$company_id) {
            throw new InvalidServiceException('企业ID参数错误');
        }
        $device_id = JWT::getUid();
//        return 'Loan_company_'.$this->getTokenKey().'_'.$company_id.'_step';
        return 'Loan_company_'.$device_id.'_'.$company_id.'_step';
    }

    /**
     * 每一步是否完成
     * @param $step
     * @return string
     */
    private function getRedisStepCompleteKey($step)
    {
//        return 'Loan_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'_complete-'.$step;
        $device_id = JWT::getUid();
        return 'Loan_company_'.$device_id.'_'.$this->getCurrentCompanyId().'_complete-'.$step;
    }

    //保存步骤是否完成
    public function setStepComplete($step, $is_complete)
    {
        return self::getRedis()->save($this->getRedisStepCompleteKey($step), $is_complete, -1);
    }

    //保存当前步骤
    public function setCurrentStep($step)
    {
        return self::getRedis()->save($this->getRedisCurrentStepKey($this->getCurrentCompanyId()), $step, -1);
    }

    public function isStepComplete($step)
    {
        return self::getRedis()->get($this->getRedisStepCompleteKey($step));
    }

    /**
     * 获取每一步数据
     * @param $step
     * @return string
     */
    private function getRedisStepDataKey($step)
    {
        $device_id =JWT::getUid();
//        return 'Loan_company_'.$this->getTokenKey().'_'.$this->getCurrentCompanyId().'-'.$step;
        return 'Loan_company_'.$device_id.'_'.$this->getCurrentCompanyId().'-'.$step;
    }

    //获取企业标识
    public function getCurrentCompanyId($company_id = null)
    {
        $company_id = $company_id ? : self::getRedis()->get('Loan_company_'.$this->getTokenKey().'_id');
        if(is_null($company_id)) {
            throw new InvalidServiceException("当前企业标示丢失或未确定企业标示");
        }
        return $company_id;
    }

    //设置企业id为标识
    public function setCurrentCompanyId($company_id){
        return self::getRedis()->save('Loan_company_'.$this->getTokenKey().'_id',$company_id,-1);
    }

    //保存每步信息
    public function setLoanStepData($step, $data)
    {
        if (isset($data['token'])) {
            unset($data['token']);
        }
        $data_token = self::getRedis()->get($this->getRedisStepDataKey($step));
        if ($data_token === false ||
            $data_token['token'] == $this->getToken() ||
            Devices::findFirstByToken($data_token['token']) === false) {
            $data['token'] = $this->getToken();
            return self::getRedis()->save($this->getRedisStepDataKey($step), $data, -1);
        } else {
            throw new InvalidServiceException('当前企业信息已在其他设备提交');
        }
    }

    public function getCurrentStep($company_id = null)
    {
        return self::getRedis()->get($this->getRedisCurrentStepKey($company_id));
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
        while ($step < 3) {
            if($this->isStepComplete($step) == 0) {
                array_push($un_complete, $step);
            }
            $step++;
        }
        return empty($un_complete) ? : $un_complete;
    }

    //获取对应步数的字段 图片应在图片ID之前
    public function getStepParams($step)
    {
        switch ($step) {
            case 1:
                return ['tel', 'name', 'sex', 'identity', 'company_id', 'province', 'city', 'area', 'address', 'business','company_name','licence_num'];
            case 2:
                return ['money', 'term','level_id'];
            case 3:
                return ['tel', 'name', 'sex', 'identity', 'company_id', 'province', 'city', 'area', 'address', 'business', 'company_name', 'licence_num', 'money', 'term', 'level_id'];
            default:
                return [];
        }
    }

    public function getCompanyStepData($step)
    {
        $data = self::getRedis()->get($this->getRedisStepDataKey($step));
//        $params = $this->getStepParams($step);
        return $data;
    }


    public function filter($res)
    {
        if($res["term"]=="1"){
            $res["term"]="三个月";
            $res["term_id"]="1";
        }elseif($res["term"]=="2"){
            $res["term"]="六个月";
            $res["term_id"]="2";
        }elseif($res["term"]=="3"){
            $res["term"]="九个月";
            $res["term_id"]="3";
        }elseif($res["term"]=="4"){
            $res["term"]="十二个月";
            $res["term_id"]="4";
        }else{
            $res["term"]="";
            $res["term_id"]="";
        }
        if(!empty($res["province"])){
            $res["province_id"]=$res["province"];
            $res["province"]=Regions::getRegionName($res["province"])?Regions::getRegionName($res["province"])->name:'';
        }else{
            $res["province_id"]="";
            $res["province"]="";
        }
        if($res["city"]){
            $res["city_id"]=$res["city"];
            $res["city"]=Regions::getRegionName($res["city"])?Regions::getRegionName($res["city"])->name:'';
        }else{
            $res["city_id"]='';
            $res["city"]='';
        }
        if($res["area"]){
            $res["area_id"]=$res["area"];
            $res["area"]=Regions::getRegionName($res["area"])?Regions::getRegionName($res["area"])->name:'';
        }else{
            $res["area_id"]='';
            $res["area"]='';
        }
        if(is_null($res["money"]) || empty($res["money"])){
            $res["money"]="";
        }
        is_null($res["purpose"])?$res["purpose"]="":$res["purpose"];
        is_null($res["identity"])?$res["identity"]="":$res["identity"];
        is_null($res["business"])?$res["business"]="":$res["business"];
        is_null($res["address"])?$res["address"]="":$res["address"];
        is_null($res["sex"])?$res["sex"]="":$res["sex"];
        return $res;
    }

    /** //查看所有信息
     * @return array
     * @throws InvalidServiceException
     */
    public function getAllData($step,$info_id=null)
    {
        $companys = Company::getCompanyById($this->getCurrentCompanyId());
        if($step<4){
            $this->replaceLoanCompany($this->getCurrentCompanyId());
            $data_1 = $this->getCompanyStepData(1);
            $data_2 = $this->getCompanyStepData(2);
            $res = is_null($data_2)?$data_1:array_merge($data_1,$data_2);
            $res = $this->filter($res);
        }elseif($step==4){
            $res=LoansInfo::select($info_id);
        }
        $res['company_name'] =$companys->getName();
        $res['company_id']=$companys->getId();
        $res['licence_num']=(new CompanyInfo())->getCompanyInfo($companys->getInfoId())->getLicenceNum();
        $data="";
        if($step=="1"){
            $data=LoansInfo::setStepOne($res);
        }elseif($step=="2"){
            $data=LoansInfo::setStepTwo($res);
        }elseif($step=="3" || $step=="4"){
            $data=LoansInfo::setStepThree($res);
        }
        return $data;
    }

    /**
     * @param $key
     * @return bool
     */
    private function checkRidesToken($key)
    {
        $rides = self::getRedis()->get($key);
        if($rides['token'] == $this->getToken() || $device = Devices::findFirstByToken($rides['token']) === false){
            return true;
        }else{
            return false;
        }
    }



    //api 新建新的普惠申请（第一步）
    public function oneNew($data)
    {
        $data["user_id"]=UserAuth::getAdminId($data['device_id']);
        $data["partner_id"]=UserAuth::getPartnerId($data['device_id']);
//        $loan=RepLoan::getLoanByCompanyId($data['company_id']);
//        if($loan){
//            if($loan->getState()==self::STATUS_REJECT || $loan->getState()==self::STATUS_FAIL){
//                RepLoan::edit($loan->getId(),self::STATUS_RE_APPLY);
//            }
//        }
        $info['company_id']=$data['company_id'];
        $info['state'] = self::STATUS_UNREVIEWED;
        $info['device_id'] = $data['device_id'];
        $info['tel']=$data['tel'];
        //新建新的企业申请
        $re = RepLoan::addNew($info);
        $data['u_id'] = $re;
        //新建新的申请信息
        $res = LoansInfo::addNew($data);
        return $res;
    }

    //后台 新建新的普惠申请（第一步）
    public function addNew($data)
    {
        $data["user_id"]=UserAuth::getAdminId($data['device_id']);
        $data["partner_id"]=UserAuth::getPartnerId($data['device_id']);
        $info['company_id']=$data['company_id'];
        $info['state'] = self::STATUS_UNREVIEWED;
        $info['device_id'] = $data['device_id'];
        $info['tel']=$data['tel'];
        //新建新的企业申请
        $re = RepLoan::addNew($info);
        $data['u_id'] = $re;
        //新建新的申请信息
        $res = LoansInfo::addInfo($data);
        return $res;
    }

//    api第二步
//    public function two($data)
//    {
//        $uid=JWT::getUid();
//        $data["step"]=2;
//        //更新提交时间状态
//        $data['device_id'] = $uid;
//        $data['state'] = self::STATUS_UNFINISHED;
//        $id = self::getRedis()->get("loan".$uid);
//        LoansInfo::edit($id,$data);
//        self::setCompanyLevel($data);
//        return $id;
//    }

    //api第二步
    public function two($data)
    {
        $data["step"]=2;
        $company_id = $this->getCurrentCompanyId();
        if($this->setLoanStepData(2, $data) === false) {
            throw new InvalidServiceException('公司基本信息保存失败');
        }
        $this->pushLoanCompany($company_id);
        $this->setStepComplete(2, 1);
        $this->setCurrentStep(2);
        return $company_id;
    }

    /**
     * 修改普惠信息
     * @param $id
     * @param $data
     */
    static public function editLoanInfo($id, $data)
    {
        $data['purpose'] = LoansInfo::PURPOSE;
        if(isset($data['tel']) && !empty($data['tel'])){
            RepLoan::editTel($id,$data['tel']);
        }
        if(isset($data['company_id']) && !empty($data['company_id'])){
            RepLoan::editCompanyId($id, $data['company_id']);
        }
        LoansInfo::edit($id,$data);
    }

    static public function setCompanyLevel($data)
    {
        $uid=JWT::getUid();
        $id = self::getRedis()->get("loan".$uid);
        self::getRedis()->save("loan".$uid."levelId".$id,$data["level_id"],-1);
    }

    /**
     * 获取所有未完成企业列表
     * @return array
     */
    public function getLoanList()
    {
        $device_id=JWT::getUid();
//        $key = 'Loan_company_'.$this->getTokenKey().'_list';
        $key = 'Loan_company_'.$device_id.'_list';
        $keys = self::getRedis()->queryKeys($key);
        $apply = [];
        if(is_array($keys)) {
            foreach ($keys as $key) {
                $value = self::getRedis()->get($key);
                $value['step'] = $this->getCurrentStep($value['id']);
                $value['date'] = $value['time'];
                $value['time'] = TimeService::humanTime($value['time']);
                $apply[] = $value;
            }
        }
        return $apply;
    }

    /**
     * 获取未完成企业列表
     * @return array
     */
    public function getUnLonaList(){
        $list = $this->getLoanList();
        $UnList = [];
        foreach ($list as $key=>$value){
            if($value['token'] == $this->getToken() || Devices::findFirstByToken($value['token']) === false){
                $UnList[]=$value;
            }
        }
        return $UnList;
    }

    //获取普惠审核列表
    public function getLoanVerifyList($status,$limit,$order)
    {
        $uid = JWT::getUid();
        $data=[];
        $result=RepCompanyVerify::getLoanVerifyList($uid,$status,$order,$limit);
        foreach ($result as $key => $value){
            $res=LoansInfo::getById($value["data_id"]);
            $companys=new Company();
            $company=$companys->getById($value['company_id']);
            $data[$key]["id"]=$res->getId();
            $data[$key]["name"]=$company->getName();
            $data[$key]["money"]="￥".number_format($res->getMoney());
            $data[$key]["time"]=date("Y/m/d H:i",$res->getTime());
            if($res->getState()==self::STATUS_UNREVIEWED){
                $data[$key]["state"]="未审核";
                $data[$key]["state_id"]=RepCompanyVerify::STATUS_NOT;
            }elseif ($res->getState()==self::STATUS_REJECT){
                $data[$key]["state"]="已驳回";
                $data[$key]["state_id"]=RepCompanyVerify::STATUS_FAIL;
            }elseif ($res->getState()==self::STATUS_ADOPT){
                $data[$key]["state"]="已通过";
                $data[$key]["state_id"]=RepCompanyVerify::STATUS_OK;
            }elseif ($res->getState()==self::STATUS_FAIL){
                $data[$key]["state"]="已结束";
                $data[$key]["state_id"]=RepCompanyVerify::STATUS_LOAN_FAIL;
            }elseif ($res->getState()==self::STATUS_OK){
                $data[$key]["state"]="已完成";
                $data[$key]["state_id"]=RepCompanyVerify::STATUS_LOAN_OK;
            }else{
                $data[$key]["state"]="错误";
            }
        }
        return $data;
    }

    static public function getLoanVerifyListInfo($parameters, $numberPage)
    {
        $conditions = '1=1';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere('verify.type = '.\Wdxr\Models\Repositories\CompanyVerify::TYPE_LOAN)
            ->andWhere('verify.status = '.\Wdxr\Models\Repositories\CompanyVerify::STATUS_FAIL)
            ->from(['verify'=>'Wdxr\Models\Entities\CompanyVerify'])
            ->leftJoin('Wdxr\Models\Entities\LoansInfo', 'info.id = verify.data_id', 'info')
            ->columns(['verify.id','verify.apply_time','verify.data_id','info.name'])
            ->orderBy('verify.id desc')
            ->getQuery()
            ->execute()
            ->toArray();
        $data=[];
        foreach ($builder as $key=>$val)
        {
            $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($val['id']);
            $loan = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($val['id']);
            if($payment === false && $loan===false){
                $data[] = $val;
            }
        }
        $numberPage= empty($numberPage)?1:$numberPage;
        for ($i=($numberPage-1)*10;$i<= $numberPage*10;$i++){
            if(!empty($data[$i])){
                $info['data'][]=$data[$i];
            }
        }
        $info['current']=$numberPage;
        $info['total_pages']=ceil(count($data)/10);
        if($numberPage-1<1){
            $info['before'] = 1;
        }else{
            $info['before']=$numberPage-1;
        }
        if($numberPage+1>$info['total_pages']){
            $info['next'] = $info['total_pages'];
        }else{
            $info['next']=$numberPage+1;
        }
        return $info;

    }

    //银行返回结果修改企业信息
    static public function bankLoan($data)
    {
        //查询审核信息
        $verify=RepCompanyVerify::getCompanyVerifyById($data['id']);
        $companyId=$verify->getCompanyId();
        //查询企业新消息
        $company=Company::getCompanyById($companyId);
//        $company_service = (new CompanyService())->getCompanyServiceById($companyId);
        //判断企业申请是否通过审核
        if($company->getAuditing()!=Company::AUDIT_OK){
            throw new InvalidServiceException('当前企业证件信息尚未通过审核');
        }
        //修改审核状态
        $verify->setStatus($data['status']);
        if(!empty($data['remark'])){
            $verify->setRemark($data['remark']);
        }
        $verify->save();
        //获取普惠信息
        $loansInfo=LoansInfo::getLoansInfoById($verify->getDataId());
        $loan=RepLoan::getLoanById($loansInfo->getUId());
        //根据状态修改普惠信息
        if($data['status']==RepCompanyVerify::STATUS_LOAN_FAIL){
            $loansInfo->setState(self::STATUS_FAIL);
            if(!$loansInfo->save()){
                throw new InvalidServiceException('普惠审核提交失败5');
            }
            $loan->setState(self::STATUS_FAIL);
            if(!$loan->save()){
                throw new InvalidServiceException('普惠审核提交失败6');
            }
//            $company_service->setPaymentStatus(Company::PAYMENT_FAIL);
//            if(!$company->save() ){
//                throw new InvalidServiceException('普惠审核提交失败7');
//            }

            $content['title'] = "(" . $loansInfo->getName() . ")普惠审核通知";
            $content['body'] = $loansInfo->getName() . '的普惠申请已经被驳回';
        }elseif($data['status']==RepCompanyVerify::STATUS_LOAN_OK){
            //通过后修改普惠信息与企业信息
            $loansInfo->setState(self::STATUS_OK);
            if(!$loansInfo->save()){
                throw new InvalidServiceException('普惠审核提交失败1');
            }
            $loan->setState(self::STATUS_OK);
            if(!$loan->save()){
                throw new InvalidServiceException('普惠审核提交失败2');
            }
            //修改缴费状态
            $payment= new CompanyPayment();
            $companyPayment=$payment->getPaymentById2($loan->getPaymentId());
            if($companyPayment ===false){
                throw new InvalidServiceException('查找不到普惠缴费信息');
            }
            $companyPayment->setStatus(CompanyPayment::STATUS_OK);
            $companyPayment->setVoucher($data['voucher']);
            if(!$companyPayment->save()){
                throw new InvalidServiceException('普惠审核提交失败'.$data['voucher'].'---'.$companyPayment->getId());
            }
            //创建一个新企业及其账号
            $user_id = \Wdxr\Models\Repositories\User::addDefaultUser($company->getId(), $companyPayment->getType(), $companyPayment->getLevelId());
            $company->setUserId($user_id);
            $company->setStatus(Company::STATUS_ENABLE);
            if(!$company->save()){
                throw new InvalidServiceException('普惠审核提交失败3');
            }
//          添加企业信息
            $company_bank = \Wdxr\Models\Repositories\CompanyBank::getBankcard($companyId,\Wdxr\Models\Repositories\CompanyBank::CATEGORY_MASTER);
            if($company_bank){
                $company_bank->setAccount($data['bank_accout']);
                $company_bank->setNumber($data['bankcard']);
                $company_bank->setBankcardPhoto($data['bankcard_photo']);
                $company_bank->setProvince(130000);
                $company_bank->setCity(130100);
                $company_bank->setAddress($data['address']);
                $company_bank->setBank('河北省农村信用社');
                $company_bank->setBankType(\Wdxr\Models\Repositories\CompanyBank::TYPE_PRIVATE);
            }else{
                $company_bank = (new \Wdxr\Models\Entities\CompanyBank());
                $company_bank->setCompanyId($companyId);
                $company_bank->setAccount($data['bank_accout']);
                $company_bank->setNumber($data['bankcard']);
                $company_bank->setBankcardPhoto($data['bankcard_photo']);
                $company_bank->setProvince(130000);
                $company_bank->setCity(130100);
                $company_bank->setAddress($data['address']);
                $company_bank->setBank('河北省农村信用社');
                $company_bank->setBankType(\Wdxr\Models\Repositories\CompanyBank::TYPE_PRIVATE);
                $company_bank->setCategory(\Wdxr\Models\Repositories\CompanyBank::CATEGORY_MASTER);
            }
            if(!$company_bank->save()){
                throw new InvalidServiceException('普惠银行卡信息提交失败');
            }


            $time = strtotime(date('Y-m-d',strtotime('+1 day')));
            //服务结束时间
            $end = strtotime('+365 days',$time)-1;
            //            $server_info['contract_status'] =$contract->getContractStatus();
//            设置服务期限
            $company_service= new CompanyService();
            $service = $company_service->getCompanyServiceByCompanyId($companyId);
            if($service === false) {
                $service = $company_service->addService($companyId, $companyPayment->getId(), CompanyService::TYPE_ORDINARY);
            }
            $service_id = $service->getId();
            $service->setStartTime($time);
            $service->setEndTime($end);
            $service->setServiceStatus(CompanyService::SERVICE_ENABLE);
            $service->setPaymentStatus(CompanyPayment::STATUS_OK);
            if(!$service->save()){
                throw new InvalidServiceException('普惠企业服务状态保存失败');
            }
            $contract = new Contract();
            $contract_num = $contract->getContractNum($service_id);
            $contract_object = $contract->getServiceContract($service_id);
            if($contract_object === false){
                /**
                 * 绑定合同
                 * @var $contract \Wdxr\Models\Repositories\Contract
                 */
                $contract = Repositories::getRepository('Contract');
                $contract->getLastContractNum($company->getDeviceId(),$companyId,$service_id);
            }else{
                $contract_object->setContractNum($contract_num);
                if(!$contract_object->save()){
                    throw new InvalidServiceException('普惠企业合同状态保存失败');
                }
            }

            $finance = new FinanceController();
            $finance->setAchievementNewAction($companyPayment->getId());
            $content['title'] = "(" . $loansInfo->getName() . ")普惠审核通知";
            $content['body'] = $loansInfo->getName() . '的普惠申请已经通过审核';

            //服务期限通知
//            Mns::periodSMS((int)$loan->getTel(),$loansInfo->getName(),date('Y年m月d日',$period));
        }
        $content['type'] = PushService::PUSH_TYPE_WARN;
        $push = new PushService();
        $push->newPushResult($content, $company->getDeviceId());
        return true;
    }

    public static function enableLoan(Companys $company, \Wdxr\Models\Entities\CompanyPayment $payment, $data)
    {
        //添加银行卡
        CompanyBank::saveCompanyBank($company->getId(), [
            'bank' => '河北省农村信用社',
            'bank_type' => CompanyBank::TYPE_PRIVATE,
            'number' => $data['bankcard'],
            'province' => '130000',
            'city' => '130100',
            'address' => $data['address'],
            'account' => $data['account'],
            'bankcard_photo' => $data['bankcard_photo']
        ], CompanyBank::CATEGORY_MASTER);

        //创建一个新企业及其账号
        \Wdxr\Models\Repositories\User::addDefaultUser(
            $company->getId(),
            $payment->getType(),
            $payment->getLevelId()
        );

        /**
         * 添加一条服务信息
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $service = $company_service->enableCompanyService(
            $company->getId(),
            $payment->getId(),
            CompanyService::TYPE_ORDINARY
        );

        /**
         * 绑定合同
         * @var $company_contract \Wdxr\Models\Repositories\Contract
         */
        $company_contract = Repositories::getRepository('Contract');
        $contract = $company_contract->getLastContractNum(
            $company->getDeviceId(),
            $company->getId(),
            $service->getId()
        );

        /**
         * 票据期限
         * @var $company_bill \Wdxr\Models\Repositories\CompanyBill
         */
        $company_bill = Repositories::getRepository('CompanyBill');
        $company_bill->setBillTime($payment->getType(), $company->getId(), $service->getStartTime());
        /**
         * 征信期限
         * @var $company_report CompanyReport
         */
        $company_report = Repositories::getRepository('CompanyReport');
        $company_report->setReportTime($payment->getType(), $service->getId());

        //业绩添加
        BonusList::newAchievement($payment->getId(), $contract->getId(), $service->getId());

        return true;
    }


//获取待导出信息
    static public function export($id)
    {
        $info=LoansInfo::getById($id);
        $loan=RepLoan::getLoanById($info->getUId());
        $data["money"]=$info->getMoney()."元";
        $data["tel"]=$loan->getTel();
        $data['system_loan']=$loan->getSystemLoan();
        $data['sponsion']=$loan->getSponsion();
        $data['other_loan']=$loan->getOtherLoan();
        $data['unhealthy']=$loan->getUnhealthy();
        $data['last_year']=$loan->getLastYear();
        $data['this_year']=$loan->getThisYear();
        $data['quota']=$loan->getQuota();
        $data['remarks']=$loan->getRemarks();
        $data["name"]=$info->getName();
        $data["identity"]=$info->getIdentity();
        $companyInfo=Company::getCompanyById($loan->getCompanyId())->getInfoId();
        $data["license"]=CompanyInfo::getCompanyInfoById($companyInfo)->getLicenceNum();
        $data["business"]=$info->getBusiness();
        $data["term"]=LoansInfo::getTerm($info->getTerm());
        $data["sex"]=LoansInfo::getSex($info->getSex());
        $data["time"]=date('Y-m-d',$info->getTime());
        $province=Regions::getRegionName($info->getProvince())?Regions::getRegionName($info->getProvince())->name:'';
        $city=Regions::getRegionName($info->getCity())?Regions::getRegionName($info->getCity())->name:'';
        $area=Regions::getRegionName($info->getArea())?Regions::getRegionName($info->getArea())->name:'';
        $data["address"]=$province.$city.$area.$info->getAddress();
        return $data;
    }

//    删除未完成的普惠申请

    static public function deletes($data_id)
    {
        if(!preg_match("/^\+?[1-9][0-9]*$/",$data_id)){
            return "您提交的ID值不合法，请重新提交！";
        }
        $loanInfo=LoansInfo::getById($data_id);
        if ($loanInfo->getState()!="1"){
            return "当前申请正在审核中，无法删除！";
        }
        $loan_id=$loanInfo->getUId();
        $loan_tel=RepLoan::getLoanById($loan_id)->getTel();
        $uid=JWT::getUid();
        self::getRedis()->delete("loan".$uid."levelId".$data_id);
        $key = 'loan'.$uid.'info'.$data_id.'tel'.$loan_tel;
        self::getRedis()->delete($key);
        RepLoan::deleteApply($loan_id);
        LoansInfo::deleteLoan($data_id);

        return true;
    }


//确认后提交审核
    public function confirm($data)
    {
        $data['device_id'] = JWT::getUid();
        $data["state"]=Loan::STATUS_UNREVIEWED;
        $loan_id = $this->oneNew($data);
        $type=CompanyPayment::TYPE_LOAN;
        $voucher="-1";
        $company_id=$this->getCurrentCompanyId();
        $level=Level::getLevelByDefault();
        if($level === false){
            throw new InvalidRepositoryException("查找不到默认级别设置！");
        }
        $level_id = $level->getId();
        //根据企业级别确定缴费金额
        $amount = Level::getLevelAmount($level_id);
        //添加缴费信息
        $payment_id = CompanyPayment::addPaymentInfo($company_id, $amount, $data['device_id'], $voucher, $type,$level_id,CompanyPayment::STATUS_LOAN);
        \Wdxr\Models\Repositories\Loan::editPaymentId($loan_id,$payment_id);
        Company::updateCompanyPayment($company_id,$data['device_id']);
        //添加到审核信息到审核列表
        RepCompanyVerify::newVerify($company_id,$data['device_id'],RepCompanyVerify::TYPE_LOAN,$loan_id);
        $this->deleteCompanyCache($company_id);
        return true;
    }

    //后台提交审核
    public function addLoan($data)
    {
        $data["device_id"]= UserAdmin::getDeviceId($data['admin_id'],UserAdmin::TYPE_ADMIN);
        $data["state"]=Loan::STATUS_UNREVIEWED;
        $loan_id = $this->addNew($data);
        $type=CompanyPayment::TYPE_LOAN;
        $voucher="-1";
        //根据企业级别确定缴费金额
        $amount = Level::getLevelAmount($data['level_id']);
        //添加缴费信息
        $payment_id = CompanyPayment::addPaymentInfo($data['company_id'], $amount, $data["device_id"], $voucher, $type,$data['level_id'],CompanyPayment::STATUS_LOAN);
        Company::updateCompanyPayment($data['company_id'],$data["device_id"]);
        //添加到审核信息到审核列表
        RepCompanyVerify::newVerify($data['company_id'],$data["device_id"],RepCompanyVerify::TYPE_LOAN,$loan_id);
        \Wdxr\Models\Repositories\Loan::editPaymentId($loan_id,$payment_id);
        Company::updateCompanyPayment($data['company_id'],$data["device_id"]);
        return true;
    }
    //中途保存
    public function tempSave($step,$data)
    {
        if($step==1){
            $this->setCurrentCompanyId($data['company_id']);
        }
        $this->setStepComplete($step, 0);
        $this->setCurrentStep($step);
        unset($data['step']);
        if($this->setLoanStepData($step, $data) === false) {
            throw new InvalidServiceException('保存失败');
        }
        $this->pushLoanCompany($this->getCurrentCompanyId());
        return true;
    }

    //驳回后重新提交
    public function edit($data)
    {
        $uid = JWT::getUid();
        $company_id = $this->getCurrentCompanyId();
        $companyVerify=new RepCompanyVerify();
        $verify=$companyVerify->getLastCompanyVerify($company_id,RepCompanyVerify::TYPE_LOAN,RepCompanyVerify::STATUS_FAIL);
        $loanInfo = LoansInfo::getLoansInfoById($verify->getDataId());
        if($loanInfo === false){
            throw new InvalidServiceException('原申请信息查询不到！');
        }
        $loan=RepLoan::getLoanById($loanInfo->getUId());
        $verify->setStatus(RepCompanyVerify::STATUS_RE_APPLY);
        if(!$verify->save()){
            throw new InvalidServiceException('审核状态修改失败！');
        }
        //修改状态为未审核
        $data['company_id'] = $company_id;
        $data["state"]=Loan::STATUS_UNREVIEWED;
        $data["device_id"]=$uid;
        $data['tel'] = $loan->getTel();
        $payment = CompanyPayment::getPaymentById($loan->getPaymentId());
        $amount = Level::getLevelAmount($payment->getLevelId());
        //添加缴费信息
        $payment_id = CompanyPayment::addPaymentInfo($company_id, $amount, $uid, '-1', CompanyPayment::TYPE_LOAN,$payment->getLevelId(),CompanyPayment::STATUS_LOAN);
        $data['payment_id'] = $payment_id;
        $loan_id = RepLoan::addNew($data);
        $data["u_id"]=$loan_id;
        $user=UserAdmin::getUser($uid);
        if($user->getType()=="1"){
            $data["user_id"]=$user->getUserId();
        }elseif ($user->getType()=="2"){
            $data["partner_id"]=$user->getUserId();
            $partner=Company::getCompanyByUserId($user->getUserId());
            $data["user_id"]=$partner->getAdminId();
        }
        //添加新的企业详细信息
        $data['province_id']=$data['province'];
        $data['city_id']=$data['city'];
        $data['area_id']=$data['area'];
        $data['term_id']=$data['term'];
        $id = LoansInfo::addNew($data);
        //添加到审核信息到审核列表
        RepCompanyVerify::newVerify($company_id,$data["device_id"],RepCompanyVerify::TYPE_LOAN, $id);
        return true;
    }

    //驳回后重新提交
    public function editInfo($company_id,$data)
    {
        $companyVerify=new RepCompanyVerify();
        $verify=$companyVerify->getLastCompanyVerify($company_id,RepCompanyVerify::TYPE_LOAN,RepCompanyVerify::STATUS_FAIL);
        $loanInfo = LoansInfo::getLoansInfoById($verify->getDataId());
        if($loanInfo === false){
            throw new InvalidServiceException('原申请信息查询不到！');
        }
        $verify->setStatus(RepCompanyVerify::STATUS_RE_APPLY);
        if(!$verify->save()){
            throw new InvalidServiceException('审核状态修改失败！');
        }
        //修改状态为未审核
        $data['company_id'] = $company_id;
        $data["state"]=Loan::STATUS_UNREVIEWED;
        $data["device_id"]=$loanInfo->getDeviceId();
        $loan_id = RepLoan::addNew($data);
        $data["u_id"]=$loan_id;
        $user=UserAdmin::getUser($data["device_id"]);
        if($user->getType()=="1"){
            $data["user_id"]=$user->getUserId();
        }elseif ($user->getType()=="2"){
            $data["partner_id"]=$user->getUserId();
            $partner=Company::getCompanyByUserId($user->getUserId());
            $data["user_id"]=$partner->getAdminId();
        }
        //添加新的企业详细信息
        $id=LoansInfo::addInfo($data);
        $amount = Level::getLevelAmount($data['level_id']);
        $voucher="-1";
        $type=CompanyPayment::TYPE_LOAN;
        //添加缴费信息
        $payment_id = CompanyPayment::addPayment($company_id, $amount,$data["device_id"], $voucher, $type,CompanyPayment::STATUS_LOAN);

        \Wdxr\Models\Repositories\Loan::editPaymentId($id,$payment_id);
        //添加到审核信息到审核列表
        RepCompanyVerify::newVerify($company_id,$data["device_id"],RepCompanyVerify::TYPE_LOAN,$id);
        return true;
    }

// 查询普惠审核资料
    static public function getInfo($id)
    {
        $verify=RepCompanyVerify::getCompanyVerifyById($id);
        if(!$verify){
            throw new InvalidServiceException("查询的当前信息不存在");
        }
        $info=LoansInfo::getById($verify->getDataId())->toArray();
        $loan=RepLoan::getLoanById($info["u_id"]);
        $admin = Admin::getAdminById($info["user_id"]);
        if(!$admin){
            throw new InvalidServiceException("查询的当前管理员信息不存在");
        }
        $info["admin_name"]=$admin->getName();
        if(!is_null($info["partner_id"])){
            $company= new Company();
            $companyInfo = $company->getCompanyByUserId($info["partner_id"]);
            if(!$companyInfo){
                throw new InvalidServiceException("查询的当前合伙人信息不存在");
            }
            $companyInfoId=$companyInfo->getInfoId();
            $info["partner_name"]=CompanyInfo::getCompanyInfoById($companyInfoId)->getLegalName();
        }else{
            $info["partner_name"]="无";
        }
        $info['company_id'] = $verify->getCompanyId();
        $info["system_loan"]=$loan->getSystemLoan();
        $info["sponsion"]=$loan->getSponsion();
        $info["other_loan"]=$loan->getOtherLoan();
        $info["unhealthy"]=$loan->getUnhealthy();
        $info["last_year"]=$loan->getLastYear();
        $info["this_year"]=$loan->getThisYear();
        $info["quota"]=$loan->getQuota();
        $info["remarks"]=$loan->getRemarks();
        $info["status"]=$verify->getStatus();
        $info["remark"]=$verify->getRemark();
        $info["verify_id"]=$id;
        $info["tel"]=$loan->getTel();
        $companys=Company::getCompanyById($loan->getCompanyId());
//        var_dump($loan->toArray());die;
//        $company_service = (new CompanyService())->getCompanyServiceById($loan->getCompanyId());
        $payment = CompanyPayment::getPaymentById($loan->getPaymentId());
        if($payment === false){
            throw new InvalidServiceException("查询的当前普惠缴费信息不存在");
        }
        $info['level']=Level::getLevelName($payment->getLevelId());
        $info['licence_num']=CompanyInfo::getCompanyInfoById($companys->getInfoId())->getLicenceNum();
        $info['company_name']=$companys->getName();
        $province = Regions::getRegionName($info["province"]);
        $info["province"] = $province?$province->name:'';
        $city = Regions::getRegionName($info["city"]);
        $info["city"]=$city?$city->name:'';
        $area = Regions::getRegionName($info["area"]);
        $info["area"]=$area?$area->name:'';
        return $info;

    }

    //普惠审核
    static public function examine($data)
    {
        $loans = new RepLoan();
        $loan = $loans->getById($data["u_id"]);
        $company_id = $loan->getCompanyId();
        $company = Company::getCompanyById($company_id);
        if($data["status"] == RepCompanyVerify::STATUS_OK) {
            //验证提交信息是否完整
            $form = new PresentationForm();
            if($form->isValid($data) == false) {
                $message = isset($form->getMessages()[0]) ? $form->getMessages()[0] : '提交的普惠信息错误';
                throw new Exception($message);
            }
            if($company->getAuditing() != Company::AUDIT_OK) {
                throw new InvalidServiceException('请先审核通过证件信息');
            }
            $data["state"] = self::STATUS_ADOPT;
            /**
             * @var $company_service CompanyService
             */
            $company_service = Repositories::getRepository('CompanyService');
            $service = $company_service->addService($company_id, $loan->getPaymentId(), CompanyService::TYPE_ORDINARY);

            /**
             * 绑定合同
             * @var $contract \Wdxr\Models\Repositories\Contract
             */
            $contract = Repositories::getRepository('Contract');
            $contract->getLastContractNum($company->getDeviceId(), $company->getId(), $service->getId());

        } elseif($data["status"] == RepCompanyVerify::STATUS_FAIL) {
            if(empty(trim($data["remark"]))) {
                throw new InvalidServiceException("驳回信息不能为空");
            }
            $data["state"] = self::STATUS_REJECT;
            $payment = CompanyPayment::getPaymentById($loan->getPaymentId());
            if($payment === false) {
                throw new InvalidServiceException("查找不到对应的普惠缴费信息");
            }
            $payment->setStatus(CompanyPayment::STATUS_FAIL);
            if(!$payment->save()) {
                throw new InvalidServiceException("普惠审核信息提交失败");
            }
        }

        $verify = new RepCompanyVerify();
        if(!$verify->edit($data["id"], $data)) {
            throw new InvalidServiceException("普惠审核信息提交失败");
        }
        self::editLoanInfo($data["data_id"], $data);
        if(!RepLoan::Presentation($data["u_id"], $data)) {
            throw new InvalidServiceException("普惠审核信息提交失败");
        }

        $loan_info = LoansInfo::getLoanInfoById($data["data_id"]);
        $content['type'] = PushService::PUSH_TYPE_WARN;
        if($data["status"] == RepCompanyVerify::STATUS_OK) {
            $content['title'] = "(" . $loan_info->getName() . ")普惠审核通知";
            $content['body'] = $loan_info->getName() . '的普惠申请已经通过审核';
            SMS::loanSuccessSMS($data["tel"],$data["name"]);
        }elseif($data["status"] == RepCompanyVerify::STATUS_FAIL) {
            $content['title'] = "(" . $loan_info->getName() . ")普惠审核通知";
            $content['body'] = $loan_info->getName() . '的普惠申请已经被驳回';
            SMS::loanFailedSMS($data["tel"],$data["name"]);
        }
        $push = new PushService();
        $push->newPushResult($content, $company->getDeviceId());
        return true;
    }

    //获取所有普惠待申请企业列表
     public function getLoanCompanyList($parameters,$numberPage,$follow)
    {
        $conditions = '1=1';$bind = [];
        $uid=JWT::getUid();
        if(!empty($parameters)){
            $conditions="company.name LIKE :name:";
            $bind=["name"=>"%".$parameters."%"];
        }
        $list = $this->getLoanList();
        $unfinished = 0;
        if(!empty($list)){
            foreach ($list as $key=>$val){
                $un[]=$val['id'];
            }
            $unfinished = implode(",",$un);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->from(['company' => 'Wdxr\Models\Entities\Companys'])
            ->where("company.device_id in (0,$uid)")
            ->andWhere("company.status <> 3 ")
            ->andWhere("company.add_people = $uid or company.id in ($follow)")
            ->andWhere("company.id not in ($unfinished)")
            ->andWhere(" ISNULL(service.id) ")
            ->andWhere($conditions,$bind)
            ->leftJoin('Wdxr\Models\Entities\CompanyService','company.id = service.company_id', 'service')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','info.id = company.info_id', 'info')
            ->leftJoin("Wdxr\Models\Entities\Loan","loan.company_id = company.id","loan")
            ->orderBy("company.id desc")
            ->columns(["company.id","company.name","info.licence_num","company.time", "loan.state"])
            ->getQuery()
            ->execute()
            ->toArray();

        $data=[];
        foreach ($builder as $key=>$val)
        {
            $payment = (new CompanyPayment())->getRPaymentByCompanyIdStatus($val['id']);
            $loan = \Wdxr\Models\Repositories\Loan::getByCompanyIdStatus($val['id']);
            if($payment === false && $loan === false){
                $data[$key]["id"]=$val["id"];
                $data[$key]["name"]=$val["name"];
                $data[$key]["licence_num"]=$val["licence_num"];
                $data[$key]["time"]=TimeService::humanTime($val["time"]);
            }
        }
        $data = array_values($data);
        $numberPage= empty($numberPage)?1:$numberPage;
        $info=[];
        for ($i=($numberPage-1)*10;$i<= $numberPage*10;$i++){
            if(!empty($data[$i])){
                $info[]=$data[$i];
            }
        }
        return $info;
    }



    //获取所有普惠申请分页信息
    public static function getLoanListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if (is_null($parameters) === false) {
            list($conditions, $bind) = array_values($parameters);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("verify.type = ?0 and company.auditing = ?1", [RepCompanyVerify::TYPE_LOAN, Company::AUDIT_OK])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->rightJoin('Wdxr\Models\Entities\LoansInfo','info.id = verify.data_id', 'info')
            ->leftJoin('Wdxr\Models\Entities\Companys','company.id = verify.company_id','company')
            ->leftJoin('Wdxr\Models\Entities\Admins', 'admin.id = info.user_id', 'admin')
            ->leftJoin('Wdxr\Models\Entities\Companys','company.user_id = info.partner_id', 'companys')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','companyinfo.id = companys.info_id', 'companyinfo')
            ->leftJoin('Wdxr\Models\Entities\Companys', 'company.partner_id = partner_recommend.user_id', 'partner_recommend')
            ->leftJoin('Wdxr\Models\Entities\Users', 'partner.id = company.partner_id', 'partner')
            ->columns([
                'verify.verify_time', 'verify.apply_time', 'info.name as info_name', 'verify.id',  'verify.data_id',
                'admin.name as admin_name', 'company.name as company_name', "ifnull(companyinfo.legal_name, '无') as legal_name",
                'company.id as company_id', 'verify.status', "ifnull(partner.name, '') as partner_name",
                'partner_recommend.id as partner_company_id', 'partner_recommend.name as partner_company'
            ])
            ->orderBy('verify.id asc');
        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 15,
            'page' => $numberPage
        ]);
    }

    static public function getLoanVerify()
    {
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->Where("verify.status = 3")
            ->andWhere("verify.type = :type:", ['type' => RepCompanyVerify::TYPE_LOAN])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->leftJoin('Wdxr\Models\Entities\Companys','company.id = verify.company_id','company')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','companyinfo.id = company.info_id', 'companyinfo')
            ->columns([ 'company.name as name', "ifnull(companyinfo.legal_name, '无') as legal_name",'companyinfo.district','companyinfo.city'])
            ->orderBy('companyinfo.district desc')
            ->getQuery()->execute()->toArray();
        return $builder;
    }

//获取普惠待审核信息分页列表
    static public function getUnLoanListPagintor($parameters,$numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            $conditions="info.name LIKE :name:";
            $bind=["name"=>"%".$parameters."%"];
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("verify.type = :type:", ['type' => RepCompanyVerify::TYPE_LOAN])
            ->andWhere("verify.status = :status:", ['status' => RepCompanyVerify::STATUS_NOT])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->rightJoin('Wdxr\Models\Entities\LoansInfo','info.id = verify.data_id', 'info')
            ->leftJoin('Wdxr\Models\Entities\Admins', 'admin.id = info.user_id', 'admin')
            ->leftJoin('Wdxr\Models\Entities\Companys','company.user_id = info.partner_id', 'company')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','companyinfo.id = company.info_id', 'companyinfo')
//            ->leftJoin('Wdxr\Models\Entities\Users', 'users.id = info.partner_id', 'users')
            ->columns(['verify.verify_time', 'verify.apply_time', 'info.name as info_name', 'verify.id',  'verify.data_id','admin.name as admin_name', "ifnull(companyinfo.legal_name, '无') as legal_name", 'verify.status'])
            ->orderBy('verify.id desc');
        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }




// 查询普惠详细信息
    static public function select($id,$step)
    {
        $uid=JWT::getUid();
        $res=LoansInfo::select($id);
        $company=Company::getCompanyById($res['company_id']);
        if($step == "4"){
            $res["level_id"]=$company->getLevelId();
        }else{
            $res["level_id"]=self::getRedis()->get("loan".$uid."levelId".$id);
        }
        if(isset($res["level_id"]) && !empty($res["level_id"])){
            $res["level"]=Level::getLevelById($res["level_id"])->getLevelName();
        }else{
            $res["level_id"]="";
            $res["level"]="";
        }
        if($step=="1"){
            $data=LoansInfo::setStepOne($res);
            return $data;
        }elseif($step=="2"){
            $data=LoansInfo::setStepTwo($res);
            return $data;
        }elseif($step=="3" || $step=="4"){
            $data=LoansInfo::setStepThree($res);
            return $data;
        }
    }

    //判断手机验证码**第一步转移
    public function telCodeVerify($data){
        if($this->verifyCompanySmsStatus($data['tel']) === false) {
            if(empty($data['code'])){
                throw new InvalidServiceException('手机验证码不可为空');
            }
            if(SMS::verifyPhone($data['code'],$data['token']) === false) {
                throw new InvalidServiceException('手机验证码错误');
            } else {
                $this->setCompanySmsStatus($data['tel']);
            }
        }
        return true;
    }

    //获取企业ID
    static public function getLoanCompanyId(){
        $loanInfoId=self::getRedis()->get("loan".JWT::getUid());
        $loanId=LoansInfo::getLoansInfoById($loanInfoId)->getUId();
        return RepLoan::getLoanById($loanId)->getCompanyId();
    }

    /**
     * 普惠审核第一步的通过审核业务
     * @param Companys $company
     * @param \Wdxr\Models\Entities\Loan $loan
     * @param $info_id
     * @param $data
     * @return bool
     * @throws InvalidServiceException
     */
    public static function doAgreeLoan(Companys $company, \Wdxr\Models\Entities\Loan $loan, $info_id, $data)
    {
        /**
         * @var $company_service CompanyService
         */
        $company_service = Repositories::getRepository('CompanyService');
        $service = $company_service->addService($company->getId(), $loan->getPaymentId(), CompanyService::TYPE_ORDINARY);

        /**
         * 绑定合同
         * @var $contract \Wdxr\Models\Repositories\Contract
         */
        $contract = Repositories::getRepository('Contract');
        $contract->getLastContractNum($company->getDeviceId(), $company->getId(), $service->getId());

        //更新普惠信息
        $data["state"] = self::STATUS_ADOPT;
        self::editLoanInfo($info_id, $data);
        if(RepLoan::Presentation($loan->getId(), $data) === false) {
            throw new InvalidServiceException("普惠审核信息提交失败");
        }

        return true;
    }

}
