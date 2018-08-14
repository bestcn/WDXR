<?php
namespace Wdxr\Modules\Frontend\Controllers;

use Wdxr\Modules\Frontend\Forms\AdminLoginForm;
use Wdxr\Auth\Exception as AuthException;

/**
 * Class PublicController
 * @package Wdxr\Modules\Frontend\Controllers
 *
 * @property \Wdxr\Captcha $captcha
 * @property \Wdxr\Auth\Auth $auth
 */
class PublicController extends ControllerBase
{

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