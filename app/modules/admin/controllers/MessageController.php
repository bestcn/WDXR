<?php
/**
 * Created by PhpStorm.
 * User: dh
 * Date: 2017/9/11
 * Time: 9:43
 */
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\PushService;

class MessageController extends ControllerBase
{
    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Messages', $_POST);
            $parameters = $query->getParams();
        } else {
            $data['type'] = 3;
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\Messages', $data);
            $parameters = $query->getParams();
            $numberPage = $this->request->getQuery("page", "int");
        }

        $paginator = \Wdxr\Models\Services\Messages::getVersionListPagintor($parameters, $numberPage);

        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function newAction()
    {
        try {
            if($this->request->isPost()) {
                $branch = new Messages();
                $branch->addNewMessage($this->request->getPost());
                $this->flash->success('添加成功');
                $this->dispatcher->forward([
                    'controller' => "message",
                    'action' => 'index'
                ]);
                return;
            }
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error($exception->getMessage());
        }
    }

    public function deleteAction()
    {
        $this->view->disable();
        if($this->request->isPost()){
            try{
                (new Messages())->delete($this->request->getPost('id'));
                $this->flash->success('删除成功');
                $this->dispatcher->forward([
                    'action' => 'index'
                ]);
                return;
            }catch (InvalidRepositoryException $exception){
                $this->flash->error($exception->getMessage());
            }
        }
    }

    public function pushAction()
    {
        $this->view->disable();
        if($this->request->isPost()){
            if($id = $this->request->getPost('id')){
                $message_data = Messages::findFirstById($id);
                if($message_array = $message_data->toArray()) {
                    $push_service = new PushService();
                    $message_array['type'] = 2;
                    $push_service->newPushAll($message_array);
                    $this->flash->success('推送成功');
                }
            }
        }else{
            $this->flash->error('推送失败');
        }
    }

}