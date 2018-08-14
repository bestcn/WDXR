<?php
namespace Wdxr\Modules\Admin\Controllers;


use Phalcon\Mvc\View;

class ErrorController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        $this->view->disableLevel([
            View::LEVEL_MAIN_LAYOUT => true,
            View::LEVEL_LAYOUT      => true,
            View::LEVEL_AFTER_TEMPLATE => true,
            View::LEVEL_BEFORE_TEMPLATE => true,
            View::LEVEL_NO_RENDER => true,
        ]);
    }

    public function notFoundAction()
    {

    }

    public function fatalAction()
    {

    }



}