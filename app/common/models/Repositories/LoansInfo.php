<?php
namespace Wdxr\Models\Repositories;

use Lcobucci\JWT\JWT;
use Phalcon\Exception;
use Wdxr\Models\Entities\Loan as EntityLoan;
use Wdxr\Models\Entities\LoansInfo as EntityLoansInfo;
use Wdxr\Models\Services\Loan as SerLoan;
use Wdxr\Models\Entities\CompanyVerify as EntitiesCompanyVerify;
use Wdxr\Models\Entities\Users as EntityUser;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\CompanyVerify as RepCompanyVerify;
use Wdxr\Models\Repositories\CompanyInfo as RepCompanyInfo;
use Wdxr\Models\Repositories\Company as RepCompany;

class LoansInfo
{
    static private $_instance = null;

    const PURPOSE = '支付河北华企管家信息科技有限公司年度服务费';

    public static function edit($id, $data)
    {
        $loan = EntityLoansInfo::findFirst(['conditions'=>'id = :id:',
            'bind' => ['id' => $id]]);
        if (!$loan) {
            throw new InvalidRepositoryException("当前信息不存在！");
        }
        if (isset($data['identity']) && !empty($data['identity'])) {
            $loan->setIdentity($data['identity']);
        }
        if (isset($data['step']) && !empty($data['step'])) {
            $loan->setStep($data['step']);
        }
        if (isset($data['name']) && !empty($data['name'])) {
            $loan->setName($data['name']);
        }
        if (isset($data['sex']) && !empty($data['sex'])) {
            $loan->setSex($data['sex']);
        }
        if (isset($data['province']) && !empty($data['province'])) {
            $loan->setProvince($data['province']);
        }
        if (isset($data['city']) && !empty($data['city'])) {
            $loan->setCity($data['city']);
        }
        if (isset($data['area']) && !empty($data['area'])) {
            $loan->setArea($data['area']);
        }
        if (isset($data['address']) && !empty($data['address'])) {
            $loan->setAddress($data['address']);
        }
        if (isset($data['business']) && !empty($data['business'])) {
            $loan->setBusiness($data['business']);
        }
        if (isset($data['money']) && !empty($data['money'])) {
            $loan->setMoney($data['money']);
        }
        if (isset($data['term']) && !empty($data['term'])) {
            $loan->setTerm($data['term']);
        }
        if (isset($data['state']) && !empty($data['state'])) {
            $loan->setState($data['state']);
        }
        $loan->setPurpose(self::PURPOSE);
        $loan->setTime(time());
        if ($loan->save()) {
            return $loan->getId();
        } else {
            throw new InvalidRepositoryException($loan->getMessages()[0]);
        }
    }

    static public function addNew($data)
    {
        $data['purpose']=self::PURPOSE;
        $loan = new EntityLoansInfo();
        if (isset($data['identity']) && !empty($data['identity'])){
            $loan->setIdentity($data['identity']);
        }
        if (isset($data['step']) && !empty($data['step'])){
            $loan->setStep($data['step']);
        }
        if (isset($data['name']) && !empty($data['name'])){
            $loan->setName($data['name']);
        }
        if (isset($data['sex']) && !empty($data['sex'])){
            $loan->setSex($data['sex']);
        }
        if (isset($data['province_id']) && !empty($data['province_id'])){
            $loan->setProvince($data['province_id']);
        }
        if (isset($data['city_id']) && !empty($data['city_id'])){
            $loan->setCity($data['city_id']);
        }
        if (isset($data['area_id']) && !empty($data['area_id'])){
            $loan->setArea($data['area_id']);
        }
        if (isset($data['address']) && !empty($data['address'])){
            $loan->setAddress($data['address']);
        }
        if (isset($data['business']) && !empty($data['business'])){
            $loan->setBusiness($data['business']);
        }
        if (isset($data['money']) && !empty($data['money'])){
            $loan->setMoney($data['money']);
        }
        if (isset($data['term_id']) && !empty($data['term_id'])){
            $loan->setTerm($data['term_id']);
        }
        if (isset($data['purpose']) && !empty($data['purpose'])){
            $loan->setPurpose($data['purpose']);
        }
        if (isset($data['state']) && !empty($data['state'])){
            $loan->setState($data['state']);
        }
        if (isset($data['partner_id']) && !empty($data['partner_id'])){
            $loan->setPartnerId($data['partner_id']);
        }
        $loan->setTime(time());
        $loan->setDeviceId($data['device_id']);
        $loan->setUId($data['u_id']);
        $loan->setUserId($data['user_id']);
        $flag=$loan->save();
        if ($flag){
            return $loan->getId();
        } else {
            throw new InvalidRepositoryException("新建普惠信息失败！");
        }
    }

    static public function addInfo($data)
    {
        $data['purpose']=self::PURPOSE;
        $loan = new EntityLoansInfo();
        if (isset($data['identity']) && !empty($data['identity'])){
            $loan->setIdentity($data['identity']);
        }
        if (isset($data['step']) && !empty($data['step'])){
            $loan->setStep($data['step']);
        }
        if (isset($data['name']) && !empty($data['name'])){
            $loan->setName($data['name']);
        }
        if (isset($data['sex']) && !empty($data['sex'])){
            $loan->setSex($data['sex']);
        }
        if (isset($data['province']) && !empty($data['province'])){
            $loan->setProvince($data['province']);
        }
        if (isset($data['city']) && !empty($data['city'])){
            $loan->setCity($data['city']);
        }
        if (isset($data['area']) && !empty($data['area'])){
            $loan->setArea($data['area']);
        }
        if (isset($data['address']) && !empty($data['address'])){
            $loan->setAddress($data['address']);
        }
        if (isset($data['business']) && !empty($data['business'])){
            $loan->setBusiness($data['business']);
        }
        if (isset($data['money']) && !empty($data['money'])){
            $loan->setMoney($data['money']);
        }
        if (isset($data['term']) && !empty($data['term'])){
            $loan->setTerm($data['term']);
        }
        if (isset($data['purpose']) && !empty($data['purpose'])){
            $loan->setPurpose($data['purpose']);
        }
        if (isset($data['state']) && !empty($data['state'])){
            $loan->setState($data['state']);
        }
        if (isset($data['partner_id']) && !empty($data['partner_id'])){
            $loan->setPartnerId($data['partner_id']);
        }
        $loan->setTime(time());
        $loan->setDeviceId($data['device_id']);
        $loan->setUId($data['u_id']);
        $loan->setUserId($data['user_id']);
        $flag=$loan->save();
        if ($flag){
            return $loan->getId();
        }else{
            throw new InvalidRepositoryException("新建普惠信息失败！");
        }
    }

    static public function getLoansInfoById($data_id)
    {
        if(is_null(self::$_instance)) {
            self::$_instance = EntityLoansInfo::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $data_id]]);
        }

        return self::$_instance;
    }



    static public function setStepOne($res)
    {
        $data["company_id"]=$res["company_id"];
        $data["company_name"]=$res["company_name"];
        $data["licence_num"]=$res["licence_num"];
        $data["tel"]=$res["tel"];
        $data["name"]=$res["name"];
        $data["sex"]=$res["sex"];
        $data["identity"]=$res["identity"];
        $data["province"]=$res["province"];
        $data["province_id"]=$res["province_id"];
        $data["city"]=$res["city"];
        $data["city_id"]=$res["city_id"];
        $data["area"]=$res["area"];
        $data["area_id"]=$res["area_id"];
        $data["address"]=$res["address"];
        $data["business"]=$res["business"];
        return $data;
    }

    static public function setStepTwo($res)
    {
        $data["money"]=$res["money"];
        $data["term"]=$res["term"];
        $data["term_id"]=$res["term_id"];
        $data["purpose"]=$res["purpose"];
        return $data;
    }



    static public function setStepThree($res)
    {
        if($res["state"]==SerLoan::STATUS_REJECT || $res["state"]==SerLoan::STATUS_FAIL){
            $verify=RepCompanyVerify::getVerifyInfoByDataId($res["id"],RepCompanyVerify::TYPE_LOAN);
            $data["remark"]=$verify->getRemark();
        }
        $data["tel"]=$res["tel"];
        $data["name"]=$res["name"];
        $data["sex"]=$res["sex"];
        $data["identity"]=$res["identity"];
        $data["licence_num"]=$res["licence_num"];
        $data["company_id"]=$res["company_id"];
        $data["company_name"]=$res["company_name"];
        $data["province"]=$res["province"];
        $data["province_id"]=$res["province_id"];
        $data["city"]=$res["city"];
        $data["city_id"]=$res["city_id"];
        $data["area"]=$res["area"];
        $data["area_id"]=$res["area_id"];
        $data["address"]=$res["address"];
        $data["business"]=$res["business"];
        $data["money"]=$res["money"];
        $data["term"]=$res["term"];
        $data["term_id"]=$res["term_id"];
        $data["purpose"]=$res["purpose"];
        $data["time"]=date("Y-m-d H:i",$res["time"]);
        return $data;
    }

//普惠详细信息查询
    static public function select($id)
    {
        if(!preg_match("/^\+?[1-9][0-9]*$/",$id)){
            throw new InvalidRepositoryException("当前信息查询失败！");
        }

        if(($res = EntityLoansInfo::findFirst(['conditions' => 'id = :id:',
                'bind' => ['id' => $id]
            ])) === false) {
            throw new InvalidRepositoryException("未获得申请信息，请重新尝试！");
        }
        $res = $res->toArray();
        $re=EntityLoan::findFirst(['conditions' => 'id = :id:',
            'bind' => ['id' =>$res["u_id"]]
        ]);

        if(!$re){
            throw new InvalidRepositoryException("未获得申请信息，请重新尝试！");
        }
        is_null($re->getTel())?$res["tel"]="":$res["tel"]=$re->getTel();
        $res['company_id']=$re->getCompanyId();
        if($res["term"]=="1"){
            $res["term"]="三个月";
            $res["term_id"]="1";
        }elseif($res["term"]=="2"){
            $res["term"]="六个月";
            $res["term_id"]="2";
        }elseif($res["term"]=="3"){
            $res["term"]="九个月";
            $res["term_id"]="3";
        }elseif($res["term"]=="4"){
            $res["term"]="十二个月";
            $res["term_id"]="4";
        }else{
            $res["term"]="";
            $res["term_id"]="";
        }
        if(!empty($res["province"])){
            $res["province_id"]=$res["province"];
//            $res["province"]=Province::getProvinceByid($res["province"]);
            $res["province"]=Regions::getRegionName($res["province"])?Regions::getRegionName($res["province"])->name:'';
        }else{
            $res["province_id"]="";
            $res["province"]="";
        }
        if($res["city"]){
            $res["city_id"]=$res["city"];
//            $res["city"]=Citie::getCitiesByid($res["city"]);
            $res["city"]=Regions::getRegionName($res["city"])?Regions::getRegionName($res["city"])->name:'';
        }else{
            $res["city_id"]='';
            $res["city"]='';
        }
        if($res["area"]){
            $res["area_id"]=$res["area"];
//            $res["area"]=Area::getAreaByid($res["area"]);
            $res["area"]=Regions::getRegionName($res["area"])?Regions::getRegionName($res["area"])->name:'';
        }else{
            $res["area_id"]='';
            $res["area"]='';
        }
        if(is_null($res["money"]) || empty($res["money"])){
            $res["money"]="";
        }
        is_null($res["purpose"])?$res["purpose"]="":$res["purpose"];
        is_null($res["identity"])?$res["identity"]="":$res["identity"];
        is_null($res["business"])?$res["business"]="":$res["business"];
        is_null($res["address"])?$res["address"]="":$res["address"];
        is_null($res["sex"])?$res["sex"]="":$res["sex"];
        $company=RepCompany::getCompanyById($res['company_id']);
        $res["licence_num"]=RepCompanyInfo::getCompanyInfoById($company->getInfoId())->getLicenceNum();
        $res["company_name"]=$company->getName();
        return $res;
    }

    static public function getLoanByData($u_id)
    {
        $re=EntityLoansInfo::findFirst(['conditions'=>'u_id = :u_id:',
            'bind' => ['u_id' => $u_id],
            "order"=>"time desc"
        ]);
        if(!$re){
            throw new InvalidRepositoryException("找不到您要查询的普惠申请信息！");
        }
        return $re;
    }


    static public function getSex($res)
    {
        if($res=="1"){
            $data="男";
        }elseif($res=="2"){
            $data="女";
        }else{
            $data="错误";
        }
        return $data;
    }

    static public function getTerm($res)
    {
        if($res=="1"){
            $data="三个月";
        }elseif($res=="2"){
            $data="六个月";
        }elseif($res=="3"){
            $data="九个月";
        }elseif($res=="4"){
            $data="十二个月";
        }else{
            $data="错误";
        }
        return $data;
    }



    static public function getById($id)
    {
            $data = EntityLoansInfo::findFirst(['conditions' => 'id = :id:',
                'bind' => ['id' => $id ]]);
            if($data === false) {
                throw new InvalidRepositoryException("企业基本信息获取失败");
            }else{
                return $data;
            }
    }

    static public function getLoanInfoById($id)
    {
        return EntityLoansInfo::findFirst(['conditions' => 'id = :id:','bind' => ['id' => $id ]]);
    }


    static public function deleteLoan($id)
    {
        $loan = EntityLoansInfo::findFirst(['conditions' => 'id = :id: ',
            'bind' => ['id' => $id ]]);
        if (!$loan) {
            throw new InvalidRepositoryException("申请没有找到");
        }
        if (!$loan->delete()) {
            throw new InvalidRepositoryException("申请删除失败");
        }

        return true;
    }

}