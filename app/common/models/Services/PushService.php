<?php
namespace Wdxr\Models\Services;

use \Push\Request\V20160801 as Push;
use Wdxr\Models\Repositories\Devices;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;

class PushService extends Services
{
    static private $access_id = 'LTAIfacKo3zdljvt';
    static private $access_key = 'VuTvHaLAvgClv5BcDiHOys3FOIqri0';
    static private $app_key = '24494081';

    static private $client = null;

    const DEVICE_TYPE_IOS = 'iOS';
    const DEVICE_TYPE_ANDROID = 'ANDROID';
    const DEVICE_TYPE_ALL = 'ALL';

    const PUSH_TYPE_MESSAGE = 'MESSAGE';
    const PUSH_TYPE_NOTICE = 'NOTICE';

    const PUSH_TARGET_ACCOUNT = 'ACCOUNT';
    const PUSH_TARGET_DEVICE = 'DEVICE';
    const PUSH_TARGET_TAG = 'TAG';
    const PUSH_TARGET_ALL = 'ALL';

    //推送类型
    const PUSH_TYPE_WARN = 1;
    const PUSH_TYPE_SYS = 2;


    const UNREAD = 1;
    const READ = 2;
    const DEL = 0;

    /**
     * @var null|self
     */
    static private $instance = null;

    /**
     * @var null|Push\PushRequest
     */
    static private $request = null;

    static private function getClient()
    {
        if(is_null(self::$client)) {
            $iClientProfile = \DefaultProfile::getProfile("cn-shanghai", self::$access_id, self::$access_key);
            self::$client =  new \DefaultAcsClient($iClientProfile);
        }
        return self::$client;
    }

    static private function getRequest()
    {
        if(is_null(self::$request)) {
            self::$request = new Push\PushRequest();
        }
        return self::$request;
    }

    static public function client()
    {
        self::getRequest();
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setDeviceType($type)
    {
        self::$request->setDeviceType($type); //设备类型 ANDROID iOS ALL.

        return self::$instance;
    }

    public function setPushType($type)
    {
        self::$request->setPushType($type); //消息类型 MESSAGE NOTICE

        return self::$instance;
    }

    /**
     * @param $title
     * @param $body
     * @return PushService
     */
    public function setTarget($title, $body )
    {
        // 推送目标
        self::$request->setAppKey(self::$app_key);
        //self::$request->setDeviceType("ALL"); //设备类型 ANDROID iOS ALL.
        //self::$request->setPushType("NOTICE"); //消息类型 MESSAGE NOTICE
        self::$request->setTitle($title); // 消息的标题
        self::$request->setBody($body); // 消息的内容

        return self::$instance;
    }


    public function toiOS($num,$parameters = null)
    {
        // 推送配置: iOS
        self::$request->setiOSBadge($num); // iOS应用图标右上角角标
        self::$request->setDeviceType(self::DEVICE_TYPE_IOS);
        self::$request->setiOSSilentNotification("false");//是否开启静默通知
        self::$request->setiOSMusic("default"); // iOS通知声音
        self::$request->setiOSApnsEnv("PRODUCT");//iOS的通知是通过APNs中心来发送的，需要填写对应的环境信息。"DEV" : 表示开发环境 "PRODUCT" : 表示生产环境
        self::$request->setiOSRemind("false"); // 推送时设备不在线（既与移动推送的服务端的长连接通道不通），则这条推送会做为通知，通过苹果的APNs通道送达一次(发送通知时,Summary为通知的内容,Message不起作用)。注意：离线消息转通知仅适用于生产环境
        self::$request->setiOSRemindBody("iOSRemindBody");//iOS消息转通知时使用的iOS通知内容，仅当iOSApnsEnv=PRODUCT && iOSRemind为true时有效
        if(is_null($parameters) === false) {
            $json = \GuzzleHttp\json_encode($parameters);
            self::$request->setiOSExtParameters($json); //自定义的kv结构,开发者扩展用 针对iOS设备
        }

        return self::$instance;
    }

    public function toAndroid($parameters = null)
    {
        // 推送配置: Android
        self::$request->setAndroidNotifyType("BOTH");//通知的提醒方式 "VIBRATE" : 震动       "SOUND" : 声音          "BOTH" : 声音和震动     NONE : 静音
        self::$request->setAndroidNotificationBarType(1);//通知栏自定义样式0-100
        self::$request->setDeviceType(self::DEVICE_TYPE_ANDROID);
        self::$request->setAndroidOpenType("ACTIVITY");//点击通知后动作 "APPLICATION" : 打开应用 "ACTIVITY" : 打开AndroidActivity "URL" : 打开URL "NONE" : 无跳转
//        self::$request->setAndroidOpenUrl("http://www.aliyun.com");//Android收到推送后打开对应的url,仅当AndroidOpenType="URL"有效
        self::$request->setAndroidActivity("com.example.hbgrb.wdxr.MessageList");//设定通知打开的activity，仅当AndroidOpenType="Activity"有效
        self::$request->setAndroidMusic("default");//Android通知音乐
//        self::$request->setAndroidXiaoMiActivity("com.ali.demo.MiActivity");//设置该参数后启动小米托管弹窗功能, 此处指定通知点击后跳转的Activity（托管弹窗的前提条件：1. 集成小米辅助通道；2. StoreOffline参数设为true
//        self::$request->setAndroidXiaoMiNotifyTitle("Mi Title");
//        self::$request->setAndroidXiaoMiNotifyBody("Mi Body");
        if(is_null($parameters) === false) {
            $json = \GuzzleHttp\json_encode($parameters);
            self::$request->setiOSExtParameters($json); //自定义的kv结构,开发者扩展用 针对iOS设备
        }

        return self::$instance;
    }

    public function setBasicConfig()
    {
        // 推送控制
        $pushTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+3 second'));//延迟3秒发送
        self::$request->setPushTime($pushTime);
        $expireTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 day'));//设置失效时间为1天
        self::$request->setExpireTime($expireTime);
        self::$request->setStoreOffline("true"); // 离线消息是否保存,若保存, 在推送时候，用户即使不在线，下一次上线则会收到
        return self::$instance;
    }

    public function push($target_value, $target = self::PUSH_TARGET_ACCOUNT )
    {
        self::$request->setTarget($target); //推送目标: DEVICE:推送给设备;   ACCOUNT:推送给指定帐号,    TAG:推送给自定义标签;      ALL: 推送给全部
        self::$request->setTargetValue($target_value); //根据Target来设定，如Target=device, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
        $response = self::getClient()->getAcsResponse(self::$request);
        return $response;
    }


    //IOS移动推送
    public function pushIOS($content,$id,$parameters = null)
    {
        $message = new Messages();
        foreach ($id as $key=>$val){
            $data['type'] = $content['type'];
            $data['title'] = $content['title'];
            $data['body'] = $content['body'];
            $data['status'] = self::UNREAD;
            $data['user_id'] = $val['user_id'];
            $message->addNew($data);

            //角标数量
            $num = count($message->getUnreadMessageUnread($val['user_id'])->toArray());

        //默认按账号推送
        PushService::client()
            ->setTarget($content['title'],$content['body'])
            ->setPushType(PushService::PUSH_TYPE_NOTICE)
            ->toiOS($num,$parameters)
            ->setBasicConfig()
            ->push($val['device_id'],self::PUSH_TARGET_DEVICE);

        }
    }

    /**
     * @param $content
     * @param $id
     * @param null $parameters
     * @throws \Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException
     * 新推送IOS
     */
    public function newPushIOS($content,$id,$parameters = null)
    {
        $message = new Messages();
        foreach ($id as $key=>$val){
            $data['type'] = $content['type'];
            $data['title'] = $content['title'];
            $data['body'] = $content['body'];
            $data['status'] = self::UNREAD;
            $data['user_id'] = $val['user_id'];
            $message->addNew($data);

            //角标数量
            $num = count($message->getUnreadMessageUnread($val['user_id'])->toArray());
            //新IOS推送
            $new_push_service = new NewPushService();
            $new_push_service->DemoPushSingleDeviceIOS($content,$val['device_id'],$num);
        }
    }

    //Android移动推送
    public function pushAndroid($content,$id,$parameters = null)
    {

        $message = new Messages();
        foreach ($id as $key => $val) {
            $data['type'] = $content['type'];
            $data['title'] = $content['title'];
            $data['body'] = $content['body'];
            $data['status'] = self::UNREAD;
            $data['user_id'] = $val['user_id'];
            $message->addNew($data);

            //角标数量
            $num = count($message->getUnreadMessageUnread($val['user_id'])->toArray());
            //默认按账号推送
            PushService::client()
                ->setTarget($content['title'], $content['body'])
                ->setPushType(PushService::PUSH_TYPE_NOTICE)
                ->toAndroid($parameters)
                ->setBasicConfig()
                ->push($val['device_id'],self::PUSH_TARGET_DEVICE);
        }
    }

    /**
     * @param $content
     * @param $id
     * @param null $parameters
     * @throws \Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException
     * 新推送安卓
     */
    public function newPushAndroid($content,$id,$parameters = null)
    {
        $message = new Messages();
        foreach ($id as $key => $val) {
            $data['type'] = $content['type'];
            $data['title'] = $content['title'];
            $data['body'] = $content['body'];
            $data['status'] = self::UNREAD;
            $data['user_id'] = $val['user_id'];
            $message->addNew($data);

//            //角标数量
//            $num = count($message->getUnreadMessageUnread($val['user_id'])->toArray());
            //新安卓推送
            $new_push_service = new NewPushService();
            $new_push_service->DemoPushSingleDeviceNotification($content,$val['device_id']);
        }
    }

    public function pushAll($content,$parameters=null)
    {
        $device = new Devices();
        $device_data = $device->getLast()->toArray();
        $ANDROID = array();
        $IOS = array();
        foreach($device_data as $key=>$val){
            if($val['type'] == self::DEVICE_TYPE_ANDROID){
                $ANDROID[$key]['device_id'] = $val['device_id'];
                $ANDROID[$key]['user_id'] = $val['user_id'];
            }
            if($val['type'] == self::DEVICE_TYPE_IOS){
                $IOS[$key]['device_id'] = $val['device_id'];
                $IOS[$key]['user_id'] = $val['user_id'];
            }
        }
        $this->pushAndroid($content,$ANDROID,$parameters);
        $this->pushIOS($content,$IOS,$parameters);
    }

    /**
     * @param $content
     * @param null $parameters
     * 新推送所有用户接口
     */
    public function newPushAll($content,$parameters=null)
    {
        $device = new Devices();
        $device_data = $device->getLast()->toArray();
        $ANDROID = array();
        $IOS = array();
        foreach($device_data as $key=>$val){
            if($val['type'] == self::DEVICE_TYPE_ANDROID){
                $ANDROID[$key]['device_id'] = $val['device_id'];
                $ANDROID[$key]['user_id'] = $val['user_id'];
            }
            if($val['type'] == self::DEVICE_TYPE_IOS){
                $IOS[$key]['device_id'] = $val['device_id'];
                $IOS[$key]['user_id'] = $val['user_id'];
            }
        }
        $this->newPushAndroid($content,$ANDROID,$parameters);
        $this->newPushIOS($content,$IOS,$parameters);
    }


    public function pushResult($content,$user_id,$parameters=null)
    {
        $device = new Devices();
        $ANDROID = array();
        $IOS = array();
        $device_data = $device->getDevicesByUserId($user_id);

        if($device_data){
            if($device_data->getType() == self::DEVICE_TYPE_ANDROID){
                $ANDROID[0]['device_id'] = $device_data->getDeviceId();
                $ANDROID[0]['user_id'] = $device_data->getUserId();
            }
            if($device_data->getType() == self::DEVICE_TYPE_IOS){
                $IOS[0]['device_id'] = $device_data->getDeviceId();
                $IOS[0]['user_id'] = $device_data->getUserId();
            }
            $this->pushAndroid($content,$ANDROID,$parameters);
            $this->pushIOS($content,$IOS,$parameters);
        }
    }

    /**
     * @param $content
     * @param $user_id
     * @param null $parameters
     * 新推送单条消息接口
     */
    public function newPushResult($content,$user_id,$parameters=null)
    {
        $device = new Devices();
        $ANDROID = array();
        $IOS = array();
        $device_data = $device->getDevicesByUserId($user_id);

        if($device_data){
            if($device_data->getType() == self::DEVICE_TYPE_ANDROID){
                $ANDROID[0]['device_id'] = $device_data->getDeviceId();
                $ANDROID[0]['user_id'] = $device_data->getUserId();
            }
            if($device_data->getType() == self::DEVICE_TYPE_IOS){
                $IOS[0]['device_id'] = $device_data->getDeviceId();
                $IOS[0]['user_id'] = $device_data->getUserId();
            }
            $this->newPushAndroid($content,$ANDROID,$parameters);
            $this->newPushIOS($content,$IOS,$parameters);
        }
    }

    /**
     * 推送征信报告
     * @param $device_id
     * @param $username
     * @param $status
     */
    static public function pushReport($device_id, $username, $status)
    {
        $content['title'] = "(".$username.")征信报告通知";
        if($status == RepoCompanyVerify::STATUS_OK) {
            $content['body'] = $username.'的征信报告已经通过审核';
        } else {
            $content['body'] = $username.'的征信报告被驳回,请检查后重新提交';
        }
        $content['type'] = PushService::PUSH_TYPE_WARN;
        (new self)->newPushResult($content, $device_id);
    }

    /**
     * 企业申请推送
     * @param $device_id
     * @param $name
     * @param $status
     * @return bool
     */
    static public function companyApply($device_id, $name, $status)
    {
        /*$content = ['type' => PushService::PUSH_TYPE_WARN];
        if($status == \Wdxr\Models\Repositories\CompanyVerify::STATUS_OK) {
            $content['title'] = "({$name})审核通过通知";
            $content['body'] = "{$name}的企业信息通过审核";
        } elseif($status == \Wdxr\Models\Repositories\CompanyVerify::STATUS_FAIL) {
            $content['title'] = "({$name})审核驳回通知";
            $content['body'] = "{$name}的企业申请信息已被驳回,请检查后重新提交";
        } else {
            return false;
        }
        return (new PushService())->pushResult($content, $device_id);*/
        return true;
    }

    public function noticeVerify($device_id, $company_id, $status, $type = \Wdxr\Models\Repositories\CompanyService::TYPE_PARTNER)
    {
        $title = $this->getVerifyPushTitle($type);
        $company = \Wdxr\Models\Repositories\Company::getCompanyById($company_id);
        $company_info = \Wdxr\Models\Repositories\CompanyInfo::getCompanyInfoById($company->getInfoId());
        if($status == \Wdxr\Models\Repositories\CompanyVerify::STATUS_OK) {
            SMS::apply_success($company_info->getContactPhone(), $title);

            $content['title'] = $company_info->getLegalName() . "的".$title."通知";
            $content['body'] = $company_info->getLegalName() . '的'.$title.'凭证已经通过审核';

            //发送App首页系统消息
            ($type == \Wdxr\Models\Repositories\CompanyService::TYPE_ORDINARY) || (new Messages())->addNewMessage([
                'title' => '最新入驻',
                'body' => "恭喜".$company->getName()."成功入驻冀企管家成为事业合伙人"
            ]);
        } else {
            SMS::apply_failed($company_info->getContactPhone(), $title);

            $content['title'] = $company_info->getLegalName() . "的".$title."通知";
            $content['body'] = $company_info->getLegalName() . '的'.$title.'信息被驳回，请检查后重新提交';
        }
        $content['type'] = PushService::PUSH_TYPE_WARN;
        return $this->newPushResult($content, $device_id);
    }

    public function getVerifyPushTitle($type)
    {
        switch ($type)
        {
            case \Wdxr\Models\Repositories\CompanyService::TYPE_PARTNER:
                $title = '缴费';
                break;
            case \Wdxr\Models\Repositories\CompanyService::TYPE_ORDINARY;
                $title = '普惠';
                break;
            default:
                return '客户类型参数错误';
        }

        return $title;
    }

}