<?php
/**
 * Created by PhpStorm.
 * User: mayu
 * Date: 2018/4/16
 * Time: 9:43
 */
namespace Wdxr\Modules\Admin\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Wdxr\Models\Repositories\SmsLog;
use Wdxr\Models\Repositories\VerifyMessages;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Services\SMS;
use Wdxr\Models\Services\PushService;
use Wdxr\Models\Services\VerifyMessages as SerVerifyMessages;

class SmsController extends ControllerBase
{
    public function indexAction()
    {
        $numberPage = 1; $parameters = [];
        if (!$this->request->isPost()) {
            $numberPage = $this->request->getQuery("page", "int");
        }

        //传递搜索条件
        $this->view->setVar('phone', $this->request->get('phone'));
        if($data['phone'] = $this->request->get('phone')){
            $parameters= $data['phone'];
        }
        //获取所有消息分页信息
        $paginator = Sms::getSmsLogListPagintor($parameters, $numberPage);
        $this->view->setVar('page', $paginator->getPaginate());

    }


    public function send_listAction()
    {
    }

    public function selectAction($id)
    {
        $log = SmsLog::getSmsLogById($id);
        if($log === false){
            $this->flash->error("没有找到短信记录");
            $this->dispatcher->forward([
                'controller' => "sms",
                'action' => 'index'
            ]);
        }
        $this->view->setVar('log',$log);
    }


    public function codeAction()
    {
        if ($this->request->isPost()) {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $option = "";
            foreach ($phone as $key => $value)
            {
                $code = rand(1000, 9999);
                $option .= "<p>电话号码 : <code>".$value."</code>　　验证码 : <code>".$code."</code>　　发送状态 : <code>";
                if(SMS::verifyCodeSMS($value,$code))
                {
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }

    public function successAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　发送状态 : <code>";
                if(SMS::successSMS($value,$name[$key],SMS::TYPE_APPLY)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }

    public function failedAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　发送状态 : <code>";
                if(SMS::failedSMS($value,$name[$key],SMS::TYPE_APPLY)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }
    public function billAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $time = $this->request->getPost('time');
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　指定期限 : <code>".$time."</code>　　发送状态 : <code>";
                if(SMS::BillSMS($value,$name[$key],$time)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }

    public function reportAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $time = $this->request->getPost('time');
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　指定期限 : <code>".$time."</code>　　发送状态 : <code>";
                if(SMS::ReportSMS($value,$name[$key],$time)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }

    }

    public function periodAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $time = $this->request->getPost('time');
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　指定期限 : <code>".$time."</code>　　发送状态 : <code>";
                if(SMS::periodSMS($value,$name[$key],$time)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }

    }

    public function loanSuccessAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　发送状态 : <code>";
                if(SMS::loanSuccessSMS($value,$name[$key])){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }

    public function loanFailedAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　发送状态 : <code>";
                if(SMS::loanFailedSMS($value,$name[$key])){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }

    public function accountAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $name = $this->request->getPost('name');
            $name =  explode(',',$name);
            $user = $this->request->getPost('user');
            $user =  explode(',',$user);
            $password = $this->request->getPost('password');
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$name[$key]."</code>　　用户账号 : <code>".$user[$key]."</code>　　初始密码 : <code>".$password."</code>　　发送状态 : <code>";
                if(SMS::accountSMS($value,$name[$key],$user[$key],$password)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }

    }

    public function apply_successAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $type = $this->request->getPost('type');
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$type."</code>　　发送状态 : <code>";
                if(SMS::apply_success($value,$type)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }

    public function apply_failedAction()
    {
        if ($this->request->isPost())
        {
            $phone = $this->request->getPost('phone');
            $phone =  explode(',',$phone);
            $type = $this->request->getPost('type');
            $option = "";
            foreach ($phone as $key => $value)
            {
                $option .= "<p>电话号码 : <code>".$value."</code>　　用户名称 : <code>".$type."</code>　　发送状态 : <code>";
                if(SMS::apply_failed($value,$type)){
                    $option.= "发送成功";
                }else{
                    $option.= "发送失败";
                }
                $option.= "</code></p>";
            }
            return $option;
        }
    }






}