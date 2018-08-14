<?php

namespace App\Entities;

class HuobullDealRepay extends \Phalcon\Mvc\Model
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
    protected $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    protected $l_key;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $self_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $true_self_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $self_money_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $interest_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $true_interest_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $interest_money_status;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $repay_date;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $true_repay_date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_true_repay;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $manage_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $impose_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $manage_impose_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $manage_money_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $impose_money_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $manage_impose_money_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $has_repay;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_npl;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $loantype;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
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
     * Method to set the value of field l_key
     *
     * @param integer $l_key
     * @return $this
     */
    public function setLKey($l_key)
    {
        $this->l_key = $l_key;

        return $this;
    }

    /**
     * Method to set the value of field self_money
     *
     * @param integer $self_money
     * @return $this
     */
    public function setSelfMoney($self_money)
    {
        $this->self_money = $self_money;

        return $this;
    }

    /**
     * Method to set the value of field true_self_money
     *
     * @param integer $true_self_money
     * @return $this
     */
    public function setTrueSelfMoney($true_self_money)
    {
        $this->true_self_money = $true_self_money;

        return $this;
    }

    /**
     * Method to set the value of field self_money_status
     *
     * @param integer $self_money_status
     * @return $this
     */
    public function setSelfMoneyStatus($self_money_status)
    {
        $this->self_money_status = $self_money_status;

        return $this;
    }

    /**
     * Method to set the value of field interest_money
     *
     * @param integer $interest_money
     * @return $this
     */
    public function setInterestMoney($interest_money)
    {
        $this->interest_money = $interest_money;

        return $this;
    }

    /**
     * Method to set the value of field true_interest_money
     *
     * @param integer $true_interest_money
     * @return $this
     */
    public function setTrueInterestMoney($true_interest_money)
    {
        $this->true_interest_money = $true_interest_money;

        return $this;
    }

    /**
     * Method to set the value of field interest_money_status
     *
     * @param integer $interest_money_status
     * @return $this
     */
    public function setInterestMoneyStatus($interest_money_status)
    {
        $this->interest_money_status = $interest_money_status;

        return $this;
    }

    /**
     * Method to set the value of field repay_date
     *
     * @param string $repay_date
     * @return $this
     */
    public function setRepayDate($repay_date)
    {
        $this->repay_date = $repay_date;

        return $this;
    }

    /**
     * Method to set the value of field true_repay_date
     *
     * @param string $true_repay_date
     * @return $this
     */
    public function setTrueRepayDate($true_repay_date)
    {
        $this->true_repay_date = $true_repay_date;

        return $this;
    }

    /**
     * Method to set the value of field time_true_repay
     *
     * @param integer $time_true_repay
     * @return $this
     */
    public function setTimeTrueRepay($time_true_repay)
    {
        $this->time_true_repay = $time_true_repay;

        return $this;
    }

    /**
     * Method to set the value of field manage_money
     *
     * @param integer $manage_money
     * @return $this
     */
    public function setManageMoney($manage_money)
    {
        $this->manage_money = $manage_money;

        return $this;
    }

    /**
     * Method to set the value of field impose_money
     *
     * @param integer $impose_money
     * @return $this
     */
    public function setImposeMoney($impose_money)
    {
        $this->impose_money = $impose_money;

        return $this;
    }

    /**
     * Method to set the value of field manage_impose_money
     *
     * @param integer $manage_impose_money
     * @return $this
     */
    public function setManageImposeMoney($manage_impose_money)
    {
        $this->manage_impose_money = $manage_impose_money;

        return $this;
    }

    /**
     * Method to set the value of field manage_money_status
     *
     * @param integer $manage_money_status
     * @return $this
     */
    public function setManageMoneyStatus($manage_money_status)
    {
        $this->manage_money_status = $manage_money_status;

        return $this;
    }

    /**
     * Method to set the value of field impose_money_status
     *
     * @param integer $impose_money_status
     * @return $this
     */
    public function setImposeMoneyStatus($impose_money_status)
    {
        $this->impose_money_status = $impose_money_status;

        return $this;
    }

    /**
     * Method to set the value of field manage_impose_money_status
     *
     * @param integer $manage_impose_money_status
     * @return $this
     */
    public function setManageImposeMoneyStatus($manage_impose_money_status)
    {
        $this->manage_impose_money_status = $manage_impose_money_status;

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
     * Method to set the value of field has_repay
     *
     * @param integer $has_repay
     * @return $this
     */
    public function setHasRepay($has_repay)
    {
        $this->has_repay = $has_repay;

        return $this;
    }

    /**
     * Method to set the value of field is_npl
     *
     * @param integer $is_npl
     * @return $this
     */
    public function setIsNpl($is_npl)
    {
        $this->is_npl = $is_npl;

        return $this;
    }

    /**
     * Method to set the value of field loantype
     *
     * @param integer $loantype
     * @return $this
     */
    public function setLoantype($loantype)
    {
        $this->loantype = $loantype;

        return $this;
    }

    /**
     * Method to set the value of field time_update
     *
     * @param string $time_update
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
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field l_key
     *
     * @return integer
     */
    public function getLKey()
    {
        return $this->l_key;
    }

    /**
     * Returns the value of field self_money
     *
     * @return integer
     */
    public function getSelfMoney()
    {
        return $this->self_money;
    }

    /**
     * Returns the value of field true_self_money
     *
     * @return integer
     */
    public function getTrueSelfMoney()
    {
        return $this->true_self_money;
    }

    /**
     * Returns the value of field self_money_status
     *
     * @return integer
     */
    public function getSelfMoneyStatus()
    {
        return $this->self_money_status;
    }

    /**
     * Returns the value of field interest_money
     *
     * @return integer
     */
    public function getInterestMoney()
    {
        return $this->interest_money;
    }

    /**
     * Returns the value of field true_interest_money
     *
     * @return integer
     */
    public function getTrueInterestMoney()
    {
        return $this->true_interest_money;
    }

    /**
     * Returns the value of field interest_money_status
     *
     * @return integer
     */
    public function getInterestMoneyStatus()
    {
        return $this->interest_money_status;
    }

    /**
     * Returns the value of field repay_date
     *
     * @return string
     */
    public function getRepayDate()
    {
        return $this->repay_date;
    }

    /**
     * Returns the value of field true_repay_date
     *
     * @return string
     */
    public function getTrueRepayDate()
    {
        return $this->true_repay_date;
    }

    /**
     * Returns the value of field time_true_repay
     *
     * @return integer
     */
    public function getTimeTrueRepay()
    {
        return $this->time_true_repay;
    }

    /**
     * Returns the value of field manage_money
     *
     * @return integer
     */
    public function getManageMoney()
    {
        return $this->manage_money;
    }

    /**
     * Returns the value of field impose_money
     *
     * @return integer
     */
    public function getImposeMoney()
    {
        return $this->impose_money;
    }

    /**
     * Returns the value of field manage_impose_money
     *
     * @return integer
     */
    public function getManageImposeMoney()
    {
        return $this->manage_impose_money;
    }

    /**
     * Returns the value of field manage_money_status
     *
     * @return integer
     */
    public function getManageMoneyStatus()
    {
        return $this->manage_money_status;
    }

    /**
     * Returns the value of field impose_money_status
     *
     * @return integer
     */
    public function getImposeMoneyStatus()
    {
        return $this->impose_money_status;
    }

    /**
     * Returns the value of field manage_impose_money_status
     *
     * @return integer
     */
    public function getManageImposeMoneyStatus()
    {
        return $this->manage_impose_money_status;
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
     * Returns the value of field has_repay
     *
     * @return integer
     */
    public function getHasRepay()
    {
        return $this->has_repay;
    }

    /**
     * Returns the value of field is_npl
     *
     * @return integer
     */
    public function getIsNpl()
    {
        return $this->is_npl;
    }

    /**
     * Returns the value of field loantype
     *
     * @return integer
     */
    public function getLoantype()
    {
        return $this->loantype;
    }

    /**
     * Returns the value of field time_update
     *
     * @return string
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
        return 'huobull_deal_repay';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealRepay[]|HuobullDealRepay
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealRepay
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
