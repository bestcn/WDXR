<?php
namespace Wdxr\Modules\Api\Controllers;

use function GuzzleHttp\Psr7\str;
use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Auth\Exception as AuthException;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\User;
use Wdxr\Modules\Api\Forms\AdminLoginForm;
use Wdxr\Models\Entities\Levels as EntityLevel;
use Wdxr\Models\Repositories\Version;

/**
 * Class PublicController
 * @package Wdxr\Modules\Api\Controllers
 * @property \Wdxr\Auth\Auth $auth
 */
class PublicController extends ControllerBase
{

    /**
     * 登录
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginAction()
    {
        try {
            $form = new AdminLoginForm();
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    throw new AuthException($message);
                }
            } else {
                $type = $this->request->getPost('type');
                if($type == UserAuth::AUTH_TYPE_ADMIN) {
                    $user = $this->auth->check([
                        'username' => $this->request->getPost('username'),
                        'password' => $this->request->getPost('password'),
                    ]);
                    $device_id = UserAdmin::getDeviceId($user->getId(), UserAdmin::TYPE_ADMIN);
                    $token = (new UserAuth())->loginToken($device_id);
                    $data = [
                        'id' => $device_id,
                        'user_id' => $user->getId(),
                        'type' => UserAuth::AUTH_TYPE_ADMIN,
                        'name' => $user->getName(),
                        'position' => $user->positions->getName(),
                        'position_id' => $user->getPositionId(),
                        'token' => (string)$token
                    ];
                    UserAuth::setAuthType($device_id, UserAuth::AUTH_TYPE_ADMIN);
                } elseif ($type == UserAuth::AUTH_TYPE_USER) {
                    $user = (new UserAuth())->check([
                        'username' => $this->request->getPost('username'),
                        'password' => $this->request->getPost('password'),
                    ]);
                    $device_id = UserAdmin::getDeviceId($user->getId(), UserAdmin::TYPE_USER);
                    $token = (new UserAuth())->loginToken($device_id);
                    $data = [
                        'id' => $device_id,
                        'user_id' => $user->getId(),
                        'type' => UserAuth::AUTH_TYPE_USER,
                        'name' => $user->getName(),
                        'position' => '',
                        'position_id' => '',
                        'token' => (string)$token
                    ];
                    UserAuth::setAuthType($device_id ,UserAuth::AUTH_TYPE_USER);
                } else {
                    throw new AuthException('非法请求');
                }
                $unread_data = (new Messages())->getUnreadMessageUnread($device_id);

                $data['num'] = $unread_data ? count($unread_data->toArray()) : 0;

                return $this->json(self::RESPONSE_OK, $data, '登录成功');
            }
        } catch (AuthException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

    /**
     * 退出登录
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */


    public function logoutAction()
    {
        try {
            if($this->auth->isLogin()) {
                $this->auth->remove();
                return $this->json(self::RESPONSE_OK, null, '成功退出登录');
            } else {
                return $this->json(self::RESPONSE_OK, null, '已经退出登录');
            }
        } catch (AuthException $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }


    /**
     * 获取企业级别
     * @return null|\Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function getCompanyLevelsAction()
    {
        try {
            $levels = EntityLevel::find(["columns" => "id, level_name as name, level_money as amount", "conditions" => 'level_status = :status:', 'bind' => ['status' => 1]]);
            return $this->json(self::RESPONSE_OK, $levels, '获取企业级别成功');
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

//获取版本号
    public function getVersionAction()
    {
        if($this->request->isPost()){
            $id = $this->request->getPost('id');
            $version = new Version();
            $version_data = $version->getVersion();
            if($version_data){
                if($version_data->getId() == $id){
                    return $this->json(self::RESPONSE_FAILED, $version_data->getId(), '当前为最新版本');
                }else{
                    return $this->json(self::RESPONSE_OK, $version_data->getUrl(), '获取版本信息成功');
                }
            }else{
                return $this->json(self::RESPONSE_FAILED, 0, '没有版本信息');
            }
        }else{
            return $this->json(self::RESPONSE_FAILED, 0, '非法访问');
        }
    }



}