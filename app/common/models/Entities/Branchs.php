<?php

namespace Wdxr\Models\Entities;

class Branchs extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=500, nullable=false)
     */
    protected $branch_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $branch_level;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $branch_area;

    /**
     *
     * @var string
     * @Column(type="string", length=11, nullable=false)
     */
    protected $branch_admin;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $branch_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $branch_admin_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $branch_phone;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $branch_account;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $branch_bank;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $provinces;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $cities;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $areas;

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
     * Method to set the value of field branch_name
     *
     * @param string $branch_name
     * @return $this
     */
    public function setBranchName($branch_name)
    {
        $this->branch_name = $branch_name;

        return $this;
    }

    /**
     * Method to set the value of field branch_level
     *
     * @param integer $branch_level
     * @return $this
     */
    public function setBranchLevel($branch_level)
    {
        $this->branch_level = $branch_level;

        return $this;
    }

    /**
     * Method to set the value of field branch_area
     *
     * @param string $branch_area
     * @return $this
     */
    public function setBranchArea($branch_area)
    {
        $this->branch_area = $branch_area;

        return $this;
    }

    /**
     * Method to set the value of field branch_admin
     *
     * @param string $branch_admin
     * @return $this
     */
    public function setBranchAdmin($branch_admin)
    {
        $this->branch_admin = $branch_admin;

        return $this;
    }

    /**
     * Method to set the value of field branch_status
     *
     * @param integer $branch_status
     * @return $this
     */
    public function setBranchStatus($branch_status)
    {
        $this->branch_status = $branch_status;

        return $this;
    }

    /**
     * Method to set the value of field branch_admin_id
     *
     * @param integer $branch_admin_id
     * @return $this
     */
    public function setBranchAdminId($branch_admin_id)
    {
        $this->branch_admin_id = $branch_admin_id;

        return $this;
    }

    /**
     * Method to set the value of field branch_phone
     *
     * @param string $branch_phone
     * @return $this
     */
    public function setBranchPhone($branch_phone)
    {
        $this->branch_phone = $branch_phone;

        return $this;
    }

    /**
     * Method to set the value of field branch_account
     *
     * @param string $branch_account
     * @return $this
     */
    public function setBranchAccount($branch_account)
    {
        $this->branch_account = $branch_account;

        return $this;
    }

    /**
     * Method to set the value of field branch_bank
     *
     * @param string $branch_bank
     * @return $this
     */
    public function setBranchBank($branch_bank)
    {
        $this->branch_bank = $branch_bank;

        return $this;
    }

    /**
     * Method to set the value of field provinces
     *
     * @param integer $provinces
     * @return $this
     */
    public function setProvinces($provinces)
    {
        $this->provinces = $provinces;

        return $this;
    }

    /**
     * Method to set the value of field cities
     *
     * @param integer $cities
     * @return $this
     */
    public function setCities($cities)
    {
        $this->cities = $cities;

        return $this;
    }

    /**
     * Method to set the value of field areas
     *
     * @param integer $areas
     * @return $this
     */
    public function setAreas($areas)
    {
        $this->areas = $areas;

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
     * Returns the value of field branch_name
     *
     * @return string
     */
    public function getBranchName()
    {
        return $this->branch_name;
    }

    /**
     * Returns the value of field branch_level
     *
     * @return integer
     */
    public function getBranchLevel()
    {
        return $this->branch_level;
    }

    /**
     * Returns the value of field branch_area
     *
     * @return string
     */
    public function getBranchArea()
    {
        return $this->branch_area;
    }

    /**
     * Returns the value of field branch_admin
     *
     * @return string
     */
    public function getBranchAdmin()
    {
        return $this->branch_admin;
    }

    /**
     * Returns the value of field branch_status
     *
     * @return integer
     */
    public function getBranchStatus()
    {
        return $this->branch_status;
    }

    /**
     * Returns the value of field branch_admin_id
     *
     * @return integer
     */
    public function getBranchAdminId()
    {
        return $this->branch_admin_id;
    }

    /**
     * Returns the value of field branch_phone
     *
     * @return string
     */
    public function getBranchPhone()
    {
        return $this->branch_phone;
    }

    /**
     * Returns the value of field branch_account
     *
     * @return string
     */
    public function getBranchAccount()
    {
        return $this->branch_account;
    }

    /**
     * Returns the value of field branch_bank
     *
     * @return string
     */
    public function getBranchBank()
    {
        return $this->branch_bank;
    }

    /**
     * Returns the value of field provinces
     *
     * @return integer
     */
    public function getProvinces()
    {
        return $this->provinces;
    }

    /**
     * Returns the value of field cities
     *
     * @return integer
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Returns the value of field areas
     *
     * @return integer
     */
    public function getAreas()
    {
        return $this->areas;
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
     * @return Branchs[]|Branchs
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Branchs
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
        return 'branchs';
    }

}
