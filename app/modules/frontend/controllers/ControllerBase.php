<?php
namespace Wdxr\Modules\Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->tag->setTitle("WDXR");
        $this->tag->setTitleSeparator(" - ");
    }

}
