<?php
namespace Wdxr\Models\Repositories;

use Lcobucci\JWT\JWT;
use Wdxr\Models\Entities\Companys as EntityCompany;
use Wdxr\Models\Entities\CompanyInfo as EntityCompanyInfo;
use Wdxr\Models\Repositories\Company as RepoCompany;
use Wdxr\Models\Repositories\CompanyVerify as RepoCompanyVerify;
use Wdxr\Models\Repositories\Exceptions\InvalidRepositoryException;
use Wdxr\Models\Repositories\Regions as RepoRegions;

class CompanyInfo extends Repositories
{

    const BANK_TYPE_PUBLIC = 1;
    const BANK_TYPE_SELF = 2;

    static private $_instance = [];
    static private $_info = [];

    static public function getLastCompanyInfo($company_id)
    {
        if(isset(self::$_instance[$company_id]) === false) {
            self::$_instance[$company_id] = EntityCompanyInfo::findFirst(['conditions' => 'company_id = :id:', 'bind' => ['id' => $company_id], 'order' => 'createAt desc, id desc']);
            if(self::$_instance[$company_id] === false) {
                throw new InvalidRepositoryException("获取公司信息失败");
            }
        }
        return self::$_instance[$company_id];
    }

    /**
     * @param $id
     * @return EntityCompanyInfo
     * @throws InvalidRepositoryException
     */
    public static function getCompanyInfoById($id)
    {
        /**
         * @var $admin EntityCompanyInfo[]
         */
        if (isset(self::$_info[$id]) === false) {
            self::$_info[$id] = EntityCompanyInfo::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
            if (self::$_info[$id] === false) {
                throw new InvalidRepositoryException("获取企业详细信息失败");
            }
        }
        return self::$_info[$id];
    }

    public function getCompanyInfo($id)
    {
        $info = EntityCompanyInfo::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if ($info == false) {
            throw new InvalidRepositoryException("获取企业详细信息失败");
        }
        return $info;
    }

    public function getCompanyInfo2($id)
    {
        $info = EntityCompanyInfo::findFirst(['conditions' => 'id = :id:', 'bind' => ['id' => $id]]);
        if ($info === false) {
            return false;
        }
        return $info;
    }

    public function getCompanyInfoByUrl($url)
    {
        $info = EntityCompanyInfo::findFirst(['conditions' => 'url = :url:',
            'bind' => ['url' => $url],
            'order'=> 'id desc']);
        if ($info == false) {
            return false;
        }
        return $info;
    }

    public function getCompanyInfoByLicenceNum($licence_num)
    {
        $info = EntityCompanyInfo::findFirst(['conditions' => 'licence_num = :licence_num:',
            'bind' => ['licence_num' => $licence_num],
            'order'=> 'id desc']);
        if($info == false){
            return false;
        }
        return $info;
    }

    public function getLast()
    {
        return EntityCompanyInfo::query()
            ->orderBy('id DESC')
            ->execute();
    }

    public function addNew($data)
    {
        $company = new EntityCompanyInfo();
        $company->setType($data['type']);
        $company->setLicence($data["licence"]);//营业执照
        $company->setAccountPermit($data["account_permit"]);//开户许可证
        $company->setCreditCode($data["credit_code"]);//机构信用代码证
        $company->setIdcardUp($data["idcard_up"]);//法人身份证正面
        $company->setIdcardDown($data["idcard_down"]);//法人身份证反面
        $company->setPhoto($data["photo"]);//法人手持身份证照片
        $company->setContacts($data["contacts"]);//联系人
        $company->setContactTitle($data["contact_title"]);//联系人职位
        $company->setContactPhone($data["contact_phone"]);//联系人电话
        $company->setIntro($data["intro"]);//企业简介
        $company->setAddress($data["address"]);//企业详细地址
        $company->setProvince($data["province"]);//企业地址省
        $company->setCity($data["city"]);//企业地址市
        $company->setDistrict($data["district"]);//企业地址县区
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return $company->getWriteConnection()->lastInsertId($company->getSource());
    }

    public function edit($id, $data)
    {
        $company = CompanyInfo::getCompanyInfoById($id);
        if($data['type'] == RepoCompany::TYPE_COMPANY) {
            $company->setAccountPermit($data["account_permit"]);//开户许可证
            $company->setCreditCode($data["credit_code"]);//机构信用代码证
        }
        $company->setLicenceNum($data['licence_num']);
        $company->setLicence($data['licence']);
        $company->setScope($data['scope']);
        $company->setPeriod($data['period']);
        $company->setLegalName($data['legal_name']);
        $company->setZipcode($data["zipcode"]);

        $company->setIdcard($data['idcard']);//法人身份证号码
        $company->setIdcardUp($data["idcard_up"]);//法人身份证正面
        $company->setIdcardDown($data["idcard_down"]);//法人身份证反面
        $company->setPhoto($data["photo"]);//法人手持身份证照片

        $company->setContacts($data["contacts"]);//联系人
        $company->setContactTitle($data["contact_title"]);//联系人职位
        $company->setContactPhone($data["contact_phone"]);//联系人电话

        $company->setIntro($data["intro"]);//企业简介

        $company->setProvince($data["province"]);//企业地址省
        $company->setCity($data["city"]);//企业地址市
        $company->setDistrict($data["district"]);//企业地址县区
        $company->setAddress($data["address"]);//企业详细地址
        $company->setShopImg($data['shop_img']);

        if ($company->save() === false) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }

        return true;
    }

    public static function getByCompanyId($id)
    {
        $company = RepoCompany::getCompanyById($id);
        $company_info = EntityCompanyInfo::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $company->getInfoId()]
        ]);
        if ($company_info === false) {
            throw new InvalidRepositoryException("获取企业详细信息失败");
        }
        return $company_info;
    }

    static public function getDetailAddress($province, $city, $district, $address = null)
    {
        $province = RepoRegions::getRegionName($province) ? RepoRegions::getRegionName($province)->name : '';
        $city = RepoRegions::getRegionName($city) ? RepoRegions::getRegionName($city)->name : '';
        $district = RepoRegions::getRegionName($district) ? RepoRegions::getRegionName($district)->name : '';
        if(strpos($address, $province) === false) {
            return $province.$city.$district;
        }
        return '';
    }

    /**
     * 接口提交补录信息
     * @param EntityCompany $company
     * @param $data
     * @return bool
     * @throws InvalidRepositoryException
     */
    public function reApplyCompanyInfo(EntityCompany $company, $data)
    {
        //获取业务员ID
        $device_id = JWT::getUid();
        $admin_id = UserAdmin::getAdminId($device_id);

        //补录设置推荐人
        $old_recommender = $company->getRecommendId();
        $new_recommender = $data['recommend'];
        if($new_recommender) {
            $new_recommender_company = RepoCompany::getCompanyById($new_recommender);
            if($new_recommender_company->getAdminId() != $admin_id) {
                throw new InvalidRepositoryException('新的推荐企业不是当前业务员的客户');
            }
        }
        //如果存在旧的推荐关系，并且和新的新的推荐关系不一样，则删除旧的推荐关系，添加新的
        if($old_recommender && $old_recommender != $new_recommender) {
            /**
             * @var $company_recommends CompanyRecommends
             */
            $company_recommends = Repositories::getRepository('CompanyRecommends');
            $company_recommends->deleteCompanyRecommend($old_recommender, $company->getId());
            $company_recommends->addNew($new_recommender, $company->getId(), $device_id);
            $company->setRecommendId($new_recommender);
        }
        //添加企业详细信息
        $company->setAuditing(RepoCompany::AUDIT_APPLY);
        //修改行业分类
        $company->setCategory($data['sub_category']);
        if(!$company->save()) {
            throw new InvalidRepositoryException("补录信息保存失败");
        }
        $this->edit($company->getInfoId(), $data);
        return RepoCompanyVerify::newVerify($company->getId(), JWT::getUid(), RepoCompanyVerify::TYPE_DOCUMENTS, $company->getInfoId());
    }

    static public function reApplyInfo(EntityCompany $company, $data)
    {
        //判断不同身份的业务员ID
        $user_id = $company->getDeviceId();
        //添加企业详细信息
        $data['company_id'] = $company->getId();
        $info_id = CompanyInfo::add($data);
        $company->setInfoId($info_id);
        $company->setAuditing(RepoCompany::AUDIT_APPLY);
        //修改行业分类
        $company->setCategory($data['sub_category']);
        if(!$company->save()) {
            throw new InvalidRepositoryException("补录信息保存失败");
        }
        RepoCompanyVerify::newVerify($company->getId(), $user_id, RepoCompanyVerify::TYPE_DOCUMENTS, $company->getInfoId());
        return true;
    }


    static public function add($data)
    {
        $company = new EntityCompanyInfo();
        $company->setType($data['type']);
        $company->setLicenceNum($data['licence_num']);
        $company->setScope($data['scope']);
        $company->setPeriod($data['period']);
        $company->setLegalName($data['legal_name']);
//        $company->setCompanyId($data["company_id"]);//企业ID
        $company->setLicence($data["licence"]);//营业执照
        if($data['type'] == RepoCompany::TYPE_COMPANY) {
            $company->setAccountPermit($data["account_permit"]);//开户许可证
            $company->setCreditCode($data["credit_code"]);//机构信用代码证
        }
        $company->setIdcardUp($data["idcard_up"]);//法人身份证正面
        $company->setIdcardDown($data["idcard_down"]);//法人身份证反面
        $company->setPhoto($data["photo"]);//法人手持身份证照片
        $company->setContacts($data["contacts"]);//联系人
        $company->setContactTitle($data["contact_title"]);//联系人职位
        $company->setContactPhone($data["contact_phone"]);//联系人电话
        $company->setIntro($data["intro"]);//企业简介
        $company->setAddress($data["address"]);//企业详细地址
        $company->setProvince($data["province"]);//企业地址省
        $company->setCity($data["city"]);//企业地址市
        $company->setDistrict($data["district"]);//企业地址县区
        $company->setZipcode($data['zipcode']);
        $company->setIdcard($data['idcard']);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return $company->getWriteConnection()->lastInsertId($company->getSource());
    }

    /**
     * 第三步提交修改完善企业证件信息
     * @param $info_id
     * @param $data
     * @return mixed
     * @throws InvalidRepositoryException
     */
    static public function addCompanyInfoData($info_id,$data)
    {
        $company = (new CompanyInfo())->getCompanyInfo($info_id);
        $company->setType($data['type']);
        $company->setLicenceNum($data['licence_num']);
        $company->setScope($data['scope']);
        $company->setPeriod($data['period']);
        $company->setLegalName($data['legal_name']);
//        $company->setCompanyId($data["company_id"]);//企业ＩＤ
        $company->setLicence($data["licence"]);//营业执照
        if($data['type'] == RepoCompany::TYPE_COMPANY) {
            $company->setAccountPermit($data["account_permit"]);//开户许可证
            $company->setCreditCode($data["credit_code"]);//机构信用代码证
        }
        $company->setIdcardUp($data["idcard_up"]);//法人身份证正面
        $company->setIdcardDown($data["idcard_down"]);//法人身份证反面
        $company->setPhoto($data["photo"]);//法人手持身份证照片
//        $company->setBankcardPhoto($data["bankcard_photo"]);//银行卡照片
//        $company->setBankcard($data["bankcard"]);//银行卡号
//        $company->setBankProvince($data["bank_province"]);//开户地址省
//        $company->setBankCity($data["bank_city"]);//开户地址市
//        $company->setBankName($data["bank_name"]);//开户行名称
//        $data['contract'] = is_array($data['contract']) ? implode(',', $data['contract']) : $data['contract'];
//        $company->setContract($data["contract"]);//纸质合同照片
//        $company->setSignPhoto($data["sign_photo"]);//签订人照片
        $company->setContacts($data["contacts"]);//联系人
        $company->setContactTitle($data["contact_title"]);//联系人职位
        $company->setContactPhone($data["contact_phone"]);//联系人电话
        $company->setIntro($data["intro"]);//企业简介
        $company->setAddress($data["address"]);//企业详细地址
        $company->setProvince($data["province"]);//企业地址省
        $company->setCity($data["city"]);//企业地址市
        $company->setDistrict($data["district"]);//企业地址县区
//        $company->setVerifyId($data["verify_id"]);//审核ID
//        $company->setBank($data['bank']);//银行列表名称
//        $company->setBankType($data['bank_type']);//账户类型
        $company->setZipcode($data['zipcode']);
        //绩效银行卡
//        $company->setWorkBank($data['work_bank']);
//        $company->setWorkBankcard($data['work_bankcard']);
//        $company->setWorkBankProvince($data['work_bank_province']);
//        $company->setWorkBankCity($data['work_bank_city']);
//        $company->setWorkBankName($data['work_bank_name']);
//        $company->setWorkBankcardPhoto($data['work_bankcard_photo']);
        $company->setIdcard($data['idcard']);
        $company->setShopImg($data['shop_img']);
//        $company->setAccountHolder($data['account_holder']);
//        $company->setWorkAccountHolder($data['work_account_holder']);
        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return $info_id;
    }

    /**
     * 完善企业证件信息
     * @param $info_id
     * @param $data
     * @return mixed
     * @throws InvalidRepositoryException
     */
    static public function addCompanyInfo($info_id, $data)
    {
        $company = (new CompanyInfo())->getCompanyInfo($info_id);
        $company->setLicence($data["licence"]);//营业执照
        if($data['type'] == RepoCompany::TYPE_COMPANY) {
            $company->setAccountPermit($data["account_permit"]);//开户许可证
            $company->setCreditCode($data["credit_code"]);//机构信用代码证
        }
        $company->setIdcard($data['idcard']);
        $company->setIdcardUp($data["idcard_up"]);//法人身份证正面
        $company->setIdcardDown($data["idcard_down"]);//法人身份证反面
        $company->setPhoto($data["photo"]);//法人手持身份证照片
        $company->setContacts($data["contacts"]);//联系人
        $company->setContactTitle($data["contact_title"]);//联系人职位
        $company->setContactPhone($data["contact_phone"]);//联系人电话
        $company->setIntro($data["intro"]);//企业简介
        $company->setZipcode($data['zipcode']);//邮编

        if (!$company->save()) {
            throw new InvalidRepositoryException($company->getMessages()[0]);
        }
        return $info_id;
    }

}