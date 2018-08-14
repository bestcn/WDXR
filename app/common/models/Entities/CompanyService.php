<?php

namespace Wdxr\Models\Entities;

class CompanyService extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $company_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $start_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $end_time;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $createAt;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $bill_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $level_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $bill_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $payment_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $report_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $service_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $type;

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
     * Method to set the value of field start_time
     *
     * @param integer $start_time
     * @return $this
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;

        return $this;
    }

    /**
     * Method to set the value of field end_time
     *
     * @param integer $end_time
     * @return $this
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;

        return $this;
    }

    /**
     * Method to set the value of field createAt
     *
     * @param string $createAt
     * @return $this
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Method to set the value of field bill_id
     *
     * @param integer $bill_id
     * @return $this
     */
    public function setBillId($bill_id)
    {
        $this->bill_id = $bill_id;

        return $this;
    }

    /**
     * Method to set the value of field level_id
     *
     * @param integer $level_id
     * @return $this
     */
    public function setLevelId($level_id)
    {
        $this->level_id = $level_id;

        return $this;
    }

    /**
     * Method to set the value of field bill_status
     *
     * @param integer $bill_status
     * @return $this
     */
    public function setBillStatus($bill_status)
    {
        $this->bill_status = $bill_status;

        return $this;
    }

    /**
     * Method to set the value of field payment_status
     *
     * @param integer $payment_status
     * @return $this
     */
    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    /**
     * Method to set the value of field report_status
     *
     * @param integer $report_status
     * @return $this
     */
    public function setReportStatus($report_status)
    {
        $this->report_status = $report_status;

        return $this;
    }

    /**
     * Method to set the value of field service_status
     *
     * @param integer $service_status
     * @return $this
     */
    public function setServiceStatus($service_status)
    {
        $this->service_status = $service_status;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Returns the value of field start_time
     *
     * @return integer
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Returns the value of field end_time
     *
     * @return integer
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Returns the value of field createAt
     *
     * @return string
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Returns the value of field bill_id
     *
     * @return integer
     */
    public function getBillId()
    {
        return $this->bill_id;
    }

    /**
     * Returns the value of field level_id
     *
     * @return integer
     */
    public function getLevelId()
    {
        return $this->level_id;
    }

    /**
     * Returns the value of field bill_status
     *
     * @return integer
     */
    public function getBillStatus()
    {
        return $this->bill_status;
    }

    /**
     * Returns the value of field payment_status
     *
     * @return integer
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    /**
     * Returns the value of field report_status
     *
     * @return integer
     */
    public function getReportStatus()
    {
        return $this->report_status;
    }

    /**
     * Returns the value of field service_status
     *
     * @return integer
     */
    public function getServiceStatus()
    {
        return $this->service_status;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
        $this->hasOne('level_id', __NAMESPACE__ . '\Levels', 'id', [
            'alias' => 'level',
        ]);
        $this->hasOne('payment_id', __NAMESPACE__ . '\CompanyPayment', 'id', [
            'alias' => 'company_payment',
        ]);
        $this->hasOne('contract_id', __NAMESPACE__ . '\Contracts', 'id', [
            'alias' => 'contracts',
        ]);
        $this->hasOne('bill_id', __NAMESPACE__ . '\CompanyBill', 'id', [
            'alias' => 'company_bill',
        ]);
        $this->hasMany('id', __NAMESPACE__ . '\Contracts', 'service_id', [
            'alias' => 'contract',
        ]);
        $this->hasMany('id', __NAMESPACE__ . '\CompanyReport', 'service_id', [
            'alias' => 'company_report',
        ]);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyService[]|CompanyService
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyService
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
        return 'company_service';
    }

}
