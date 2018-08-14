<?php
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Exception;
use Wdxr\Auth\Auth;
use Wdxr\Modules\Admin\Forms\AdminPasswordForm;
use Wdxr\Models\Repositories\Admin;

class ProfilesController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->tag->prependTitle("个人资料");
    }

    /**
     * 修改个人密码
     * @throws Exception
     * @internal param $id
     */
    public function passwordAction()
    {
        $form = new AdminPasswordForm;
        $user_id = (new Auth)->getIdentity()['id'];
        if($this->request->isPost()) {
            try {
                if($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $admin = Admin::getAdminById($user_id);
                    if($this->security->checkHash($this->request->getPost('old_password'), $admin->getPassword()) === false) {
                        throw new Exception("旧密码错误");
                    }
                    (new Admin)->changePassword($user_id, $this->request->getPost('password'));

                    $this->flash->success('修改管理员密码成功');
                }
            } catch (Exception $exception) {
                $this->flash->error($exception->getMessage());
            }
        }

        $this->view->setVar('id', $user_id);
        $this->view->setVar('form', $form);
    }

}
