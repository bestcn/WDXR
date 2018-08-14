<?php

namespace App\Entities;

class HuobullUserCompany extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var string
     * @Column(type="string", length=150, nullable=false)
     */
    protected $company_name;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $contact;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $officetype;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $officedomain;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $officecale;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $register_capital;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    protected $asset_value;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $officeaddress;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $description;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $bankLicense;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $orgNo;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $businessLicense;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    protected $taxNo;

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
     * Method to set the value of field company_name
     *
     * @param string $company_name
     * @return $this
     */
    public function setCompanyName($company_name)
    {
        $this->company_name = $company_name;

        return $this;
    }

    /**
     * Method to set the value of field contact
     *
     * @param string $contact
     * @return $this
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Method to set the value of field officetype
     *
     * @param string $officetype
     * @return $this
     */
    public function setOfficetype($officetype)
    {
        $this->officetype = $officetype;

        return $this;
    }

    /**
     * Method to set the value of field officedomain
     *
     * @param string $officedomain
     * @return $this
     */
    public function setOfficedomain($officedomain)
    {
        $this->officedomain = $officedomain;

        return $this;
    }

    /**
     * Method to set the value of field officecale
     *
     * @param string $officecale
     * @return $this
     */
    public function setOfficecale($officecale)
    {
        $this->officecale = $officecale;

        return $this;
    }

    /**
     * Method to set the value of field register_capital
     *
     * @param string $register_capital
     * @return $this
     */
    public function setRegisterCapital($register_capital)
    {
        $this->register_capital = $register_capital;

        return $this;
    }

    /**
     * Method to set the value of field asset_value
     *
     * @param string $asset_value
     * @return $this
     */
    public function setAssetValue($asset_value)
    {
        $this->asset_value = $asset_value;

        return $this;
    }

    /**
     * Method to set the value of field officeaddress
     *
     * @param string $officeaddress
     * @return $this
     */
    public function setOfficeaddress($officeaddress)
    {
        $this->officeaddress = $officeaddress;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field bankLicense
     *
     * @param string $bankLicense
     * @return $this
     */
    public function setBankLicense($bankLicense)
    {
        $this->bankLicense = $bankLicense;

        return $this;
    }

    /**
     * Method to set the value of field orgNo
     *
     * @param string $orgNo
     * @return $this
     */
    public function setOrgNo($orgNo)
    {
        $this->orgNo = $orgNo;

        return $this;
    }

    /**
     * Method to set the value of field businessLicense
     *
     * @param string $businessLicense
     * @return $this
     */
    public function setBusinessLicense($businessLicense)
    {
        $this->businessLicense = $businessLicense;

        return $this;
    }

    /**
     * Method to set the value of field taxNo
     *
     * @param string $taxNo
     * @return $this
     */
    public function setTaxNo($taxNo)
    {
        $this->taxNo = $taxNo;

        return $this;
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
     * Returns the value of field company_name
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Returns the value of field contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Returns the value of field officetype
     *
     * @return string
     */
    public function getOfficetype()
    {
        return $this->officetype;
    }

    /**
     * Returns the value of field officedomain
     *
     * @return string
     */
    public function getOfficedomain()
    {
        return $this->officedomain;
    }

    /**
     * Returns the value of field officecale
     *
     * @return string
     */
    public function getOfficecale()
    {
        return $this->officecale;
    }

    /**
     * Returns the value of field register_capital
     *
     * @return string
     */
    public function getRegisterCapital()
    {
        return $this->register_capital;
    }

    /**
     * Returns the value of field asset_value
     *
     * @return string
     */
    public function getAssetValue()
    {
        return $this->asset_value;
    }

    /**
     * Returns the value of field officeaddress
     *
     * @return string
     */
    public function getOfficeaddress()
    {
        return $this->officeaddress;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field bankLicense
     *
     * @return string
     */
    public function getBankLicense()
    {
        return $this->bankLicense;
    }

    /**
     * Returns the value of field orgNo
     *
     * @return string
     */
    public function getOrgNo()
    {
        return $this->orgNo;
    }

    /**
     * Returns the value of field businessLicense
     *
     * @return string
     */
    public function getBusinessLicense()
    {
        return $this->businessLicense;
    }

    /**
     * Returns the value of field taxNo
     *
     * @return string
     */
    public function getTaxNo()
    {
        return $this->taxNo;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("niup2p");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'huobull_user_company';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserCompany[]|HuobullUserCompany
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserCompany
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
