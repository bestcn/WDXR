<?php
namespace Wdxr\Models\Services;

use Lcobucci\JWT\JWT;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class SMS extends Services
{

    const VERIFY_CODE = '53471';            //您的验证码是{1}
    const VERIFY_SUCCESS = '53421';         //尊敬的{1}，您的企业申请已经通过审核
    const VERIFY_FAILED = '53422';          //尊敬的{1}，您的企业申请未通过审核
    const BILL_DEADLINE = '53428';          //尊敬的{1}，请及时联系您的客户经理，在{2}之前上传您的票据材料
    const REPORT_DEADLINE = '53441';        //尊敬的{1}，请及时联系您的客户经理，在{2}之前上传您的征信材料
    const SERVICE_DEADLINE = '53445';       //尊敬的{1}，您的服务将在{2}到期，请及时联系您的客户经理，办理续签业务
    const LOAN_SUCCESS = '53461';           //尊敬的{1}，您的普惠申请已经通过审核
    const LOAN_FAILED = '53463';            //尊敬的{1}，您的普惠申请未通过审核
    const CREATE_USER = '53470';            //尊敬的{1}，您的账号是 {2}，初始密码是 {3}。为了您的账户安全，请在初次登录后修改密码
    const APPLY_SUCCESS = '53758';          //尊敬的用户，您的{1}申请已经通过审核
    const APPLY_FAILED = '53757';           //尊敬的用户，您的{1}申请未通过审核

    const TYPE_APPLY = 1;
    const TYPE_BILL = 2;
    const TYPE_REPORT = 3;
    const TYPE_LOAN = 4;

    static private function getTypeName($type)
    {
        switch ($type)
        {
            case self::TYPE_APPLY:
                return "申请";
            case self::TYPE_BILL:
                return "票据";
            case self::TYPE_REPORT:
                return "征信";
            case self::TYPE_LOAN:
                return "普惠";
            default:
                return "";
        }
    }

    /**
     * 发送短信
     * @param $template_code
     * @param $phone
     * @param array $options
     * @return bool
     */
    static private function sendSMS($template_code, $phone, array $options)
    {

    }

    /**
     * 短信验证码
     * @param $phone
     * @return bool
     */
    static public function verifyCodeSMS($phone, $code)
    {
        $service = Services::Hprose('Sms');
        return $service->verify_code($phone,$code);
        //return self::sendSMS(self::VERIFY_CODE, $phone, ['code' => strval($code)]);
    }

    /**
     * 生成手机验证码
     * @return int|mixed|null
     */
    static public function getVerifyCode($token)
    {
        $key = 'sms_verify_code_'.md5($token);
        if(!($code = self::getRedis()->get($key))) {
            $code = rand(1000, 9999);
        }
        self::getRedis()->save($key, $code, 300);
        return $code;
    }

    static public function getCode()
    {
        $key = 'sms_verify_code_'.JWT::getUid();
        if(!($code = self::getRedis()->get($key))) {
            return false;
        }
        return $code;
    }

    /**
     * 验证手机验证码
     * @param $code
     * @return bool
     */
    static public function verifyPhone($code,$token)
    {
        $key = 'sms_verify_code_'.md5($token);
        if(strcmp($code, self::getRedis()->get($key)) === 0) {
            self::getRedis()->delete($key);
            return true;
        }
        return false;
    }

    /**
     * 短信通知用户证件审核通过
     * @param $phone
     * @param $name
     * @param $type
     */
    static public function successSMS($phone, $name, $type)
    {
        $service = Services::Hprose('Sms');
        return $service->verify_success($phone,$name);
//        $type = self::getTypeName($type);
//        return self::sendSMS(self::APPLY_SUCCESS, $phone, ['name' => $name, 'type' => $type]);
    }

    /**
     * 短信通知用户证件审核失败
     * @param $phone
     * @param $name
     * @param $type
     */
    static public function failedSMS($phone, $name, $type)
    {
        $service = Services::Hprose('Sms');
        return $service->verify_failed($phone,$name);
//        $type = self::getTypeName($type);
//        return self::sendSMS(self::APPLY_FAILED, $phone, ['name' => $name, 'type' => $type]);
    }

    /**
     * 短信通知用户在指定日期之前提交完整材料
     * @param $phone
     * @param $name
     * @param $time
     */
    static public function deadlineSMS($phone, $name, $time)
    {
        return self::sendSMS(self::BILL_DEADLINE, $phone, ['name' => $name, 'time' => $time]);
    }


    /**
     *短信通知用户票据的审核期限
     *@param $phone
     *@param $name
     *@param $type
     *@param $time
     */

    static public function BillSMS($phone, $name, $time)
    {
        $service = Services::Hprose('Sms');
        return $service->bill_deadline($phone,$name,$time);
//        return self::sendSMS(self::BILL_DEADLINE, $phone, ['name' => $name, 'type' => $type , 'time' => $time]);
    }

    /**
     *短信通知用户征信的审核期限
     *@param $phone
     *@param $name
     *@param $type
     *@param $time
     */
    static public function ReportSMS($phone, $name, $time)
    {
        $service = Services::Hprose('Sms');
        return $service->report_deadline($phone,$name,$time);
    }

    /**
     * 短信通知用户的服务期限
     * $phone
     * $name
     * $time
     */
    static public function periodSMS($phone, $name, $time)
    {
        $service = Services::Hprose('Sms');
        return $service->service_deadline($phone,$name,$time);
//        return self::sendSMS(self::SERVICE_DEADLINE, $phone, ['name' => $name, 'time' => $time]);
    }

    /**
     * 短信通知普惠通过
     */
    static public function loanSuccessSMS($phone, $name)
    {
        $service = Services::Hprose('Sms');
        return $service->loan_success($phone,$name);
    }

    /**
     * 普惠驳回
     * @param $phone
     * @param $name
     * @return mixed
     */
    static public function loanFailedSMS($phone, $name)
    {
        $service = Services::Hprose('Sms');
        return $service->loan_failed($phone,$name);
    }

    /**
     * 用户通过后通知用户的账号密码信息
     */
    static public function accountSMS($phone, $name ,$username,$password)
    {
        $service = Services::Hprose('Sms');
        return $service->create_user($phone,$name,$username,$password);
    }

    /**
     *多类型审核通过
     */
    static public function apply_success($phone, $type)
    {
        $service = Services::Hprose('Sms');
        return $service->apply_success($phone, $type);
    }

    /**
     * 多类型审核驳回
     */
    static public function apply_failed($phone, $type)
    {
        $service = Services::Hprose('Sms');
        return $service->apply_failed($phone, $type);
    }

    static public function getSmsLogListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            $conditions="phone LIKE :phone:";
            $bind=["phone"=>"%".$parameters."%"];
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from(['log' => 'Wdxr\Models\Entities\SmsLog'])
            ->orderBy('log.time desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

}