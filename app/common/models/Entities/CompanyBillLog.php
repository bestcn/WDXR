<?php

namespace Wdxr\Models\Entities;

class CompanyBillLog extends \Phalcon\Mvc\Model
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
    protected $bill_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $verify_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $deceive_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $company_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $createAt;

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
     * Method to set the value of field verify_id
     *
     * @param integer $verify_id
     * @return $this
     */
    public function setVerifyId($verify_id)
    {
        $this->verify_id = $verify_id;

        return $this;
    }

    /**
     * Method to set the value of field deceive_id
     *
     * @param integer $deceive_id
     * @return $this
     */
    public function setDeceiveId($deceive_id)
    {
        $this->deceive_id = $deceive_id;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Returns the value of field verify_id
     *
     * @return integer
     */
    public function getVerifyId()
    {
        return $this->verify_id;
    }

    /**
     * Returns the value of field deceive_id
     *
     * @return integer
     */
    public function getDeceiveId()
    {
        return $this->deceive_id;
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
     * Returns the value of field type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
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
     * @return CompanyBillLog[]|CompanyBillLog
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyBillLog
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
        return 'company_bill_log';
    }

}
