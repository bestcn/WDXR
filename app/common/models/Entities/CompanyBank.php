<?php

namespace Wdxr\Models\Entities;

class CompanyBank extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $company_id;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $bank;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $number;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $province;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $city;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $address;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $bank_type;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=false)
     */
    protected $account;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $category;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $bankcard_photo;

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
     * Method to set the value of field company_id
     *
     * @param integer $company_id
     * @return $this
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;

        return $this;
    }

    /**
     * Method to set the value of field bank
     *
     * @param string $bank
     * @return $this
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Method to set the value of field number
     *
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

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
     * Method to set the value of field bank_type
     *
     * @param integer $bank_type
     * @return $this
     */
    public function setBankType($bank_type)
    {
        $this->bank_type = $bank_type;

        return $this;
    }

    /**
     * Method to set the value of field account
     *
     * @param string $account
     * @return $this
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Method to set the value of field category
     *
     * @param integer $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Method to set the value of field bankcard_photo
     *
     * @param string $bankcard_photo
     * @return $this
     */
    public function setBankcardPhoto($bankcard_photo)
    {
        $this->bankcard_photo = $bankcard_photo;

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
     * Returns the value of field company_id
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * Returns the value of field bank
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Returns the value of field number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
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
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the value of field bank_type
     *
     * @return integer
     */
    public function getBankType()
    {
        return $this->bank_type;
    }

    /**
     * Returns the value of field account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Returns the value of field category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns the value of field bankcard_photo
     *
     * @return string
     */
    public function getBankcardPhoto()
    {
        return $this->bankcard_photo;
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
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyBank[]|CompanyBank
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyBank
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
        return 'company_bank';
    }

}
