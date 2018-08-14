<?php
namespace Wdxr\Modules\Admin\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl;
use Wdxr\Auth\Auth;
use \Phalcon\Mvc\Dispatcher\Exception as DispatchException;

class Security extends Plugin
{

    public function __construct($dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
    }

    public function beforeException(Event $event, Dispatcher $dispatcher, $exception)
    {
        if ($exception instanceof DispatchException) {
            $dispatcher->forward(array(
                'controller' => 'error',
                'action' => 'notFound'
            ));
            return false;
        }

        //异常日志
        //todo 关闭异常
        $this->logger->error($exception->getMessage() . "\n" .$exception->getTraceAsString(). "\n");
        // handles exceptions
//        $dispatcher->forward(array(
//            'controller' => 'error',
//            'action' => 'fatal'
//        ));

        return false;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $controller = $dispatcher->getControllerName();
        if($controller == 'public') {return true;}
        if($this->auth->isLogin() === false)
        {
            return $this->response->redirect('admin/public/login');
        }
        //获取控制器和动作
        $action     = $dispatcher->getActionName();
        $this->acl->addResource(ucfirst($controller), [$action]);

        if($this->isAdmin()) {
            return true;
        }
        if ($this->isAllowed($controller, $action) != Acl::ALLOW) {
            $this->flash->error('无权限访问！');
            $dispatcher->forward(array('controller'=>'index','action'=>'index'));
            return false;
        }
    }

    public function isAllowed($controller, $action)
    {
        $allowed = Acl::DENY;
        $roles = Auth::getUserRoles();
        foreach ($roles as $role) {
            $this->acl->allow($role, 'index', 'index');
            $allowed = $this->acl->isAllowed($role, $controller, $action);
            if($allowed == Acl::ALLOW) {
                break;
            }
        }

        return $allowed;
    }

    protected function isAdmin()
    {
        $auth = $this->session->get('auth-identity');
        if($auth['id'] == 7) {
            return true;
        }
        return false;
    }
}
/**
 * 注意,getAcl中修改角色大小写后
 * 需要在beforeExecuteRoute中同步修改以免抛出异常
 */
