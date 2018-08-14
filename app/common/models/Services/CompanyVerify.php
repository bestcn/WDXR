<?php
namespace Wdxr\Models\Services;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Models\Repositories\CompanyService;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\CompanyBill as RepoCompanyBill;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class CompanyVerify extends Services
{

    /**
     * 企业补录列表
     * @param $device_id
     * @param int $page
     * @return array
     */
    static public function getCompanyVerifyInfoList($device_id, $page = 1)
    {
        $verifies = Services::getStaticModelsManager()->createBuilder()
            ->where('verify.device_id = :device_id: and verify.type = :type:', [
                'device_id' => $device_id,
                'type' => RepoCompanyVerify::TYPE_DOCUMENTS])
            ->andWhere("verify.status = :status:", ['status' => RepoCompanyVerify::STATUS_FAIL])
            ->andWhere("company.id is not null")
            ->from(['verify'=>'Wdxr\Models\Entities\CompanyVerify'])
            ->leftJoin('Wdxr\Models\Entities\Companys','company.id = verify.company_id', 'company')
            ->orderBy('verify_time desc, apply_time desc')
            ->limit(10, $page * 10 - 10)
            ->columns(["verify.company_id","company.name","verify.status","verify.verify_time","verify.apply_time","verify.remark",])
            ->getQuery()
            ->execute();
        if($verifies->count() === 0) {
            return [];
        }
        $list = [];
        /**
         * @var $verify \Wdxr\Models\Entities\CompanyVerify
         */
        foreach ($verifies->toArray() as $verify)
        {
            $list[] = [
                'company_id' => $verify['company_id'],
                'company_name' => $verify['name'],
                'verify_time' => is_null($verify['verify_time']) ? "---" : date('Y年m月d日', $verify['verify_time']),
                'apply_time' => date('Y年m月d日', $verify['apply_time']),
                'status' => $verify['status'],
                'status_name' => RepoCompanyVerify::getStatusName($verify['status']),
                'reason' => $verify['remark']
            ];
        }
        return $list;
    }

    static public function getCompnayVerifyList($parameters,$numberPage)
    {
        $conditions = '1=1';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $verifies = Services::getStaticModelsManager()->createBuilder()
            ->where('verify.type = :type:',['type' => RepoCompanyVerify::TYPE_DOCUMENTS])
            ->andWhere($conditions,$bind)
            ->andWhere("verify.status = :status:", ['status' => RepoCompanyVerify::STATUS_FAIL])
            ->andWhere('company.id is not null')
            ->from(['verify'=>'Wdxr\Models\Entities\CompanyVerify'])
            ->leftJoin('Wdxr\Models\Entities\Companys','company.id = verify.company_id', 'company')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo','info.id = company.info_id', 'info')
            ->columns(["company.id","company.name","info.licence_num","verify.apply_time",'verify.id as verify_id'])
            ->orderBy('verify.verify_time desc, verify.apply_time desc');
        return new PaginatorQueryBuilder([
        'builder' => $verifies,
        'limit'=> 10,
        'page' => $numberPage
    ]);

    }

    static public function getCompnayVerifyInfoList($parameters,$numberPage,$device_id)
    {
        $conditions = '1=1';$bind = [];
        if(!empty($parameters)) {
            list($conditions, $bind) = array_values($parameters);
        }
        $verifies = Services::getStaticModelsManager()->createBuilder()
            ->Where($conditions,$bind)
            ->andWhere('company.id is not null')
            ->andWhere('verify.device_id = '.$device_id)
            ->from(['verify'=>'Wdxr\Models\Entities\CompanyVerify'])
            ->leftJoin('Wdxr\Models\Entities\Companys','company.id = verify.company_id', 'company')
            ->columns(["company.id as company_id",'company.name as company_name',"verify.type","verify.id","verify.status","verify.apply_time",'verify.data_id'])
            ->orderBy('verify.verify_time desc, verify.apply_time desc');
//            ->getQuery()
//            ->execute()
//            ->toArray();
//        var_dump($verifies);die;
        return new PaginatorQueryBuilder([
            'builder' => $verifies,
            'limit'=> 10,
            'page' => $numberPage
        ]);

    }

    /*
     * 获取补录企业数量
     */
    public function getCompanyVerifyInfoCount($device_id)
    {
        $verifies = Services::getStaticModelsManager()->createBuilder()
            ->where('device_id = :device_id: and type = :type:', [
                'device_id' => $device_id,
                'type' => RepoCompanyVerify::TYPE_DOCUMENTS])
            ->andWhere("status = :status:", ['status' => RepoCompanyVerify::STATUS_FAIL])
            ->from('Wdxr\Models\Entities\CompanyVerify')
            ->orderBy('verify_time desc, apply_time desc')
            ->getQuery()
            ->execute();

        if($verifies->count() === 0) {
            return 0;
        }

        $list = [];
        /**
         * @var $verify \Wdxr\Models\Entities\CompanyVerify
         */
        foreach ($verifies as $verify)
        {
            if($verify->companys === false) {
                continue;
            }
            $list[] = [
                'company_id' => $verify->getCompanyId(),
                'company_name' => $verify->companys->getName(),
                'verify_time' => is_null($verify->getVerifyTime()) ? "---" : date('Y年m月d日', $verify->getVerifyTime()),
                'apply_time' => date('Y年m月d日', $verify->getApplyTime()),
                'status' => $verify->getStatus(),
                'status_name' => RepoCompanyVerify::getStatusName($verify->getStatus()),
                'reason' => $verify->getRemark()
            ];
        }
        return count($list);
    }

    static public function getCompanyBillLog($company_id, $page)
    {
        $logs = Services::getStaticModelsManager()->createBuilder()
            ->where('company_id = :company_id:', ['company_id' => $company_id])
            ->from('Wdxr\Models\Entities\CompanyBillLog')
            ->orderBy('createAt desc')
            ->limit(10, $page * 10 - 10)
            ->getQuery()
            ->execute();

        if($logs->count() === 0) {
            return [];
        }

        $list = [];
        /**
         * @var $log \Wdxr\Models\Entities\CompanyBillLog
         * @var $verify \Wdxr\Models\Entities\CompanyVerify
         */
        foreach ($logs as $log) {
            $verify = $log->company_verify;
            $list[] = [
                'verify_time' => is_null($verify->getVerifyTime()) ? "---" : date('Y年m月d日', $verify->getVerifyTime()),
                'apply_time' => date('Y年m月d日', $verify->getApplyTime()),
                'type' => RepoCompanyBill::getTypeName($log->getType()),
                'status' => $verify->getStatus(),
                'status_name' => RepoCompanyVerify::getStatusName($verify->getStatus()),
            ];
        }
        return $list;
    }

    public static function getCompanyVerify($type, $numberPage, $parameters = null)
    {
        $conditions = 'verify.type = :type:';
        $bind = ['type' => $type];
        if (is_null($parameters) === false) {
            list($conditions, $bind) = array_values($parameters);
        }
        if ($conditions) {
            $conditions = $conditions . ' and verify.type = :type:';
            $bind = array_merge($bind, ['type' => $type]);
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("verify.status = :status:", ['status' => RepoCompanyVerify::STATUS_NOT])
            ->andWhere("payment.status = ?0", [\Wdxr\Models\Repositories\CompanyPayment::STATUS_APPLY])
            ->andWhere("company.auditing = :audit:", ['audit' => \Wdxr\Models\Repositories\Company::AUDIT_OK])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = verify.company_id', 'company')
            ->join('Wdxr\Models\Entities\CompanyInfo', 'company.info_id = company_info.id', 'company_info')
            ->join('Wdxr\Models\Entities\CompanyPayment', 'company.id = payment.company_id', 'payment')
            ->join('Wdxr\Models\Entities\Admins', 'admin.id = company.admin_id', 'admin')
            ->leftJoin('Wdxr\Models\Entities\Users', 'partner.id = company.partner_id', 'partner')
            ->leftJoin('Wdxr\Models\Entities\Companys', 'company.partner_id = partner_recommend.user_id', 'partner_recommend')
            ->leftJoin(RepoCompanyVerify::getVerifyEntity($type), 'info.id = verify.data_id', 'info')
            ->columns([
                'verify.verify_time', 'verify.apply_time', 'company.name', 'verify.id', 'company.id as company_id',
                'info.type', 'admin.name as admin_name', "ifnull(partner.name, '') as partner_name", 'company_info.legal_name',
                'payment.status as payment_status', 'company.status as company_status', 'company.auditing',
                'partner_recommend.id as partner_company_id', 'partner_recommend.name as partner_company'
            ])->groupBy('verify.id')
            ->orderBy('verify.verify_time asc, verify.apply_time asc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 15,
            'page' => $numberPage
        ]);
    }

    public static function getCompanyVerify2($type, $numberPage, $where = '1=1')
    {
        $conditions = 'verify.type = :type:';
        $bind = ['type' => $type];

        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("verify.status = :status:", ['status' => RepoCompanyVerify::STATUS_NOT])
            ->andWhere("$where")
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])->distinct(null)
            ->join('Wdxr\Models\Entities\Companys', 'company.id = verify.company_id', 'company')
            ->leftJoin('Wdxr\Models\Entities\Companys', 'company.recommend_id = recommend.id', 'recommend')
            ->join('Wdxr\Models\Entities\CompanyInfo', 'company.info_id = company_info.id', 'company_info')
            ->join('Wdxr\Models\Entities\Admins', 'admin.id = company.admin_id', 'admin')
            ->leftJoin('Wdxr\Models\Entities\Users', 'partner.id = company.partner_id', 'partner')
            ->leftJoin('Wdxr\Models\Entities\Companys', 'company.partner_id = partner_recommend.user_id', 'partner_recommend')
            ->leftJoin(RepoCompanyVerify::getVerifyEntity($type), 'info.id = verify.data_id', 'info')
            ->columns([
                'verify.verify_time', 'verify.apply_time', 'company.name', 'verify.id', 'company.id as company_id',
                'admin.name as admin_name', "ifnull(partner.name, '') as partner_name", 'company_info.legal_name',
                'company.status as company_status', 'company.auditing','info.type', 'recommend.name as recommend_company',
                'partner_recommend.id as partner_company_id', 'partner_recommend.name as partner_company'
            ])->orderBy('verify.verify_time asc, verify.apply_time asc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 15,
            'page' => $numberPage
        ]);
    }

    public static function getPaymentVerifyInfo($verify_id)
    {
        $payments = Services::getStaticModelsManager()->createBuilder()
            ->where('verify.type = :type:', ['type' => RepoCompanyVerify::TYPE_PAYMENT])
            ->andWhere('verify.id = :id:', ['id' => $verify_id])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = verify.company_id', 'company')
            ->join('Wdxr\Models\Entities\CompanyPayment', 'verify.data_id = payment.id', 'payment')
            ->join('Wdxr\Models\Entities\Admins', 'admin.id = company.admin_id', 'admin')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo', 'company.info_id = info.id', 'info')
            ->leftJoin('Wdxr\Models\Entities\Users', 'users.id = company.partner_id', 'users')
            ->columns([
                'verify.verify_time', 'verify.apply_time', 'company.name as company_name', 'verify.id as verify_id',
                'company.id as company_id', "ifnull(info.type,'暂无') as company_type", 'payment.*',
                'admin.name as admin', "ifnull(users.name, '无') as partner"
            ])->limit(1)
            ->getQuery()->execute();

        return $payments;
    }

    static public function getCompanyVerifyInfo($verify_id)
    {
        $companys = Services::getStaticModelsManager()->createBuilder()
            ->where('verify.type = :type:', ['type' => RepoCompanyVerify::TYPE_DOCUMENTS])
            ->andWhere('verify.id = :id:', ['id' => $verify_id])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->join('Wdxr\Models\Entities\Companys', 'company.id = verify.company_id', 'company')
            ->join('Wdxr\Models\Entities\CompanyService', 'company.id = service.company_id', 'service')
            ->join('Wdxr\Models\Entities\CompanyInfo', 'verify.data_id = info.id', 'info')
            ->join('Wdxr\Models\Entities\Admins', 'admin.id = company.admin_id', 'admin')
            ->leftJoin('Wdxr\Models\Entities\CompanyPayment', 'service.payment_id = payment.id', 'payment')
            ->leftJoin('Wdxr\Models\Entities\Users', 'users.id = company.partner_id', 'users')
            ->columns(['verify.verify_time', 'verify.apply_time', 'company.name as company_name', 'verify.id as verify_id', 'verify.status as verify_status',
                'company.id as company_id', 'info.type as company_type', 'info.*', 'company.*', 'service.payment_status as payment'])
            ->limit(1)
            ->getQuery()->execute();

        return $companys[0];
    }

    static public function getCompanyVerifyList($type, $numberPage)
    {
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where('verify.type = :type:', ['type' => $type])
            ->andWhere("verify.status = :status:", ['status' => RepoCompanyVerify::STATUS_NOT])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->leftJoin('Wdxr\Models\Entities\Companys', 'company.id = verify.company_id', 'company')
            ->leftJoin('Wdxr\Models\Entities\CompanyInfo', 'info.id = company.info_id', 'info')
            ->leftJoin('Wdxr\Models\Entities\CompanyReport', 'report.id = verify.data_id', 'report')
            ->columns(['verify.verify_time','report.type as report_type','verify.data_id', 'verify.apply_time', 'company.name', 'verify.id', 'info.type'])
            ->orderBy('verify.verify_time desc, verify.apply_time desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    //获取各分类未审核数量
    static public function getUnCompanyVerifyNum()
    {
        $data['documents']=RepoCompanyVerify::getUnCompanyVerify(RepoCompanyVerify::TYPE_DOCUMENTS);
        $data['bill']=RepoCompanyVerify::getUnCompanyVerify(RepoCompanyVerify::TYPE_BILL);
        $data['credit']=RepoCompanyVerify::getUnCompanyVerify(RepoCompanyVerify::TYPE_CREDIT);
        $data['payment']=RepoCompanyVerify::getUnCompanyVerify(RepoCompanyVerify::TYPE_PAYMENT);
        $data['loan']=RepoCompanyVerify::getUnCompanyVerify(RepoCompanyVerify::TYPE_LOAN);
        return $data;
    }



    /**
     * 获取票据补交记录
     * @param $company_id
     * @param int $page
     * @return array
     * @throws InvalidServiceException
     */
    static public function getCompanyVerifyBillList($company_id, $page = 1,$orderby)
    {
        $company = RepoCompany::getCompanyById($company_id);
        $uid = JWT::getUid();
        $user = (new UserAdmin())->getByDeviceId($uid,UserAdmin::TYPE_USER);
        if($company->getDeviceId() != $uid && $company->getUserId() != $user->getUserId()) {
            throw new InvalidServiceException("该企业不是您的客户，不能使用当前账号操作该企业");
        }
        $verifies = Services::getStaticModelsManager()->createBuilder()
            ->where('verify.company_id = :company_id: and verify.type = :type:', ['company_id' => $company_id, 'type' => RepoCompanyVerify::TYPE_BILL])
            ->from(['verify' => 'Wdxr\Models\Entities\CompanyVerify'])
            ->join("Wdxr\Models\Entities\CompanyBillInfo", 'verify.data_id = bill_info.id', 'bill_info')
            ->columns(['verify.company_id', 'verify.apply_time', 'verify.verify_time', 'verify.type', 'bill_info.type as bill_type', 'verify.status','bill_info.amount as amount'])
            ->orderBy($orderby)
            ->limit(10, $page * 10 - 10)
            ->getQuery()
            ->execute();

        if($verifies->count() === 0) {
            return [];
        }

        $list = [];
        /**
         * @var $verify \Wdxr\Models\Entities\CompanyVerify
         */
        foreach ($verifies as $verify)
        {
            $list[] = [
                'verify_time' => is_null($verify->verify_time) ? "---" : date('Y/m/d H:i:s', $verify->verify_time),
                'apply_time' => date('Y/m/d H:i:s', $verify->apply_time),
                'type' => RepoCompanyBill::getTypeName($verify->bill_type),
                'status' => $verify->status,
                'status_name' => RepoCompanyVerify::getStatusName($verify->status),
                'amount' => '￥'.$verify->amount,
            ];
        }
        return $list;
    }

}