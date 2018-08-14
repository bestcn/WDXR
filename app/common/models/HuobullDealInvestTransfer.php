<?php

namespace App\Entities;

class HuobullDealInvestTransfer extends \Phalcon\Mvc\Model
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
    protected $deal_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $invest_id;

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
    protected $transfer_amount;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $invest_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_last_repay;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_near_repay;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $transfer_number;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $t_user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_transfer;

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
    protected $callback_count;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $lock_user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_lock;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $ips_status;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=true)
     */
    protected $ips_bill_no;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=true)
     */
    protected $pMerBillNo;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $create_date;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $transfer_date;

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
     * Method to set the value of field transfer_amount
     *
     * @param integer $transfer_amount
     * @return $this
     */
    public function setTransferAmount($transfer_amount)
    {
        $this->transfer_amount = $transfer_amount;

        return $this;
    }

    /**
     * Method to set the value of field invest_money
     *
     * @param integer $invest_money
     * @return $this
     */
    public function setInvestMoney($invest_money)
    {
        $this->invest_money = $invest_money;

        return $this;
    }

    /**
     * Method to set the value of field time_last_repay
     *
     * @param integer $time_last_repay
     * @return $this
     */
    public function setTimeLastRepay($time_last_repay)
    {
        $this->time_last_repay = $time_last_repay;

        return $this;
    }

    /**
     * Method to set the value of field time_near_repay
     *
     * @param integer $time_near_repay
     * @return $this
     */
    public function setTimeNearRepay($time_near_repay)
    {
        $this->time_near_repay = $time_near_repay;

        return $this;
    }

    /**
     * Method to set the value of field transfer_number
     *
     * @param integer $transfer_number
     * @return $this
     */
    public function setTransferNumber($transfer_number)
    {
        $this->transfer_number = $transfer_number;

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
     * Method to set the value of field time_transfer
     *
     * @param integer $time_transfer
     * @return $this
     */
    public function setTimeTransfer($time_transfer)
    {
        $this->time_transfer = $time_transfer;

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
     * Method to set the value of field callback_count
     *
     * @param integer $callback_count
     * @return $this
     */
    public function setCallbackCount($callback_count)
    {
        $this->callback_count = $callback_count;

        return $this;
    }

    /**
     * Method to set the value of field lock_user_id
     *
     * @param integer $lock_user_id
     * @return $this
     */
    public function setLockUserId($lock_user_id)
    {
        $this->lock_user_id = $lock_user_id;

        return $this;
    }

    /**
     * Method to set the value of field time_lock
     *
     * @param integer $time_lock
     * @return $this
     */
    public function setTimeLock($time_lock)
    {
        $this->time_lock = $time_lock;

        return $this;
    }

    /**
     * Method to set the value of field ips_status
     *
     * @param integer $ips_status
     * @return $this
     */
    public function setIpsStatus($ips_status)
    {
        $this->ips_status = $ips_status;

        return $this;
    }

    /**
     * Method to set the value of field ips_bill_no
     *
     * @param string $ips_bill_no
     * @return $this
     */
    public function setIpsBillNo($ips_bill_no)
    {
        $this->ips_bill_no = $ips_bill_no;

        return $this;
    }

    /**
     * Method to set the value of field pMerBillNo
     *
     * @param string $pMerBillNo
     * @return $this
     */
    public function setPMerBillNo($pMerBillNo)
    {
        $this->pMerBillNo = $pMerBillNo;

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
     * Method to set the value of field transfer_date
     *
     * @param string $transfer_date
     * @return $this
     */
    public function setTransferDate($transfer_date)
    {
        $this->transfer_date = $transfer_date;

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
     * Returns the value of field invest_id
     *
     * @return integer
     */
    public function getInvestId()
    {
        return $this->invest_id;
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
     * Returns the value of field transfer_amount
     *
     * @return integer
     */
    public function getTransferAmount()
    {
        return $this->transfer_amount;
    }

    /**
     * Returns the value of field invest_money
     *
     * @return integer
     */
    public function getInvestMoney()
    {
        return $this->invest_money;
    }

    /**
     * Returns the value of field time_last_repay
     *
     * @return integer
     */
    public function getTimeLastRepay()
    {
        return $this->time_last_repay;
    }

    /**
     * Returns the value of field time_near_repay
     *
     * @return integer
     */
    public function getTimeNearRepay()
    {
        return $this->time_near_repay;
    }

    /**
     * Returns the value of field transfer_number
     *
     * @return integer
     */
    public function getTransferNumber()
    {
        return $this->transfer_number;
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
     * Returns the value of field time_transfer
     *
     * @return integer
     */
    public function getTimeTransfer()
    {
        return $this->time_transfer;
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
     * Returns the value of field callback_count
     *
     * @return integer
     */
    public function getCallbackCount()
    {
        return $this->callback_count;
    }

    /**
     * Returns the value of field lock_user_id
     *
     * @return integer
     */
    public function getLockUserId()
    {
        return $this->lock_user_id;
    }

    /**
     * Returns the value of field time_lock
     *
     * @return integer
     */
    public function getTimeLock()
    {
        return $this->time_lock;
    }

    /**
     * Returns the value of field ips_status
     *
     * @return integer
     */
    public function getIpsStatus()
    {
        return $this->ips_status;
    }

    /**
     * Returns the value of field ips_bill_no
     *
     * @return string
     */
    public function getIpsBillNo()
    {
        return $this->ips_bill_no;
    }

    /**
     * Returns the value of field pMerBillNo
     *
     * @return string
     */
    public function getPMerBillNo()
    {
        return $this->pMerBillNo;
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
     * Returns the value of field transfer_date
     *
     * @return string
     */
    public function getTransferDate()
    {
        return $this->transfer_date;
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
        return 'huobull_deal_invest_transfer';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvestTransfer[]|HuobullDealInvestTransfer
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvestTransfer
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
