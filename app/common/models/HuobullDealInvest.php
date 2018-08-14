<?php

namespace App\Entities;

class HuobullDealInvest extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $deal_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $log_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $realname;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $utype;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_repay;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_auto;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=false)
     */
    protected $deal_sn;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $deal_ips_sn;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_doloan;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_transfer;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    protected $to_user_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $memo;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $create_date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_update;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_create;

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
     * Method to set the value of field deal_id
     *
     * @param integer $deal_id
     * @return $this
     */
    public function setDealId($deal_id)
    {
        $this->deal_id = $deal_id;

        return $this;
    }

    /**
     * Method to set the value of field log_id
     *
     * @param integer $log_id
     * @return $this
     */
    public function setLogId($log_id)
    {
        $this->log_id = $log_id;

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
     * Method to set the value of field realname
     *
     * @param string $realname
     * @return $this
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Method to set the value of field utype
     *
     * @param integer $utype
     * @return $this
     */
    public function setUtype($utype)
    {
        $this->utype = $utype;

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
     * Method to set the value of field is_repay
     *
     * @param integer $is_repay
     * @return $this
     */
    public function setIsRepay($is_repay)
    {
        $this->is_repay = $is_repay;

        return $this;
    }

    /**
     * Method to set the value of field is_auto
     *
     * @param integer $is_auto
     * @return $this
     */
    public function setIsAuto($is_auto)
    {
        $this->is_auto = $is_auto;

        return $this;
    }

    /**
     * Method to set the value of field deal_sn
     *
     * @param string $deal_sn
     * @return $this
     */
    public function setDealSn($deal_sn)
    {
        $this->deal_sn = $deal_sn;

        return $this;
    }

    /**
     * Method to set the value of field deal_ips_sn
     *
     * @param string $deal_ips_sn
     * @return $this
     */
    public function setDealIpsSn($deal_ips_sn)
    {
        $this->deal_ips_sn = $deal_ips_sn;

        return $this;
    }

    /**
     * Method to set the value of field is_doloan
     *
     * @param integer $is_doloan
     * @return $this
     */
    public function setIsDoloan($is_doloan)
    {
        $this->is_doloan = $is_doloan;

        return $this;
    }

    /**
     * Method to set the value of field is_transfer
     *
     * @param integer $is_transfer
     * @return $this
     */
    public function setIsTransfer($is_transfer)
    {
        $this->is_transfer = $is_transfer;

        return $this;
    }

    /**
     * Method to set the value of field to_user_id
     *
     * @param integer $to_user_id
     * @return $this
     */
    public function setToUserId($to_user_id)
    {
        $this->to_user_id = $to_user_id;

        return $this;
    }

    /**
     * Method to set the value of field memo
     *
     * @param string $memo
     * @return $this
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field deal_id
     *
     * @return integer
     */
    public function getDealId()
    {
        return $this->deal_id;
    }

    /**
     * Returns the value of field log_id
     *
     * @return integer
     */
    public function getLogId()
    {
        return $this->log_id;
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
     * Returns the value of field realname
     *
     * @return string
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Returns the value of field utype
     *
     * @return integer
     */
    public function getUtype()
    {
        return $this->utype;
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
     * Returns the value of field is_repay
     *
     * @return integer
     */
    public function getIsRepay()
    {
        return $this->is_repay;
    }

    /**
     * Returns the value of field is_auto
     *
     * @return integer
     */
    public function getIsAuto()
    {
        return $this->is_auto;
    }

    /**
     * Returns the value of field deal_sn
     *
     * @return string
     */
    public function getDealSn()
    {
        return $this->deal_sn;
    }

    /**
     * Returns the value of field deal_ips_sn
     *
     * @return string
     */
    public function getDealIpsSn()
    {
        return $this->deal_ips_sn;
    }

    /**
     * Returns the value of field is_doloan
     *
     * @return integer
     */
    public function getIsDoloan()
    {
        return $this->is_doloan;
    }

    /**
     * Returns the value of field is_transfer
     *
     * @return integer
     */
    public function getIsTransfer()
    {
        return $this->is_transfer;
    }

    /**
     * Returns the value of field to_user_id
     *
     * @return integer
     */
    public function getToUserId()
    {
        return $this->to_user_id;
    }

    /**
     * Returns the value of field memo
     *
     * @return string
     */
    public function getMemo()
    {
        return $this->memo;
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
     * Returns the value of field time_update
     *
     * @return integer
     */
    public function getTimeUpdate()
    {
        return $this->time_update;
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
        return 'huobull_deal_invest';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvest[]|HuobullDealInvest
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvest
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
