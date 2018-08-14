<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Mvc\Controller;
use Wdxr\Modules\Admin\Forms\LoginForm;
use Wdxr\Auth\Exception as AuthException;

/**
 * Class PublicController
 * @package Wdxr\Modules\Frontend\Controllers
 *
 * @property \Wdxr\Captcha $captcha
 * @property \Wdxr\Auth\Auth $auth
 */
class PublicController extends Controller
{

    public function initialize()
    {
        $this->tag->setTitle("冀企管家业务管理后台");
        $this->tag->setTitleSeparator(" - ");
    }

    /**
     * 登录
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginAction()
    {
        $this->tag->prependTitle("登录");
        $this->auth->isLoginAndRedirect();
        $form = new LoginForm();

        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->check([
                        'username' => $this->request->getPost('username'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ]);

                    $this->flashSession->success("登录成功");
                    return $this->response->redirect('admin/index/index');
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * 退出登录
     */
    public function logoutAction()
    {
        $this->auth->remove();
        return $this->response->redirect('admin/public/login');
    }

    /**
     * 图片验证码
     */
    public function captchaAction()
    {
        /**
         * 不使用分层渲染
         */
        $this->view->disable();
        $query = $this->request->getQuery("t");
        if($query == false) {
            return;
        }
        //显示验证码
        $this->captcha->output(true);
    }




}