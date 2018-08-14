<?php

namespace App\Entities;

class HuobullUserMoneyLog extends \Phalcon\Mvc\Model
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
    protected $account_money;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $memo;

    /**
     *
     * @var integer
     * @Column(type="integer", length=2, nullable=false)
     */
    protected $type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_create;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $time_create_ymd;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=false)
     */
    protected $time_create_ym;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $time_create_y;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $from_user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $from_deal_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $from_invest_repay_id;

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
     * Method to set the value of field account_money
     *
     * @param integer $account_money
     * @return $this
     */
    public function setAccountMoney($account_money)
    {
        $this->account_money = $account_money;

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
     * Method to set the value of field time_create_ymd
     *
     * @param string $time_create_ymd
     * @return $this
     */
    public function setTimeCreateYmd($time_create_ymd)
    {
        $this->time_create_ymd = $time_create_ymd;

        return $this;
    }

    /**
     * Method to set the value of field time_create_ym
     *
     * @param integer $time_create_ym
     * @return $this
     */
    public function setTimeCreateYm($time_create_ym)
    {
        $this->time_create_ym = $time_create_ym;

        return $this;
    }

    /**
     * Method to set the value of field time_create_y
     *
     * @param integer $time_create_y
     * @return $this
     */
    public function setTimeCreateY($time_create_y)
    {
        $this->time_create_y = $time_create_y;

        return $this;
    }

    /**
     * Method to set the value of field from_user_id
     *
     * @param integer $from_user_id
     * @return $this
     */
    public function setFromUserId($from_user_id)
    {
        $this->from_user_id = $from_user_id;

        return $this;
    }

    /**
     * Method to set the value of field from_deal_id
     *
     * @param integer $from_deal_id
     * @return $this
     */
    public function setFromDealId($from_deal_id)
    {
        $this->from_deal_id = $from_deal_id;

        return $this;
    }

    /**
     * Method to set the value of field from_invest_repay_id
     *
     * @param integer $from_invest_repay_id
     * @return $this
     */
    public function setFromInvestRepayId($from_invest_repay_id)
    {
        $this->from_invest_repay_id = $from_invest_repay_id;

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
     * Returns the value of field account_money
     *
     * @return integer
     */
    public function getAccountMoney()
    {
        return $this->account_money;
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
     * Returns the value of field type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
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
     * Returns the value of field time_create_ymd
     *
     * @return string
     */
    public function getTimeCreateYmd()
    {
        return $this->time_create_ymd;
    }

    /**
     * Returns the value of field time_create_ym
     *
     * @return integer
     */
    public function getTimeCreateYm()
    {
        return $this->time_create_ym;
    }

    /**
     * Returns the value of field time_create_y
     *
     * @return integer
     */
    public function getTimeCreateY()
    {
        return $this->time_create_y;
    }

    /**
     * Returns the value of field from_user_id
     *
     * @return integer
     */
    public function getFromUserId()
    {
        return $this->from_user_id;
    }

    /**
     * Returns the value of field from_deal_id
     *
     * @return integer
     */
    public function getFromDealId()
    {
        return $this->from_deal_id;
    }

    /**
     * Returns the value of field from_invest_repay_id
     *
     * @return integer
     */
    public function getFromInvestRepayId()
    {
        return $this->from_invest_repay_id;
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
        return 'huobull_user_money_log';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserMoneyLog[]|HuobullUserMoneyLog
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserMoneyLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
