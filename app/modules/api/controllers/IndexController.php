<?php

namespace Wdxr\Modules\Api\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->response->setJsonContent(['status' => '1', 'data' => null, 'info' => 'ok']);
    }

}

