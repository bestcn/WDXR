<?php
namespace Wdxr\Auth;

use Phalcon\Mvc\User\Component;
use Wdxr\Models\Repositories\User;
use Wdxr\Models\Entities\RememberTokens;
use Wdxr\Models\Entities\UserLogins;
use Wdxr\Models\Entities\FailedLogins;

/**
 * Wdxr\Auth\Auth
 * Manages Authentication/Identity Management
 */
class Company_Auth extends Component
{

    static $roles = null;

    public function check($credentials)
    {

        // Check if the user exist
        /**
         * @var \Wdxr\Models\Entities\Admins $user
         */
        $user = User::findFirstByName($credentials['username']);
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



        $this->session->set('auth-company', [
            'id' => $user->getId(),
            'name' => $user->getName(),

        ]);

        return $user;
    }



    static public function getUserRoles()
    {
        if(is_null(Company_Auth::$roles)) {
            Company_Auth::$roles = \Phalcon\Di::getDefault()->get('session')->get('auth-roles');
        }
        return Company_Auth::$roles;
    }

    /**
     * Checks if the user status is login
     *
     * @return bool
     */
    public function isLogin()
    {
        return is_array($this->session->get('auth-company'));
    }

    /**
     * If user status is login redirect to admin index
     *
     * @return bool|\Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function isLoginAndRedirect()
    {

        if($this->isLogin()) {
            return $this->response->redirect('company/index/index');
        }


        return false;
    }

    public function notLoginAndRedirect()
    {
        if($this->isLogin() === false)
        {
            return $this->response->redirect('company/login/index');
        }
        return true;
    }


    public function saveSuccessLogin($user)
    {
        $successLogin = new UserLogins();
        $successLogin->setUsersId($user->getId());
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
                time() - 3600 * 6
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


    public function checkUserFlags($user)
    {
        if ($user->status != 1) {
            throw new Exception('该用户未激活');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-company');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-company');
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

        $this->session->remove('auth-company');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = user::getUserById($id);
        if ($user == false) {
            throw new Exception('该用户不存在');
        }

        $this->checkUserFlags($user);

        $this->session->set('auth-company', [
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
        $identity = $this->session->get('auth-company');
        if (isset($identity['id'])) {

            $user = User::getUserById($identity['id']);
            if ($user == false) {
                throw new Exception('该用户不存在');
            }

            return $user;
        }

        return false;
    }
}
