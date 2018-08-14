<?php

namespace Wdxr\Models\Entities;

class Finances extends \Phalcon\Mvc\Model
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
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $makecoll;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $company_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $money;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $remark;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $time;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $start_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $day_count;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $bank_name;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $phone;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $byid;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $account_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $end_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $status;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $info;

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
     * Method to set the value of field makecoll
     *
     * @param string $makecoll
     * @return $this
     */
    public function setMakecoll($makecoll)
    {
        $this->makecoll = $makecoll;

        return $this;
    }

    /**
     * Method to set the value of field company_id
     *
     * @param string $company_id
     * @return $this
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;

        return $this;
    }

    /**
     * Method to set the value of field money
     *
     * @param string $money
     * @return $this
     */
    public function setMoney($money)
    {
        $this->money = $money;

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
     * Method to set the value of field time
     *
     * @param string $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Method to set the value of field start_time
     *
     * @param string $start_time
     * @return $this
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;

        return $this;
    }

    /**
     * Method to set the value of field day_count
     *
     * @param integer $day_count
     * @return $this
     */
    public function setDayCount($day_count)
    {
        $this->day_count = $day_count;

        return $this;
    }

    /**
     * Method to set the value of field bank_name
     *
     * @param string $bank_name
     * @return $this
     */
    public function setBankName($bank_name)
    {
        $this->bank_name = $bank_name;

        return $this;
    }

    /**
     * Method to set the value of field phone
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Method to set the value of field byid
     *
     * @param integer $byid
     * @return $this
     */
    public function setByid($byid)
    {
        $this->byid = $byid;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field account_id
     *
     * @param integer $account_id
     * @return $this
     */
    public function setAccountId($account_id)
    {
        $this->account_id = $account_id;

        return $this;
    }

    /**
     * Method to set the value of field end_time
     *
     * @param string $end_time
     * @return $this
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;

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
     * Method to set the value of field info
     *
     * @param string $info
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = $info;

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
     * Returns the value of field makecoll
     *
     * @return string
     */
    public function getMakecoll()
    {
        return $this->makecoll;
    }

    /**
     * Returns the value of field company_id
     *
     * @return string
     */
    public function getCompanyId()
    {
        return $this->company_id;
    }

    /**
     * Returns the value of field money
     *
     * @return float
     */
    public function getMoney()
    {
        return $this->money;
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
     * Returns the value of field time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Returns the value of field start_time
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Returns the value of field day_count
     *
     * @return integer
     */
    public function getDayCount()
    {
        return $this->day_count;
    }

    /**
     * Returns the value of field bank_name
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * Returns the value of field phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Returns the value of field byid
     *
     * @return integer
     */
    public function getByid()
    {
        return $this->byid;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field account_id
     *
     * @return integer
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * Returns the value of field end_time
     *
     * @return string
     */
    public function getEndTime()
    {
        return $this->end_time;
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
     * Returns the value of field info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
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
     * @return Finances[]|Finances
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Finances
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
        return 'finances';
    }

}
