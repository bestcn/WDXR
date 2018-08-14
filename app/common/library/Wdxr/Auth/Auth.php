<?php
namespace Wdxr\Auth;

use Phalcon\Mvc\User\Component;
use Wdxr\Models\Entities\Admins;
use Wdxr\Models\Entities\RememberTokens;
use Wdxr\Models\Entities\SuccessLogins;
use Wdxr\Models\Entities\FailedLogins;
use Wdxr\Models\Repositories\Position;
use Wdxr\Models\Services\UploadService;

/**
 * Wdxr\Auth\Auth
 * Manages Authentication/Identity Management
 */
class Auth extends Component
{

    static $roles = null;
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return Admins
     * @throws Exception
     */
    public function check($credentials)
    {

        // Check if the user exist
        /**
         * @var \Wdxr\Models\Entities\Admins $user
         */
        $user = Admins::findFirstByName($credentials['username']);
        if ($user == false) {
            $this->registerUserThrottling(0);
            throw new Exception('用户名或密码错误');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->getPassword())) {
            $this->registerUserThrottling($user->getId());
            throw new Exception('用户名或密码错误!');
        }

        // Check if the user was flagged
        $this->checkUserFlags($user);

        // Register the successful login
        $this->saveSuccessLogin($user);

        // Save user's roles name to session
        $this->saveUserRoles($user);

        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            $this->createRememberEnvironment($user);
        }

        $this->session->set('auth-identity', [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'position' => $user->positions->name,
            'avatar' => UploadService::getAttachmentUrl($user->getAvatar()),
            'admin_id' => '',
            'company_id' => '',
        ]);

        return $user;
    }

    public function saveUserRoles(Admins $user)
    {
        $position_id = $user->getPositionId();
        $roles = Position::getPositionRoleName($position_id);
        $this->session->set('auth-roles', $roles);
    }

    static public function getUserRoles()
    {
        if(is_null(Auth::$roles)) {
            Auth::$roles = \Phalcon\Di::getDefault()->get('session')->get('auth-roles');
        }
        return Auth::$roles;
    }

    /**
     * Checks if the user status is login
     *
     * @return bool
     */
    public function isLogin()
    {
        return is_array($this->session->get('auth-identity'));
    }

    /**
     * If user status is login redirect to admin index
     *
     * @return bool|\Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function isLoginAndRedirect()
    {
        if($this->isLogin()) {
            return $this->response->redirect('admin/index/index');
        }
        return false;
    }

    public function notLoginAndRedirect()
    {
        if($this->isLogin() === false)
        {
            return $this->response->redirect('admin/public/login');
        }
        return true;
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Wdxr\Models\Entities\Admins $admin
     * @throws Exception
     */
    public function saveSuccessLogin($admin)
    {
        $successLogin = new SuccessLogins();
        $successLogin->setUsersId($admin->getId());
        $successLogin->setIpAddress($this->request->getClientAddress());
        $successLogin->setUserAgent($this->request->getUserAgent());
        $successLogin->setLoginTime(date('Y-m-d H:i:s', time()));
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the effectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->setUsersId($userId);
        $failedLogin->setIpAddress($this->request->getClientAddress());
        $failedLogin->setAttempted(time());
        $failedLogin->setUserAgent($this->request->getUserAgent());
        $failedLogin->save();

        $attempts = FailedLogins::count([
            'ipAddress = ?0 AND attempted >= ?1',
            'bind' => [
                $this->request->getClientAddress(),
                time() - 3600
            ]
        ]);

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Wdxr\Models\Entities\Admins $user
     */
    public function createRememberEnvironment(Admins $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->getEmail() . $user->getPassword() . $userAgent);

        $remember = new RememberTokens();
        $remember->setUsersId($user->getId());
        $remember->setToken($token);
        $remember->setUserAgent($userAgent);
        $remember->setCreatedAt(time());

        if ($remember->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->getId(), $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the cookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Admins::findFirstById($userId);
        if ($user) {

            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {

                $remember = RememberTokens::findFirst([
                    'usersId = ?0 AND token = ?1',
                    'bind' => [
                        $user->id,
                        $token
                    ]
                ]);
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Check if the user was flagged
                        $this->checkUserFlags($user);

                        // Register identity
                        $this->session->set('auth-identity', [
                            'id' => $user->id,
                            'name' => $user->name,
                            'profile' => $user->profile->name
                        ]);

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('admin/index/index');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('frontend/public/adminlogin');
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param \Wdxr\Models\Entities\Admins $user
     * @throws Exception
     */
    public function checkUserFlags(Admins $user)
    {
        if ($user->getStatus() === 0) {
            throw new Exception('该用户尚未激活');
        }

        if ($user->getIsLock() === 1) {
            throw new Exception('该用户已被锁定');
        }

        if ($user->getOnJob() === 0) {
            throw new Exception('该用户已离职');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = Admins::findFirstById($id);
        if ($user == false) {
            throw new Exception('该用户不存在');
        }

        $this->checkUserFlags($user);

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->positions->name
        ]);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Wdxr\Models\Entities\Admins|bool
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {

            $user = Admins::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception('该用户不存在');
            }

            return $user;
        }

        return false;
    }
}
