<?php
/**
 * Created by PhpStorm.
 * User: DH
 * Date: 2017/4/20
 * Time: 10:31
 */
namespace Wdxr\Models\Services;
use Wdxr\Models\Repositories\VerifyMessages as RepVerifyMessages;
use Wdxr\Models\Repositories\CompanyVerify;
use Wdxr\Models\Repositories\Company;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class VerifyMessages extends Services
{


    static public function getMessageListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            $conditions="title LIKE :title: or content LIKE :content:";
            $bind=["title"=>"%".$parameters."%","content"=>"%".$parameters."%"];
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->from(['message' => 'Wdxr\Models\Entities\VerifyMessages'])
            ->leftJoin('Wdxr\Models\Entities\Admins', 'admin.id = message.select_id', 'admin')
            ->columns(['message.select_time', 'message.title', 'message.id',  'admin.name as admin_name', "ifnull(admin.name, '无') as admin_name",'create_time','message.content', 'message.status'])
            ->orderBy('message.id desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    static public function getUnMessageListPagintor($parameters, $numberPage)
    {
        $conditions = '';$bind = [];
        if(!empty($parameters)) {
            $conditions="title LIKE :title: or content LIKE :content:";
            $bind=["title"=>"%".$parameters."%","content"=>"%".$parameters."%"];
        }
        $builder = Services::getStaticModelsManager()->createBuilder()
            ->where($conditions, $bind)
            ->andWhere("message.status LIKE :status: ",["status"=>RepVerifyMessages::READ_NOT])
            ->from(['message' => 'Wdxr\Models\Entities\VerifyMessages'])
            ->columns(['id','title','create_time','status'])
            ->orderBy('id desc');

        return new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit'=> 10,
            'page' => $numberPage
        ]);
    }

    static public function getMessageInfo ($id){

        $info=RepVerifyMessages::getVerifyMessagesById($id);
        $data['id']=$id;
        $data['title']=$info->getTitle();
        $data['content']=$info->getContent();
        $data['create_time']=$info->getCreateTime();
        if(!empty($info->getSelectTime())){
            $data['select_time']=$info->getSelectTime();
        }else{
            $data['select_time']="";
        }
        if(!empty($info->getSelectId())){
            $data['name']= \Wdxr\Models\Repositories\Admin::getAdminById($info->getSelectId())->getName();
        }else{
            $data['name']="";
        }
        return $data;
    }

    //新建审核消息
    static public function newVerifyMessages($id,$verify_id ,$type)
    {
        $company=Company::getCompanyById($id);
        $title=$company->getName()."的".CompanyVerify::getTypeName($type)."审核";
        $url="<a href='#'>";
        if($type==CompanyVerify::TYPE_DOCUMENTS){
              $url="<a href='/admin/companys/edit_auditing/".$verify_id."'>";
          }elseif($type==CompanyVerify::TYPE_BILL){
              $url="<a href='/admin/companys/bill/".$verify_id."'>";
          }elseif ($type==CompanyVerify::TYPE_CREDIT){
              $url="<a href='/admin/companys/report/".$id."'>";
          }elseif($type==CompanyVerify::TYPE_PAYMENT){
              $url="<a href='/admin/finance/edit_payment/".$verify_id."'>";
          }elseif ($type==CompanyVerify::TYPE_LOAN){
              $url="<a href='/admin/loan/edit/".$verify_id."'>";
          }
        $content="当前企业：".$url.$company->getName().$id."的".CompanyVerify::getTypeName($type)."</a>正等待您的审核，请您尽快进行审核提交！";
                //添加到审核消息表
        RepVerifyMessages::newMessages($title,$content);
    }

}