<?php
namespace Wdxr\Modules\Api\Controllers;

use Lcobucci\JWT\JWT;
use Phalcon\Mvc\View;
use Wdxr\Auth\Auth;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Entities\Regions as EntityRegions;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Area;
use Wdxr\Models\Repositories\Attachment;
use Wdxr\Models\Repositories\Branch;
use Wdxr\Models\Repositories\Citie;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\CompanyInfo;
use Wdxr\Models\Repositories\Devices;
use Wdxr\Models\Repositories\Feedback;
use Wdxr\Models\Repositories\LoansInfo;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\Position;
use Wdxr\Models\Repositories\Province;
use Wdxr\Models\Repositories\Regions;
use Wdxr\Models\Repositories\Salesman;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Contract as ServiceContract;
use Wdxr\Models\Services\Cos;
use Wdxr\Models\Services\SMS;
use Wdxr\Models\Services\PushService;
use Wdxr\Models\Services\TimeService;
use Wdxr\Models\Services\UploadService;
use Wdxr\Modules\Api\Forms\PhoneForm;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\Exceptions\InvalidServiceException;
use Wdxr\Auth\Exception as AuthException;

class ToolsController extends ControllerBase
{

    /**
     * 判断token是否有效
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function validTokenAction()
    {
        return $this->response->setJsonContent(['status' => '1', 'data' => null, 'info' => 'ok']);
    }

    /**
     * 获取指定地区名称
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getAreaItemAction()
    {
        $id = $this->request->getPost("id");
        $name = Regions::getRegionName($id)->name;

        return $this->json(self::RESPONSE_OK, $name, '获取地区名称成功');
    }

    /**
     * 获取有层级的地区列表
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getAreaAction()
    {
        $regions = Regions::getRegions();
        return $this->json(self::RESPONSE_OK, $regions, '获取地区列表成功');
    }

    /**
     * 获取全部地区列表
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getAllAreaAction()
    {
        return $this->json(self::RESPONSE_OK, EntityRegions::find(), '获取地区列表成功');
    }

    public function getProvinceAction()
    {
        return $this->json(self::RESPONSE_OK, Province::getProvinceList(), '获取省份列表成功');
    }

    public function getCityAction()
    {
        $province_id = $this->request->getPost("pid");
        return $this->json(self::RESPONSE_OK, Citie::getCities($province_id), '获取城市列表成功');
    }

    public function getDistrictAction()
    {
        $city_id = $this->request->getPost("pid");
        return $this->json(self::RESPONSE_OK, Area::getArea($city_id), '获取区县列表成功');
    }


    public function contractAction()
    {
        $num = $this->request->getPost('num');
        $sign_id = $this->request->getPost('sign_id');
        $view = $this->view->getRender('Tools', 'contractTemplate', [
            'sign' => ServiceContract::getImage($sign_id),
        ]);

        if(file_put_contents(BASE_PATH."/contract/html/".$num.".html", $view) === false) {
            echo "failed";
        } else {
            echo 'ok';
        }
    }

    public function contractTemplateAction($name)
    {
        $this->view->enable();
        $this->view->disableLevel([
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_MAIN_LAYOUT => true,
        ]);
        $this->response->setContentType('text/html');
        $this->view->setVar('name', $name);
    }

    //登陆获取用户设备接口
    public function updateDeviceAction()
    {
        if($this->request->isPost()){
            $device = new Devices();
            $data['user_id'] = JWT::getUid();
            $data['device_id'] = $this->request->getPost('device_id');
            $data['type'] = $this->request->getPost('type');
            $data['name'] = $this->request->getPost('name') ?: '';
            $data['device_name'] = $this->request->getPost('device_name') ?: '';
            $data['token'] = $this->request->getPost('token') ?: '';
            $device->deleteDevice($data['device_id']);
//            if($device->getDevicesByUserId($data['user_id'])){
//                $device->edit($data['user_id'],$data);
//            }else{
                $device->addNew($data);
//            }
            return $this->json(self::RESPONSE_OK, '', '更新成功');
        }
    }

    //读取更新接口
    public function updateReadAction()
    {
        if($this->request->isPost()) {
            $message = new Messages();
            $message_data = $message->findFirstById($this->request->getPost('id'));
            if($message_data === false) {
                return $this->json(self::RESPONSE_FAILED, null, '选择的消息不存在');
            }
            $message_data->setStatus(PushService::READ);
            if($message_data->save()){
                $user_id = JWT::getUid();
                $unread_data = $message->getUnreadMessageUnread($user_id);
                if($unread_data){
                    $data['num'] = count($unread_data->toArray());
                }else{
                    $data['num'] = 0;
                }
                return $this->json(self::RESPONSE_OK, $data, '更新成功');
            }else{
                return $this->json(self::RESPONSE_FAILED, null, '更新失败');
            }
        }
    }


    public function getMessageAction()
    {
        if($this->request->isPost()) {
            $user_id = JWT::getUid();

            if($this->request->getPost('type')){
                $type = $this->request->getPost('type');
            }else{
                $type = PushService::PUSH_TYPE_WARN;
            }
            $message = new Messages();
            $page = $this->request->getPost('page');
            if(!$page){
                $page = 1;
            }
            $limit = ($page-1)*10;
            $message_data = $message->getUnreadMessage($user_id,$limit,$type)->toArray();
                if(!empty($message_data)){
                    foreach ($message_data as $key=>$val){
                        $message_data[$key]['date'] = $val['time'];
                        $message_data[$key]['time'] = TimeService::humanTime($val['time']);
                    }
                }

            $unread_data = $message->getUnreadMessageUnread($user_id);
            if($unread_data){
                $data['num'] = count($unread_data->toArray());
            }else{
                $data['num'] = 0;
            }
            $data['list'] = $message_data;

            return $this->json(self::RESPONSE_OK, $data, '获取消息成功');
        }
    }

    //删除信息
    public function delMessageAction()
    {
        $user_id = JWT::getUid();
        if($this->request->isPost()) {
            if($id = $this->request->getPost('id')){
                $message = new Messages();
                $message_data = $message->deleteMessage($id);
                $unread_data = $message->getUnreadMessageUnread($user_id);
                if($unread_data){
                    $data['num'] = count($unread_data->toArray());
                }else{
                    $data['num'] = 0;
                }
                if($message_data){
                    return $this->json(self::RESPONSE_OK, $data, '删除成功');
                }else{
                    return $this->json(self::RESPONSE_FAILED, $data, '删除失败');
                }
            }else{
                return $this->json(self::RESPONSE_FAILED, null, '删除失败');
            }
        }
    }

    public function smsAction()
    {
        $form = new PhoneForm();
        if($form->isValid($this->request->getPost()) == false) {
            return $this->json(self::RESPONSE_FAILED, null, (string)$form->getMessages()[0]);
        }
        $phone = $this->request->getPost('phone');
        $token = $this->request->getPost('token');
        $code = SMS::getVerifyCode($token);
        if(SMS::verifyCodeSMS($phone, $code)) {
            return $this->json(self::RESPONSE_OK, $code, '短信验证码发送成功');
        }
        return $this->json(self::RESPONSE_FAILED, $code, '短信验证码发送失败');
    }

    //个人中心
    public function centerAction()
    {
        if($this->request->isPost()){
            try{
                $user_id = JWT::getUid();
                    $admin_id = UserAdmin::getUser($user_id);
                    if($admin_id->getType() == 1){

                    $admin_data = Admin::getAdminById($admin_id->getUserId());
                    //头像
                    if($admin_data->getAvatar()){
                        $attachment = Attachment::getLastAttachmentById($admin_data->getAvatar());
                        $data['object_id'] = MD5($attachment->getObjectId());
                        $data['pic'] = UploadService::getAttachmentUrl($admin_data->getAvatar());
                    }else{
                        $data['object_id'] = '';
                        $data['pic'] = '';
                    }
                    //是否为合伙人
                    $data['type'] = 1;
                    //工号
                    $data['id'] = $admin_data->getId();
                    //姓名
                    $data['name'] = $admin_data->getName();
                    //职位
                    $position_data = Position::getPositionById($admin_data->getPositionId());
                    $data['position'] = $position_data->getName();
                    //联系方式
                    $data['phone'] = $admin_data->getPhone()?:'无';
                    //隶属分站
                    $salesman = new Salesman();
                    $salesman_data = $salesman->getSalesmanByAdminId($admin_data->getId());
                    if($salesman_data){
                        $branch_data = Branch::getBranchById($salesman_data->getBranchId());
                        $data['branch'] = $branch_data->getBranchName();
                    }else{
                        $data['branch'] = '未分配';
                    }
                }else{
                    //头像
                    $user_data = User::getUserById($admin_id->getUserId());
                    if($user_data->getPic()){
                        $data['pic'] = UploadService::getAttachmentUrl($user_data->getPic());
                    }else{
                        $data['pic'] = '';
                    }
                    $company = new Company();
                    $company_data = $company->getCompanyByUserId($admin_id->getUserId());
                    //是否为合伙人
                    $data['type'] = 2;
                    //企业ID
                    $data['id'] = $company_data->getId();
                    //企业名称
                    $data['name'] = $company_data->getName();
                    //职位
                    $user_data = User::getUserById($company_data->getUserId());
                    if($user_data->getIsPartner()){
                        $data['position'] = '事业合伙人';
                    }else{
                        $data['position'] = '普惠';
                    }
                    //联系方式
                    $data['phone'] = $user_data->getPhone()?:'无';
                    //隶属分站
                        $salesman = new Salesman();
                        $salesman_data = $salesman->getSalesmanByAdminId($company_data->getAdminId());
                        if($salesman_data){
                            $branch_data = Branch::getBranchById($salesman_data->getBranchId());
                            $data['branch'] = $branch_data->getBranchName();
                        }else{
                            $data['branch'] = '未分配';
                        }
                }
                return $this->json(self::RESPONSE_OK, $data, '获取个人信息成功');
            }catch (InvalidRepositoryException $exception){
                return $this->json(self::RESPONSE_FAILED, '', $exception->getMessage());
            }catch (InvalidServiceException $e){
                return $this->json(self::RESPONSE_FAILED, '', $e->getMessage());
            }
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    //修改头像
    public function setPicAction($pic)
    {
        if($this->request->isPost()) {
            if(!$pic){
                return $this->json(self::RESPONSE_FAILED, '', '非法参数');
            }
            try {
                $user_id = JWT::getUid();
                $admin_id = UserAdmin::getUser($user_id);
                if ($admin_id->getType() == 1) {
                    $admin_data = Admin::getAdminById($admin_id->getUserId());
                    $admin_data->setPic($pic);
                    if(!$admin_data->save()){
                        return $this->json(self::RESPONSE_FAILED, '', '修改失败');
                    }
                    return $this->json(self::RESPONSE_OK, '', '修改成功');
                }else{
                    $user_data = User::getUserById($admin_id->getUserId());
                    $user_data->setPic($pic);
                    if(!$user_data->save()){
                        return $this->json(self::RESPONSE_FAILED, '', '修改失败');
                    }
                    return $this->json(self::RESPONSE_OK, '', '修改成功');
                }
            }catch (InvalidRepositoryException $exception){
                return $this->json(self::RESPONSE_FAILED, '', $exception->getMessage());
            }catch (InvalidServiceException $e){
                return $this->json(self::RESPONSE_FAILED, '', $e->getMessage());
            }
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    //反馈
    public function feedbackAction()
    {
        if($this->request->isPost()) {
            try{
                $user_id = JWT::getUid();
                if(!$this->request->getPost('content')){
                    return $this->json(self::RESPONSE_FAILED, '', '请填写反馈信息');
                }
                if(strlen($this->request->getPost('content')) < 6){
                    return $this->json(self::RESPONSE_FAILED, '', '请填写6个字符以上的内容');
                }
                $data['device_id'] = $user_id;
                $data['content'] = $this->request->getPost('content');
                if($this->request->getPost('img')){
                    $data['img'] = $this->request->getPost('img');
                }
                $feedback = new Feedback();
                $feedback->addNew($data);
                return $this->json(self::RESPONSE_OK, '', '感谢您的反馈');
            }catch (InvalidRepositoryException $exception){
                return $this->json(self::RESPONSE_FAILED, '', $exception->getMessage());
            }
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    /*
     * 修改手机号
     */
    public function changePhoneAction()
    {
        if($this->request->isPost()){
            $phone = $this->request->getPost('phone');
            $code = $this->request->getPost('code');
            $token = $this->request->getPost('token');
            if (empty($phone)) {
                return $this->json(self::RESPONSE_FAILED, '', '请填写手机号');
            }
            if(SMS::verifyPhone($code,$token) == false){
                return $this->json(self::RESPONSE_FAILED, '', '验证码错误');
            }
            //修改手机号
            try {
                $user_id = JWT::getUid();
                $admin_id = UserAdmin::getUser($user_id);
                if ($admin_id->getType() == 1) {
                    $admin_data = Admin::getAdminById($admin_id->getUserId());
                    $admin_data->setPhone($phone);
                    if(!$admin_data->save()){
                        return $this->json(self::RESPONSE_FAILED, '', '修改失败');
                    }
                    return $this->json(self::RESPONSE_OK, '', '修改成功');
                }else{
                    $user_data = User::getUserById($admin_id->getUserId());
                    $user_data->setPhone($phone);
                    if(!$user_data->save()){
                        return $this->json(self::RESPONSE_FAILED, '', '修改失败');
                    }
                    return $this->json(self::RESPONSE_OK, '', '修改成功');
                }
            }catch (InvalidRepositoryException $exception){
                return $this->json(self::RESPONSE_FAILED, '', $exception->getMessage());
            }catch (InvalidServiceException $e){
                return $this->json(self::RESPONSE_FAILED, '', $e->getMessage());
            }
        }
        return $this->json(self::RESPONSE_FAILED, '', '非法访问');
    }

    /**
     * 退出登录
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function logoutAction()
    {
        try {
            $token = $this->request->isPost() ? $this->request->getPost('token') : $this->request->get('token');
            $device_id = JWT::getUid();
            //判断用户是否登录
            if((new Auth())->isLogin()) {
                (new Auth())->remove();
//                $this->redis->delete('token_'.$device_id);
                //销毁token
                (new UserAuth())->deleteToken($device_id);
                //删除设备信息
                $device = new Devices();
                $device->deleteByToken($token);
                return $this->json(self::RESPONSE_OK, null, '成功退出登录');
            } else {
//                $this->redis->delete('token_'.$device_id);
                //销毁token
                (new UserAuth())->deleteToken($device_id);
                //删除设备信息
                $device = new Devices();
                $device->deleteByToken($token);
                return $this->json(self::RESPONSE_OK, null, '已经退出登录');
            }
        } catch (AuthException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 获取最高级行业分类
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getTopCategoryAction()
    {
        $classification = new \Category\Classification();
        $data = $classification->getTopCategory();

        return $this->json(self::RESPONSE_OK, $data, '获取最高级行业分类成功');
    }

    /**
     * 根据行业代码获取行业分类
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getCategoryAction()
    {
        $code = $this->request->get("code", 'string', 'A', true);
        $classification = new \Category\Classification();
        $data = $classification->getSelectArray($code);

        return $this->json(self::RESPONSE_OK, $data, '获取行业分类成功');
    }

    public function getSignatureAction()
    {
        $expire = $this->request->getPost('expire', 'int', 10);
        $bucket = $this->request->getPost('bucket', 'int', 1);
        switch ($bucket)
        {
            case 1:
                $bucket = Cos::BUCKET_ATTACHMENT;
                break;
            case 2:
                $bucket = Cos::BUCKET_CONTRACT;
                break;
            case 3:
                $bucket = Cos::BUCKET_PUBLIC;
                break;
            default:
                $bucket = Cos::BUCKET_ATTACHMENT;
        }

        $signature = Cos::getSignature($expire, $bucket);

        return $this->json(self::RESPONSE_OK, $signature, '获取文件签名成功');
    }

}