<?php
namespace Wdxr\Modules\Api\Controllers;

use Lcobucci\JWT\JWT;
use Wdxr\Auth\UserAuth;
use Wdxr\Models\Repositories\UserAdmin;
use Wdxr\Models\Services\Admin;
use Wdxr\Models\Services\User;
use Wdxr\Modules\Api\Forms\AdminPasswordForm;
use Phalcon\Exception;

class ProfilesController extends ControllerBase
{

    public function changePasswordAction()
    {
        $user = UserAdmin::getUser(JWT::getUid());
        $old_password = $this->request->getPost('old_password');
        $new_password = $this->request->getPost('password');
        $form = new AdminPasswordForm;
        try {
            if($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    throw new Exception($message->getMessage());
                }
            } else {
                if($user->getType() == UserAdmin::TYPE_ADMIN) {
                    Admin::changePassword($user->getUserId(), $old_password, $new_password);
                } else {
                    User::changePassword($user->getUserId(), $old_password, $new_password);
                }
                return $this->json(self::RESPONSE_OK, null, '密码修改成功');
            }
        } catch (Exception $exception) {
            return $this->json(self::RESPONSE_FAILED, null, $exception->getMessage());
        }
    }

}