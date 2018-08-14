<?php

namespace App\Entities;

class HuobullUserCarry extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $fee;

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
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_create;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_update;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $msg;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $desc;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=false)
     */
    protected $real_name;

    /**
     *
     * @var string
     * @Column(type="string", length=120, nullable=false)
     */
    protected $bankzone;

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
     * @Column(type="string", nullable=false)
     */
    protected $create_date;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $pingzheng;

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
     * Method to set the value of field fee
     *
     * @param integer $fee
     * @return $this
     */
    public function setFee($fee)
    {
        $this->fee = $fee;

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
     * Method to set the value of field time_create
     *
     * @param integer $time_create
     * @return $this
     */
    public function setTimeCreate($time_create)
    {
        $this->time_create = $time_create;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field time_update
     *
     * @param integer $time_update
     * @return $this
     */
    public function setTimeUpdate($time_update)
    {
        $this->time_update = $time_update;

        return $this;
    }

    /**
     * Method to set the value of field msg
     *
     * @param string $msg
     * @return $this
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Method to set the value of field desc
     *
     * @param string $desc
     * @return $this
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

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
     * Method to set the value of field create_date
     *
     * @param string $create_date
     * @return $this
     */
    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;

        return $this;
    }

    /**
     * Method to set the value of field pingzheng
     *
     * @param string $pingzheng
     * @return $this
     */
    public function setPingzheng($pingzheng)
    {
        $this->pingzheng = $pingzheng;

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
     * Returns the value of field money
     *
     * @return integer
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Returns the value of field fee
     *
     * @return integer
     */
    public function getFee()
    {
        return $this->fee;
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
     * Returns the value of field time_create
     *
     * @return integer
     */
    public function getTimeCreate()
    {
        return $this->time_create;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field time_update
     *
     * @return integer
     */
    public function getTimeUpdate()
    {
        return $this->time_update;
    }

    /**
     * Returns the value of field msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * Returns the value of field desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
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
     * Returns the value of field bankzone
     *
     * @return string
     */
    public function getBankzone()
    {
        return $this->bankzone;
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
     * Returns the value of field create_date
     *
     * @return string
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Returns the value of field pingzheng
     *
     * @return string
     */
    public function getPingzheng()
    {
        return $this->pingzheng;
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
        return 'huobull_user_carry';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserCarry[]|HuobullUserCarry
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserCarry
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
