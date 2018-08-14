<?php

namespace Wdxr\Models\Entities;

class Levels extends \Phalcon\Mvc\Model
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
    protected $level_name;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $level_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $level_status;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $day_amount;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $info;

    /**
     *
     * @var string
     * @Column(type="string", length=3, nullable=true)
     */
    protected $is_default;

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
     * Method to set the value of field level_name
     *
     * @param string $level_name
     * @return $this
     */
    public function setLevelName($level_name)
    {
        $this->level_name = $level_name;

        return $this;
    }

    /**
     * Method to set the value of field level_money
     *
     * @param double $level_money
     * @return $this
     */
    public function setLevelMoney($level_money)
    {
        $this->level_money = $level_money;

        return $this;
    }

    /**
     * Method to set the value of field level_status
     *
     * @param integer $level_status
     * @return $this
     */
    public function setLevelStatus($level_status)
    {
        $this->level_status = $level_status;

        return $this;
    }

    /**
     * Method to set the value of field day_amount
     *
     * @param double $day_amount
     * @return $this
     */
    public function setDayAmount($day_amount)
    {
        $this->day_amount = $day_amount;

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
     * Method to set the value of field is_default
     *
     * @param integer $is_default
     * @return $this
     */
    public function setIsDefault($is_default)
    {
        $this->is_default = $is_default;

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
     * Returns the value of field level_name
     *
     * @return string
     */
    public function getLevelName()
    {
        return $this->level_name;
    }

    /**
     * Returns the value of field level_money
     *
     * @return double
     */
    public function getLevelMoney()
    {
        return $this->level_money;
    }

    /**
     * Returns the value of field level_status
     *
     * @return integer
     */
    public function getLevelStatus()
    {
        return $this->level_status;
    }

    /**
     * Returns the value of field day_amount
     *
     * @return double
     */
    public function getDayAmount()
    {
        return $this->day_amount;
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
     * Returns the value of field is_default
     *
     * @return integer
     */
    public function getIsDefault()
    {
        return $this->is_default;
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
     * @return Levels[]|Levels
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Levels
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
        return 'levels';
    }

}
