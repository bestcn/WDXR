<?php
namespace Wdxr\Modules\Admin\Controllers;

use Lcobucci\JWT\JWT;
use Phalcon\Mvc\Controller;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Wdxr\Models\Repositories\Repositories;

/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 */
class ControllerBase extends Controller
{



    static private $_logger;
    static private $_logger_operation;

    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @return boolean
     */
    public function initialize()
    {
        $this->tag->setTitle("冀企管家业务管理后台");
        $this->tag->setTitleSeparator(" - ");
        $this->view->setVar('admin_name', $this->session->get('auth-identity')['name']);
        $this->view->setVar('position', $this->session->get('auth-identity')['position']);
        $this->view->setVar('avatar', $this->session->get('auth-identity')['avatar']);
        $this->view->setTemplateBefore('common');
        $this->saveUrl();

        $this->view->setVar('phalcon_version', \Phalcon\Version::get());
    }

    /**
     * @return FileAdapter
     * 错误日志
     */
    public function logger()
    {
        if (is_null(self::$_logger)) {
            $name = BASE_PATH."/cache/logs/".date("Ymd").".log";
            self::$_logger = new FileAdapter($name);
        }
        return self::$_logger;
    }

    /**
     * @return FileAdapter
     * 操作日志
     */
    public function logger_operation()
    {
        if (is_null(self::$_logger_operation)) {
            $dir = BASE_PATH."/cache/logs/".$this->session->get('auth-identity')['id']."/";
            if (!is_dir(BASE_PATH."/cache/logs/".$this->session->get('auth-identity')['id']."/")) {
                mkdir($dir, 0777, true);
            }
            $name = $dir.date("Ymd").".log";
            self::$_logger_operation = new FileAdapter($name);
        }
        return self::$_logger_operation;
    }

    /**
     * 记录操作日志
     * @param $name
     * @param $class
     * @param $action
     * @param int $parameter
     * @param null $description
     * @return bool
     */
    public function logger_operation_set($name, $class, $action, $parameter = 0, $description = null)
    {
        /**
         * @var $log \Wdxr\Models\Repositories\AdminLog
         */
        $log = Repositories::getRepository('AdminLog');
        $log->newAdminLog($name, $class, $action, $parameter, $description);

        return true;
    }

    /**
     * URL队列
     * @return bool
     */
    protected function saveUrl()
    {
        $current_url_list = $this->redis->get('_current_url');
        $url = $this->request->getURI();
        if (is_array($current_url_list) === false) {
            $current_url_list = [$url];
        }
        array_unshift($current_url_list, $url);

        if (count($current_url_list) > 10) {
            $current_url_list = array_slice($current_url_list, 0, 10);
        }
        $this->redis->save('_current_url', $current_url_list);

        return true;
    }

    /**
     * 上一页的URL
     * @return null|string
     */
    protected function getLastUrl()
    {
        $url = $this->redis->get('_current_url');
        $last_url = isset($url[1]) ? $url[1] : null;

        return $last_url;
    }

}
