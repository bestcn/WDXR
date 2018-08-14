<?php

namespace Wdxr\Models\Entities;

class LoansInfo extends \Phalcon\Mvc\Model
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
     * @var integer
     * @Column(type="integer", length=3, nullable=true)
     */
    protected $step;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $u_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    protected $state;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=true)
     */
    protected $sex;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    protected $identity;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $license;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    protected $household;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $province;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $city;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $area;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $business;

    /**
     *
     * @var integer
     * @Column(type="integer", length=5, nullable=true)
     */
    protected $money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=true)
     */
    protected $term;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $purpose;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $device_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $partner_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $user_id;

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
     * Method to set the value of field step
     *
     * @param integer $step
     * @return $this
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Method to set the value of field u_id
     *
     * @param integer $u_id
     * @return $this
     */
    public function setUId($u_id)
    {
        $this->u_id = $u_id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field state
     *
     * @param integer $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Method to set the value of field time
     *
     * @param integer $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Method to set the value of field sex
     *
     * @param integer $sex
     * @return $this
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Method to set the value of field identity
     *
     * @param string $identity
     * @return $this
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * Method to set the value of field license
     *
     * @param string $license
     * @return $this
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Method to set the value of field household
     *
     * @param string $household
     * @return $this
     */
    public function setHousehold($household)
    {
        $this->household = $household;

        return $this;
    }

    /**
     * Method to set the value of field province
     *
     * @param integer $province
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
     * @param integer $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Method to set the value of field area
     *
     * @param integer $area
     * @return $this
     */
    public function setArea($area)
    {
        $this->area = $area;

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
     * Method to set the value of field business
     *
     * @param string $business
     * @return $this
     */
    public function setBusiness($business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Method to set the value of field money
     *
     * @param integer $money
     * @return $this
     */
    public function setMoney($money)
    {
        $this->money = $money;

        return $this;
    }

    /**
     * Method to set the value of field term
     *
     * @param integer $term
     * @return $this
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Method to set the value of field purpose
     *
     * @param string $purpose
     * @return $this
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;

        return $this;
    }

    /**
     * Method to set the value of field device_id
     *
     * @param integer $device_id
     * @return $this
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;

        return $this;
    }

    /**
     * Method to set the value of field partner_id
     *
     * @param integer $partner_id
     * @return $this
     */
    public function setPartnerId($partner_id)
    {
        $this->partner_id = $partner_id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

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
     * Returns the value of field step
     *
     * @return integer
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Returns the value of field u_id
     *
     * @return integer
     */
    public function getUId()
    {
        return $this->u_id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Returns the value of field time
     *
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Returns the value of field sex
     *
     * @return integer
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Returns the value of field identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Returns the value of field license
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Returns the value of field household
     *
     * @return string
     */
    public function getHousehold()
    {
        return $this->household;
    }

    /**
     * Returns the value of field province
     *
     * @return integer
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Returns the value of field city
     *
     * @return integer
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Returns the value of field area
     *
     * @return integer
     */
    public function getArea()
    {
        return $this->area;
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
     * Returns the value of field business
     *
     * @return string
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Returns the value of field money
     *
     * @return integer
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Returns the value of field term
     *
     * @return integer
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Returns the value of field purpose
     *
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * Returns the value of field device_id
     *
     * @return integer
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * Returns the value of field partner_id
     *
     * @return integer
     */
    public function getPartnerId()
    {
        return $this->partner_id;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
        $this->hasOne('province', __NAMESPACE__.'\Regions', 'id', [
            'alias' => 'province_regions',
        ]);
        $this->hasOne('city', __NAMESPACE__.'\Regions', 'id', [
            'alias' => 'city_regions',
        ]);
        $this->hasOne('area', __NAMESPACE__.'\Regions', 'id', [
            'alias' => 'area_regions',
        ]);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LoansInfo[]|LoansInfo
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LoansInfo
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
        return 'loans_info';
    }

}
