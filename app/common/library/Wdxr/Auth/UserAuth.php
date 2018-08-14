<?php
namespace Wdxr\Auth;

use Lcobucci\JWT\JWT;
use Phalcon\Mvc\User\Component;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Entities\Companys;
use Wdxr\Models\Entities\UserLogins;
use Wdxr\Models\Entities\Users;
use Wdxr\Models\Repositories\Admin;
use Wdxr\Models\Repositories\Company;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Repositories\UserAdmin;

class UserAuth extends Component
{

    const AUTH_TYPE_ADMIN = 1;
    const AUTH_TYPE_USER = 2;

    public function check($credentials)
    {
        /**
         * @var Users $user
         */
        $user = Users::findFirstByName($credentials['username']);

        if ($user == false) {
            $this->registerUserThrottling(0);
            throw new Exception('用户名或密码错误');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->getPassword())) {
            $this->registerUserThrottling($user->getId());
            throw new Exception('用户名或密码错误!');
        }

        if($user->getStatus() != User::STATUS_ENABLE) {
            throw new Exception("该用户已经被禁用");
        }

        $company = Company::getCompanyByUserId($user->getId());

        $this->saveSuccessLogin($user);

        $this->session->set('auth-identity', [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'position' => '',
            'admin_id' => $company->getAdminId(),
            'company_id' => $company->getId(),
        ]);

        return $user;
    }


    public function registerUserThrottling($userId)
    {
        $failedLogin = new UserLogins();
        $failedLogin->setUsersId($userId);
        $failedLogin->setIpAddress($this->request->getClientAddress());
        $failedLogin->setUserAgent($this->request->getUserAgent());
        $failedLogin->setType(2);
        $failedLogin->save();
    }

    public function saveSuccessLogin(Users $user)
    {
        $successLogin = new UserLogins();
        $successLogin->setUsersId($user->getId());
        $successLogin->setIpAddress($this->request->getClientAddress());
        $successLogin->setUserAgent($this->request->getUserAgent());
        $successLogin->setType(1);
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    static public function setAuthType($device_id, $type)
    {
        \Phalcon\Di::getDefault()->get('redis')->save($device_id.'-user-type', $type, -1);
    }

    static public function getAuthType($device_id)
    {
        return \Phalcon\Di::getDefault()->get('redis')->get($device_id.'-user-type');
    }

    static public function isUserAuth($device_id = null)
    {
        $device_id = is_null($device_id) ? JWT::getUid() : $device_id;
        return \Phalcon\Di::getDefault()->get('redis')->get($device_id.'-user-type') == self::AUTH_TYPE_USER;
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return bool|Admins|Users
     * @throws Exception
     */
    static public function getUser()
    {
        $auth = UserAdmin::getUser(JWT::getUid());
        if($auth->getType() == UserAuth::AUTH_TYPE_ADMIN) {
            $admin = Admin::getAdminById($auth->getUserId());
            if ($admin == false) {
                throw new Exception('该用户不存在');
            }

            return $admin;
        } elseif($auth->getType() == UserAuth::AUTH_TYPE_USER) {
            $user = Users::findFirstById($auth->getUserId());
            if ($user == false) {
                throw new Exception('该用户不存在');
            }

            return $user;
        }

        return false;
    }

    /**
     * @return bool|Admins
     * @throws Exception
     */
    static public function getAdminInfo()
    {
        $admin_id = self::getAdminId();
        if ($admin_id) {
            /**
             * @var Admins $user
             */
            $user = Admins::findFirstById($admin_id);
            if ($user == false) {
                throw new Exception('该业务员不存在');
            }

            return $user;
        }

        return false;
    }

    /**
     * 获取当前业务员ID
     * @return int
     * @throws Exception
     */
    static public function getAdminId($device_id = null)
    {
        $device_id = $device_id ? : JWT::getUid();
        $user = UserAdmin::getUser($device_id);
        if($user->getType() == UserAuth::AUTH_TYPE_USER) {
            $company = Company::getCompanyByUserId($user->getUserId());
            return $company->getAdminId();
        } else {
            return $user->getUserId();
        }
    }

    /**
     * 获取合伙人的公司ID
     * @return bool|Companys
     */
    static public function getUserCompanyInfo()
    {
        $user = UserAdmin::getUser(JWT::getUid());
        if(UserAuth::isUserAuth()) {
            $company = Company::getCompanyByUserId($user->getId());
            return $company;
        } else {
            return null;
        }
    }

    /**
     * 获取合伙人ID
     * @return bool|int
     */
    static public function getPartnerId($device_id = null)
    {
        $device_id = $device_id ? : JWT::getUid();
        $user = UserAdmin::getUser($device_id);
        if($user->getType() == UserAdmin::TYPE_USER) {
            return $user->getUserId();
        }

        return null;
    }

    public function loginToken($device_id)
    {
        $token = JWT::generateToken($device_id);
        $tokens = $this->redis->get('token_'.$device_id);
        $tokens = is_string($tokens) ? [$tokens] : $tokens;
        $tokens = is_array($tokens) ? $tokens : [];

        array_push($tokens, (string)$token);

        $this->redis->save('token_'.$device_id, $tokens, -1);
        return $token;
    }

    public function checkToken($device_id, $token)
    {
        $tokens = $this->redis->get('token_'.$device_id);
        $tokens = is_array($tokens) ? $tokens : (array)$tokens;

        if(in_array($token, $tokens) === false) {
            return false;
        }
        return true;
    }

    public function deleteToken($device_id)
    {
        $token = $this->request->isPost() ? $this->request->getPost('token') : $this->request->get('token');

        $tokens = $this->redis->get('token_'.$device_id);
        $tokens = is_array($tokens) ? $tokens : (array)$tokens;

        $key = array_search($token, $tokens);
        unset($tokens[$key]);

        $new_tokens = [];
        foreach ($tokens as $val){
            $new_tokens[] = $val;
        }
        $this->redis->save('token_'.$device_id,$new_tokens,-1);
        return true;
    }

    public function deleteDeviceToken($device_id)
    {
        $device_token = $this->request->isPost() ? $this->request->getPost('device_token') : $this->request->get('device_token');

        $tokens = $this->redis->get('token_'.$device_id);
        $tokens = is_array($tokens) ? $tokens : (array)$tokens;

        $key = array_search($device_token, $tokens);
        unset($tokens[$key]);
        $new_tokens = [];
        foreach ($tokens as $val){
            $new_tokens[] = $val;
        }
        $this->redis->save('token_'.$device_id,$new_tokens,-1);
        return true;
    }

    public function deleteDevicesToken($device_id,$device_token)
    {
        $tokens = $this->redis->get('token_'.$device_id);
        $tokens = is_array($tokens) ? $tokens : (array)$tokens;
        $key = array_search($device_token, $tokens);
        unset($tokens[$key]);
        $new_tokens = [];
        foreach ($tokens as $val){
            $new_tokens[] = $val;
        }
        $this->redis->save('token_'.$device_id,$new_tokens,-1);
        return true;
    }

    /**
     * 唯一登录标示
     * @return string
     */
    public function getTokenKey()
    {
        $token = $this->request->isPost() ? $this->request->getPost('token') : $this->request->get('token');
        return md5($token);
    }

    /**
     * 获取当前Token
     * @return string
     */
    public function getToken()
    {
        $token = $this->request->isPost() ? $this->request->getPost('token') : $this->request->get('token');
        return $token;
    }

}