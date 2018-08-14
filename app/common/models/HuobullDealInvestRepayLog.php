<?php

namespace App\Entities;

class HuobullDealInvestRepayLog extends \Phalcon\Mvc\Model
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
    protected $invest_repay_id;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=false)
     */
    protected $order_no;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $brw_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    protected $guarantee_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
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
     * @Column(type="integer", length=2, nullable=false)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $remark;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=true)
     */
    protected $time_success;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
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
     * Method to set the value of field invest_repay_id
     *
     * @param integer $invest_repay_id
     * @return $this
     */
    public function setInvestRepayId($invest_repay_id)
    {
        $this->invest_repay_id = $invest_repay_id;

        return $this;
    }

    /**
     * Method to set the value of field order_no
     *
     * @param string $order_no
     * @return $this
     */
    public function setOrderNo($order_no)
    {
        $this->order_no = $order_no;

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
     * Method to set the value of field remark
     *
     * @param string $remark
     * @return $this
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Method to set the value of field time_success
     *
     * @param integer $time_success
     * @return $this
     */
    public function setTimeSuccess($time_success)
    {
        $this->time_success = $time_success;

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
     * Returns the value of field invest_repay_id
     *
     * @return integer
     */
    public function getInvestRepayId()
    {
        return $this->invest_repay_id;
    }

    /**
     * Returns the value of field order_no
     *
     * @return string
     */
    public function getOrderNo()
    {
        return $this->order_no;
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
     * Returns the value of field guarantee_id
     *
     * @return integer
     */
    public function getGuaranteeId()
    {
        return $this->guarantee_id;
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
     * Returns the value of field type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the value of field remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Returns the value of field time_success
     *
     * @return integer
     */
    public function getTimeSuccess()
    {
        return $this->time_success;
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
        return 'huobull_deal_invest_repay_log';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvestRepayLog[]|HuobullDealInvestRepayLog
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDealInvestRepayLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
