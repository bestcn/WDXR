<?php
/**
 * Created by PhpStorm.
 * User: mayu
 * Date: 2017/9/20
 * Time: 13:43
 */
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Repositories\Messages;
use Wdxr\Models\Repositories\VerifyMessages;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\PushService;
use Wdxr\Models\Services\VerifyMessages as SerVerifyMessages;

class NewsController extends ControllerBase
{
    public function indexAction()
    {

        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\VerifyMessages', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('name', $this->request->get('name'));
        if($data['name'] = $this->request->get('name')){
            $parameters= $data['name'];
        }
        //获取所有消息分页信息
        $paginator = SerVerifyMessages::getMessageListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());

    }


    public function newAction($id)
    {

        $message=VerifyMessages::getVerifyMessagesById($id);
        if($message->getStatus()==VerifyMessages::READ_NOT){
            VerifyMessages::setVerifyMessagesById($id);
        }
        $data=SerVerifyMessages::getMessageInfo($id);
        $this->view->setVar('message',$data);
    }

    public function unreadAction()
    {
        $numberPage = 1; $parameters = [];
        if ($this->request->isPost()) {
            unset($_POST['password']);
            $query = Criteria::fromInput($this->di, 'Wdxr\Models\Entities\VerifyMessages', $_POST);
            $parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        //传递搜索条件
        $this->view->setVar('name', $this->request->get('name'));
        if($data['name'] = $this->request->get('name')){
            $parameters= $data['name'];
        }
        //获取所有未读消息分页信息
        $paginator = SerVerifyMessages::getUnMessageListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());
    }

    public function deleteAction()
    {
        try {
            VerifyMessages::deleteVerifyMessagesById($this->request->getPost('id'));
            $this->flash->success("消息删除成功");
            return $this->response->redirect('admin/news/index');
        } catch (InvalidRepositoryException $exception) {
            $this->flash->error("消息删除失败");
            return $this->response->redirect('admin/news/new/'.$this->request->getPost('id'));
        }
    }



}