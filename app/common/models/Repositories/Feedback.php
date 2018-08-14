<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/19
 * Time: 17:01
 */
namespace Wdxr\Models\Repositories;

use Wdxr\Models\Entities\Feedback as EntityAdmin;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
class Feedback
{

    /**
     * @param $id
     * @return EntityAdmin
     */
    static public function getFeedbackById($id)
    {
        /**
         * @var $admin EntityAdmin
         */
        $Manage = EntityAdmin::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        return $Manage;
    }

    public function getLast()
    {
        return EntityAdmin::query()
            ->orderBy('id asc')
            ->execute();
    }


    public function addNew($data)
    {
        $Feedback = new EntityAdmin();
        $Feedback->setContent($data["content"]);
        $Feedback->setImg($data['img']);
        $Feedback->setDeviceId($data['device_id']);
        if (!$Feedback->save()) {
            throw new InvalidRepositoryException($Feedback->getMessages()[0]);
        }
        return true;
    }

    static public function deleteFeedback($id)
    {
        $Feedback = Feedback::getFeedbackById($id);
        if (!$Feedback) {
            throw new InvalidRepositoryException("未找到反馈");
        }

        if (!$Feedback->delete()) {
            throw new InvalidRepositoryException("反馈删除失败");
        }

        return true;
    }

}