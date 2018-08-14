<?php

namespace Wdxr\Models\Entities;

/**
 * CompanyInfo
 * 
 * @package Wdxr\Models\Entities
 * @autogenerated by Phalcon Developer Tools
 * @date 2018-06-04, 02:31:11
 */
class CompanyInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $licence;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $licence_num;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $account_permit;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $credit_code;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $legal_name;

    /**
     *
     * @var string
     * @Column(type="string", length=1024, nullable=true)
     */
    protected $scope;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $period;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $idcard_up;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $idcard_down;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $photo;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $contacts;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $contact_title;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $contact_phone;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $intro;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $province;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $city;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $district;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $zipcode;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $createAt;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $idcard;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $shop_img;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field licence
     *
     * @param string $licence
     * @return $this
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * Method to set the value of field licence_num
     *
     * @param string $licence_num
     * @return $this
     */
    public function setLicenceNum($licence_num)
    {
        $this->licence_num = $licence_num;

        return $this;
    }

    /**
     * Method to set the value of field account_permit
     *
     * @param string $account_permit
     * @return $this
     */
    public function setAccountPermit($account_permit)
    {
        $this->account_permit = $account_permit;

        return $this;
    }

    /**
     * Method to set the value of field credit_code
     *
     * @param string $credit_code
     * @return $this
     */
    public function setCreditCode($credit_code)
    {
        $this->credit_code = $credit_code;

        return $this;
    }

    /**
     * Method to set the value of field legal_name
     *
     * @param string $legal_name
     * @return $this
     */
    public function setLegalName($legal_name)
    {
        $this->legal_name = $legal_name;

        return $this;
    }

    /**
     * Method to set the value of field scope
     *
     * @param string $scope
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Method to set the value of field period
     *
     * @param string $period
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Method to set the value of field idcard_up
     *
     * @param string $idcard_up
     * @return $this
     */
    public function setIdcardUp($idcard_up)
    {
        $this->idcard_up = $idcard_up;

        return $this;
    }

    /**
     * Method to set the value of field idcard_down
     *
     * @param string $idcard_down
     * @return $this
     */
    public function setIdcardDown($idcard_down)
    {
        $this->idcard_down = $idcard_down;

        return $this;
    }

    /**
     * Method to set the value of field photo
     *
     * @param string $photo
     * @return $this
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Method to set the value of field contacts
     *
     * @param string $contacts
     * @return $this
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Method to set the value of field contact_title
     *
     * @param string $contact_title
     * @return $this
     */
    public function setContactTitle($contact_title)
    {
        $this->contact_title = $contact_title;

        return $this;
    }

    /**
     * Method to set the value of field contact_phone
     *
     * @param string $contact_phone
     * @return $this
     */
    public function setContactPhone($contact_phone)
    {
        $this->contact_phone = $contact_phone;

        return $this;
    }

    /**
     * Method to set the value of field type
     *
     * @param integer $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Method to set the value of field intro
     *
     * @param string $intro
     * @return $this
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Method to set the value of field province
     *
     * @param string $province
     * @return $this
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Method to set the value of field city
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Method to set the value of field district
     *
     * @param string $district
     * @return $this
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Method to set the value of field zipcode
     *
     * @param string $zipcode
     * @return $this
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Method to set the value of field createAt
     *
     * @param string $createAt
     * @return $this
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Method to set the value of field idcard
     *
     * @param string $idcard
     * @return $this
     */
    public function setIdcard($idcard)
    {
        $this->idcard = $idcard;

        return $this;
    }

    /**
     * Method to set the value of field shop_img
     *
     * @param string $shop_img
     * @return $this
     */
    public function setShopImg($shop_img)
    {
        $this->shop_img = $shop_img;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field licence
     *
     * @return string
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * Returns the value of field licence_num
     *
     * @return string
     */
    public function getLicenceNum()
    {
        return $this->licence_num;
    }

    /**
     * Returns the value of field account_permit
     *
     * @return string
     */
    public function getAccountPermit()
    {
        return $this->account_permit;
    }

    /**
     * Returns the value of field credit_code
     *
     * @return string
     */
    public function getCreditCode()
    {
        return $this->credit_code;
    }

    /**
     * Returns the value of field legal_name
     *
     * @return string
     */
    public function getLegalName()
    {
        return $this->legal_name;
    }

    /**
     * Returns the value of field scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Returns the value of field period
     *
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Returns the value of field idcard_up
     *
     * @return string
     */
    public function getIdcardUp()
    {
        return $this->idcard_up;
    }

    /**
     * Returns the value of field idcard_down
     *
     * @return string
     */
    public function getIdcardDown()
    {
        return $this->idcard_down;
    }

    /**
     * Returns the value of field photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Returns the value of field contacts
     *
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Returns the value of field contact_title
     *
     * @return string
     */
    public function getContactTitle()
    {
        return $this->contact_title;
    }

    /**
     * Returns the value of field contact_phone
     *
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contact_phone;
    }

    /**
     * Returns the value of field type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the value of field intro
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the value of field province
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Returns the value of field city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Returns the value of field district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Returns the value of field zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Returns the value of field createAt
     *
     * @return string
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the value of field idcard
     *
     * @return string
     */
    public function getIdcard()
    {
        return $this->idcard;
    }

    /**
     * Returns the value of field shop_img
     *
     * @return string
     */
    public function getShopImg()
    {
        return $this->shop_img;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyInfo[]|CompanyInfo
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyInfo
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'company_info';
    }

}