<?php

namespace Wdxr\Models\Entities;

class Loan extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=3, nullable=false)
     */
    protected $state;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $device_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $tel;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $system_loan;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $sponsion;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $other_loan;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $unhealthy;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $last_year;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $this_year;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $quota;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $remarks;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $company_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $payment_id;

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
     * Method to set the value of field state
     *
     * @param integer $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Method to set the value of field device_id
     *
     * @param integer $device_id
     * @return $this
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;

        return $this;
    }

    /**
     * Method to set the value of field tel
     *
     * @param string $tel
     * @return $this
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Method to set the value of field system_loan
     *
     * @param string $system_loan
     * @return $this
     */
    public function setSystemLoan($system_loan)
    {
        $this->system_loan = $system_loan;

        return $this;
    }

    /**
     * Method to set the value of field sponsion
     *
     * @param string $sponsion
     * @return $this
     */
    public function setSponsion($sponsion)
    {
        $this->sponsion = $sponsion;

        return $this;
    }

    /**
     * Method to set the value of field other_loan
     *
     * @param string $other_loan
     * @return $this
     */
    public function setOtherLoan($other_loan)
    {
        $this->other_loan = $other_loan;

        return $this;
    }

    /**
     * Method to set the value of field unhealthy
     *
     * @param string $unhealthy
     * @return $this
     */
    public function setUnhealthy($unhealthy)
    {
        $this->unhealthy = $unhealthy;

        return $this;
    }

    /**
     * Method to set the value of field last_year
     *
     * @param string $last_year
     * @return $this
     */
    public function setLastYear($last_year)
    {
        $this->last_year = $last_year;

        return $this;
    }

    /**
     * Method to set the value of field this_year
     *
     * @param string $this_year
     * @return $this
     */
    public function setThisYear($this_year)
    {
        $this->this_year = $this_year;

        return $this;
    }

    /**
     * Method to set the value of field quota
     *
     * @param string $quota
     * @return $this
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Method to set the value of field remarks
     *
     * @param string $remarks
     * @return $this
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Method to set the value of field company_id
     *
     * @param integer $company_id
     * @return $this
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;

        return $this;
    }

    /**
     * Method to set the value of field payment_id
     *
     * @param integer $payment_id
     * @return $this
     */
    public function setPaymentId($payment_id)
    {
        $this->payment_id = $payment_id;

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
     * Returns the value of field state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Returns the value of field device_id
     *
     * @return integer
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * Returns the value of field tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Returns the value of field system_loan
     *
     * @return string
     */
    public function getSystemLoan()
    {
        return $this->system_loan;
    }

    /**
     * Returns the value of field sponsion
     *
     * @return string
     */
    public function getSponsion()
    {
        return $this->sponsion;
    }

    /**
     * Returns the value of field other_loan
     *
     * @return string
     */
    public function getOtherLoan()
    {
        return $this->other_loan;
    }

    /**
     * Returns the value of field unhealthy
     *
     * @return string
     */
    public function getUnhealthy()
    {
        return $this->unhealthy;
    }

    /**
     * Returns the value of field last_year
     *
     * @return string
     */
    public function getLastYear()
    {
        return $this->last_year;
    }

    /**
     * Returns the value of field this_year
     *
     * @return string
     */
    public function getThisYear()
    {
        return $this->this_year;
    }

    /**
     * Returns the value of field quota
     *
     * @return string
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Returns the value of field remarks
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Returns the value of field company_id
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * Returns the value of field payment_id
     *
     * @return integer
     */
    public function getPaymentId()
    {
        return $this->payment_id;
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
     * @return Loan[]|Loan
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Loan
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
        return 'loan';
    }

}
