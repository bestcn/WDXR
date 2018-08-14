<?php

namespace Wdxr\Models\Entities;

class Statistics extends \Phalcon\Mvc\Model
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
    protected $company_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $company_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $bank_name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $bank_card;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $fee;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $recommends_fee;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $manages_fee;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $bonus;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $time;

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
     * Method to set the value of field company_name
     *
     * @param string $company_name
     * @return $this
     */
    public function setCompanyName($company_name)
    {
        $this->company_name = $company_name;

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
     * Method to set the value of field bank_card
     *
     * @param string $bank_card
     * @return $this
     */
    public function setBankCard($bank_card)
    {
        $this->bank_card = $bank_card;

        return $this;
    }

    /**
     * Method to set the value of field fee
     *
     * @param double $fee
     * @return $this
     */
    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Method to set the value of field recommends_fee
     *
     * @param double $recommends_fee
     * @return $this
     */
    public function setRecommendsFee($recommends_fee)
    {
        $this->recommends_fee = $recommends_fee;

        return $this;
    }

    /**
     * Method to set the value of field manages_fee
     *
     * @param double $manages_fee
     * @return $this
     */
    public function setManagesFee($manages_fee)
    {
        $this->manages_fee = $manages_fee;

        return $this;
    }

    /**
     * Method to set the value of field bonus
     *
     * @param double $bonus
     * @return $this
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field company_name
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company_name;
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
     * Returns the value of field bank_name
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * Returns the value of field bank_card
     *
     * @return string
     */
    public function getBankCard()
    {
        return $this->bank_card;
    }

    /**
     * Returns the value of field fee
     *
     * @return double
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Returns the value of field recommends_fee
     *
     * @return double
     */
    public function getRecommendsFee()
    {
        return $this->recommends_fee;
    }

    /**
     * Returns the value of field manages_fee
     *
     * @return double
     */
    public function getManagesFee()
    {
        return $this->manages_fee;
    }

    /**
     * Returns the value of field bonus
     *
     * @return double
     */
    public function getBonus()
    {
        return $this->bonus;
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
     * @return Statistics[]|Statistics
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Statistics
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
        return 'statistics';
    }

}
