<?php
namespace Wdxr\Modules\Api\Plugins;

use Lcobucci\JWT\JWT;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Repositories\UserAdmin;

class Security extends Plugin
{

    public function __construct($dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $controller = $dispatcher->getControllerName();
        if($controller == 'public') {
            return true;
        }
        $token = $this->request->isPost() ? $this->request->getPost('token') : $this->request->get('token');

        try {
            if(empty($token) || ($uid = JWT::getUidByToken($token)) === false) {
                echo json_encode(['status' => 2, 'data' => null, 'info' => '请先登录']);
                return false;
            }
            if(($status = $this->checkAuthType($uid)) !== true) {
                echo json_encode(['status' => 2, 'data' => null, 'info' => $status]);
                return false;
            }
            if((new UserAuth())->checkToken($uid, $token) === false) {
                echo json_encode(['status' => 2, 'data' => null, 'info' => '请先登录']);
                return false;
            }
//            if(empty($this->redis->get('token_'.$uid))){
//                echo json_encode(['status' => 2, 'data' => null, 'info' => '请先登录']);
//                return false;
//            }
//            if((strcmp($token, $this->redis->get('token_'.$uid)) !== 0)) {
//                echo json_encode(['status' => 2, 'data' => null, 'info' => '您的账号在其他设备登录，如果不是本人操作，请尽快联系管理员']);
//                return false;
//            }
        } catch (\Exception $exception) {
            echo json_encode(['status' => 2, 'data' => null, 'info' => 'token错误']);
            return false;
        }
        return true;
    }

    /**
     * 检查用户类型
     * @param $device_id
     * @return bool
     */
    public function checkAuthType($device_id)
    {
        $user_type = UserAuth::getAuthType($device_id);
        $user = UserAdmin::getUser($device_id);
        if($user_type == UserAuth::AUTH_TYPE_ADMIN) {
            $status = Admin::getAdminStatus($user->getUserId());
        } elseif($user_type == UserAuth::AUTH_TYPE_USER) {
            $status = User::getUserStatus($user->getUserId());
        } else {
            $status = '非法访问';
        }

        return $status;
    }

}
