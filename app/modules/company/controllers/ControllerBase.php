<?php
namespace Wdxr\Modules\Company\Controllers;

use Phalcon\Mvc\Controller;
use Wdxr\Auth;
/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 */
class ControllerBase extends Controller
{
    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @return boolean
     */
    public function initialize()
    {
        $auth = new Auth\Company_Auth();
        $auth->notLoginAndRedirect();
        $this->tag->setTitle("WDXR");
        $this->tag->setTitleSeparator(" - ");
        $this->view->setTemplateBefore('common');
    }
}
