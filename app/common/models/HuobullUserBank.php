<?php

namespace App\Entities;

class HuobullUserBank extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $bank_id;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=false)
     */
    protected $bankcard;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    protected $real_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $region_lv1;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $region_lv2;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $region_lv3;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $region_lv4;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $bankzone;

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
     * Method to set the value of field bank_id
     *
     * @param integer $bank_id
     * @return $this
     */
    public function setBankId($bank_id)
    {
        $this->bank_id = $bank_id;

        return $this;
    }

    /**
     * Method to set the value of field bankcard
     *
     * @param string $bankcard
     * @return $this
     */
    public function setBankcard($bankcard)
    {
        $this->bankcard = $bankcard;

        return $this;
    }

    /**
     * Method to set the value of field real_name
     *
     * @param string $real_name
     * @return $this
     */
    public function setRealName($real_name)
    {
        $this->real_name = $real_name;

        return $this;
    }

    /**
     * Method to set the value of field region_lv1
     *
     * @param integer $region_lv1
     * @return $this
     */
    public function setRegionLv1($region_lv1)
    {
        $this->region_lv1 = $region_lv1;

        return $this;
    }

    /**
     * Method to set the value of field region_lv2
     *
     * @param integer $region_lv2
     * @return $this
     */
    public function setRegionLv2($region_lv2)
    {
        $this->region_lv2 = $region_lv2;

        return $this;
    }

    /**
     * Method to set the value of field region_lv3
     *
     * @param integer $region_lv3
     * @return $this
     */
    public function setRegionLv3($region_lv3)
    {
        $this->region_lv3 = $region_lv3;

        return $this;
    }

    /**
     * Method to set the value of field region_lv4
     *
     * @param integer $region_lv4
     * @return $this
     */
    public function setRegionLv4($region_lv4)
    {
        $this->region_lv4 = $region_lv4;

        return $this;
    }

    /**
     * Method to set the value of field bankzone
     *
     * @param string $bankzone
     * @return $this
     */
    public function setBankzone($bankzone)
    {
        $this->bankzone = $bankzone;

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
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field bank_id
     *
     * @return integer
     */
    public function getBankId()
    {
        return $this->bank_id;
    }

    /**
     * Returns the value of field bankcard
     *
     * @return string
     */
    public function getBankcard()
    {
        return $this->bankcard;
    }

    /**
     * Returns the value of field real_name
     *
     * @return string
     */
    public function getRealName()
    {
        return $this->real_name;
    }

    /**
     * Returns the value of field region_lv1
     *
     * @return integer
     */
    public function getRegionLv1()
    {
        return $this->region_lv1;
    }

    /**
     * Returns the value of field region_lv2
     *
     * @return integer
     */
    public function getRegionLv2()
    {
        return $this->region_lv2;
    }

    /**
     * Returns the value of field region_lv3
     *
     * @return integer
     */
    public function getRegionLv3()
    {
        return $this->region_lv3;
    }

    /**
     * Returns the value of field region_lv4
     *
     * @return integer
     */
    public function getRegionLv4()
    {
        return $this->region_lv4;
    }

    /**
     * Returns the value of field bankzone
     *
     * @return string
     */
    public function getBankzone()
    {
        return $this->bankzone;
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
        return 'huobull_user_bank';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserBank[]|HuobullUserBank
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserBank
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
