<?php

namespace App\Entities;

class HuobullUserCarryConfig extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $min_price;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $max_price;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $fee_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $vip_id;

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
     * Method to set the value of field min_price
     *
     * @param integer $min_price
     * @return $this
     */
    public function setMinPrice($min_price)
    {
        $this->min_price = $min_price;

        return $this;
    }

    /**
     * Method to set the value of field max_price
     *
     * @param integer $max_price
     * @return $this
     */
    public function setMaxPrice($max_price)
    {
        $this->max_price = $max_price;

        return $this;
    }

    /**
     * Method to set the value of field fee
     *
     * @param integer $fee
     * @return $this
     */
    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Method to set the value of field fee_type
     *
     * @param integer $fee_type
     * @return $this
     */
    public function setFeeType($fee_type)
    {
        $this->fee_type = $fee_type;

        return $this;
    }

    /**
     * Method to set the value of field vip_id
     *
     * @param integer $vip_id
     * @return $this
     */
    public function setVipId($vip_id)
    {
        $this->vip_id = $vip_id;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field min_price
     *
     * @return integer
     */
    public function getMinPrice()
    {
        return $this->min_price;
    }

    /**
     * Returns the value of field max_price
     *
     * @return integer
     */
    public function getMaxPrice()
    {
        return $this->max_price;
    }

    /**
     * Returns the value of field fee
     *
     * @return integer
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Returns the value of field fee_type
     *
     * @return integer
     */
    public function getFeeType()
    {
        return $this->fee_type;
    }

    /**
     * Returns the value of field vip_id
     *
     * @return integer
     */
    public function getVipId()
    {
        return $this->vip_id;
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
        return 'huobull_user_carry_config';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserCarryConfig[]|HuobullUserCarryConfig
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserCarryConfig
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
