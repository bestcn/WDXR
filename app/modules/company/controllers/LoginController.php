<?php
namespace Wdxr\Modules\Company\Controllers;

use Wdxr\Auth;
use Wdxr\Auth\Exception;
use Phalcon\Mvc\Controller;
use Wdxr\Models\Entities\Users;
use Wdxr\Models\Repositories\User;
use Wdxr\Modules\Company\Controllers\SelectController as Select;

class LoginController extends Controller
{

    public function indexAction()
    {
        $auth = new Auth\Company_Auth();
        if($this->request->getPost()){

            try {
                    $auth->check([
                        'username' => $this->request->getPost('username'),
                        'password' => $this->request->getPost('password')
                    ]);
                    $user_info = $this->session->get('auth-company');
                    $user = new User();
                    $user_data = $user->getUserById($user_info['id']);
                    $user_data->setLastLoginIp($user_data->getDI()->get('request')->getClientAddress());
                    $user_data->setLastLoginTime(time());
                    $user_data->save();
                    return $this->response->redirect('company/index/result/1/登陆成功');

            }catch(Exception $e){
                $this->loginfalseAction($e->getMessage());
            }
        }else{
            $auth->isLoginAndRedirect();
        }
    }

    public function removeAction()
    {
        $auth = new Auth\Company_Auth();
        $auth->remove();
        return $this->response->redirect('company/index/index/');
    }

    public function loginfalseAction($content)
    {
        $this->view->setVar('status', 0);
        $this->view->setVar('content', $content);
        $this->view->setVar('url', '/company/login');
        $this->view->pick('login/result');
    }

    //申请页面
    public function applyAction()
    {
        if($this->request->isPost()){

        }


        $select = new Select();
        $province = $select->get_provinceAction();
        $this->view->setVar('province', $province);

        $city = $select->get_citieAction();
        $this->view->setVar('city', $city);

        $area = $select->get_areaAction();
        $this->view->setVar('area', $area);

    }

}