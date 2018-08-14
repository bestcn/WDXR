<?php

namespace Wdxr\Models\Entities;

class CompanyBillInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=128, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $bill_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $device_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $admin_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $company_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $createAt;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $user_submit;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $amount;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $rent;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $rent_receipt;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $rent_contract;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $property_fee;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $water_fee;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $electricity;

    /**
     * Method to set the value of field id
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * Method to set the value of field admin_id
     *
     * @param integer $admin_id
     * @return $this
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;

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
     * Method to set the value of field user_submit
     *
     * @param integer $user_submit
     * @return $this
     */
    public function setUserSubmit($user_submit)
    {
        $this->user_submit = $user_submit;

        return $this;
    }

    /**
     * Method to set the value of field amount
     *
     * @param double $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Method to set the value of field rent
     *
     * @param string $rent
     * @return $this
     */
    public function setRent($rent)
    {
        $this->rent = $rent;

        return $this;
    }

    /**
     * Method to set the value of field rent_receipt
     *
     * @param string $rent_receipt
     * @return $this
     */
    public function setRentReceipt($rent_receipt)
    {
        $this->rent_receipt = $rent_receipt;

        return $this;
    }

    /**
     * Method to set the value of field rent_contract
     *
     * @param string $rent_contract
     * @return $this
     */
    public function setRentContract($rent_contract)
    {
        $this->rent_contract = $rent_contract;

        return $this;
    }

    /**
     * Method to set the value of field property_fee
     *
     * @param string $property_fee
     * @return $this
     */
    public function setPropertyFee($property_fee)
    {
        $this->property_fee = $property_fee;

        return $this;
    }

    /**
     * Method to set the value of field water_fee
     *
     * @param string $water_fee
     * @return $this
     */
    public function setWaterFee($water_fee)
    {
        $this->water_fee = $water_fee;

        return $this;
    }

    /**
     * Method to set the value of field electricity
     *
     * @param string $electricity
     * @return $this
     */
    public function setElectricity($electricity)
    {
        $this->electricity = $electricity;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * Returns the value of field bill_id
     *
     * @return integer
     */
    public function getBillId()
    {
        return $this->bill_id;
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
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
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
     * Returns the value of field createAt
     *
     * @return string
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Returns the value of field user_submit
     *
     * @return integer
     */
    public function getUserSubmit()
    {
        return $this->user_submit;
    }

    /**
     * Returns the value of field amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the value of field rent
     *
     * @return string
     */
    public function getRent()
    {
        return $this->rent;
    }

    /**
     * Returns the value of field rent_receipt
     *
     * @return string
     */
    public function getRentReceipt()
    {
        return $this->rent_receipt;
    }

    /**
     * Returns the value of field rent_contract
     *
     * @return string
     */
    public function getRentContract()
    {
        return $this->rent_contract;
    }

    /**
     * Returns the value of field property_fee
     *
     * @return string
     */
    public function getPropertyFee()
    {
        return $this->property_fee;
    }

    /**
     * Returns the value of field water_fee
     *
     * @return string
     */
    public function getWaterFee()
    {
        return $this->water_fee;
    }

    /**
     * Returns the value of field electricity
     *
     * @return string
     */
    public function getElectricity()
    {
        return $this->electricity;
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
     * @return CompanyBillInfo[]|CompanyBillInfo
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyBillInfo
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
        return 'company_bill_info';
    }

}
