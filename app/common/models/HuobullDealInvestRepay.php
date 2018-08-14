<?php

namespace App\Entities;

class HuobullDealInvestRepay extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=3, nullable=false)
     */
    protected $l_key;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $brw_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    protected $t_user_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $repay_date;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $true_repay_date;

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
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $impose_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $manage_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $manage_money_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $manage_interest_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $manage_interest_money_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $guarantee_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $repay_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $invest_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $has_repay;

    /**
     *
     * @var integer
     * @Column(type="integer", length=2, nullable=false)
     */
    protected $loantype;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $memo;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    protected $time_finish;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
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
     * Method to set the value of field brw_id
     *
     * @param integer $brw_id
     * @return $this
     */
    public function setBrwId($brw_id)
    {
        $this->brw_id = $brw_id;

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
     * Method to set the value of field t_user_id
     *
     * @param integer $t_user_id
     * @return $this
     */
    public function setTUserId($t_user_id)
    {
        $this->t_user_id = $t_user_id;

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
     * Method to set the value of field manage_interest_money
     *
     * @param integer $manage_interest_money
     * @return $this
     */
    public function setManageInterestMoney($manage_interest_money)
    {
        $this->manage_interest_money = $manage_interest_money;

        return $this;
    }

    /**
     * Method to set the value of field manage_interest_money_status
     *
     * @param integer $manage_interest_money_status
     * @return $this
     */
    public function setManageInterestMoneyStatus($manage_interest_money_status)
    {
        $this->manage_interest_money_status = $manage_interest_money_status;

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
     * Method to set the value of field guarantee_id
     *
     * @param integer $guarantee_id
     * @return $this
     */
    public function setGuaranteeId($guarantee_id)
    {
        $this->guarantee_id = $guarantee_id;

        return $this;
    }

    /**
     * Method to set the value of field repay_id
     *
     * @param integer $repay_id
     * @return $this
     */
    public function setRepayId($repay_id)
    {
        $this->repay_id = $repay_id;

        return $this;
    }

    /**
     * Method to set the value of field invest_id
     *
     * @param integer $invest_id
     * @return $this
     */
    public function setInvestId($invest_id)
    {
        $this->invest_id = $invest_id;

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
     * Method to set the value of field time_finish
     *
     * @param integer $time_finish
     * @return $this
     */
    public function setTimeFinish($time_finish)
    {
        $this->time_finish = $time_finish;

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
     * Returns the value of field l_key
     *
     * @return integer
     */
    public function getLKey()
    {
        return $this->l_key;
    }

    /**
     * Returns the value of field brw_id
     *
     * @return integer
     */
    public function getBrwId()
    {
        return $this->brw_id;
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
     * Returns the value of field t_user_id
     *
     * @return integer
     */
    public function getTUserId()
    {
        return $this->t_user_id;
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
     * Returns the value of field impose_money
     *
     * @return integer
     */
    public function getImposeMoney()
    {
        return $this->impose_money;
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
     * Returns the value of field manage_money_status
     *
     * @return integer
     */
    public function getManageMoneyStatus()
    {
        return $this->manage_money_status;
    }

    /**
     * Returns the value of field manage_interest_money
     *
     * @return integer
     */
    public function getManageInterestMoney()
    {
        return $this->manage_interest_money;
    }

    /**
     * Returns the value of field manage_interest_money_status
     *
     * @return integer
     */
    public function getManageInterestMoneyStatus()
    {
        return $this->manage_interest_money_status;
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
     * Returns the value of field guarantee_id
     *
     * @return integer
     */
    public function getGuaranteeId()
    {
        return $this->guarantee_id;
    }

    /**
     * Returns the value of field repay_id
     *
     * @return integer
     */
    public function getRepayId()
    {
        return $this->repay_id;
    }

    /**
     * Returns the value of field invest_id
     *
     * @return integer
     */
    public function getInvestId()
    {
        return $this->invest_id;
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
     * Returns the value of field loantype
     *
     * @return integer
     */
    public function getLoantype()
    {
        return $this->loantype;
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
     * Returns the value of field time_finish
     *
     * @return integer
     */
    public function getTimeFinish()
    {
        return $this->time_finish;
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
        return 'huobull_deal_invest_repay';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvestRepay[]|HuobullDealInvestRepay
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvestRepay
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
