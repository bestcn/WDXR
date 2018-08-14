<?php

namespace Wdxr\Models\Entities;

/**
 * BonusSystem
 * 
 * @package Wdxr\Models\Entities
 * @autogenerated by Phalcon Developer Tools
 * @date 2018-05-29, 02:58:29
 */
class BonusSystem extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $recommend;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $customer;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $first;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $second;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $third;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $fourth;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $fifth;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $sixth;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $seventh;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $eighth;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $ninth;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $tenth;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $eleventh;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=true)
     */
    protected $twelfth;

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
     * Method to set the value of field recommend
     *
     * @param integer $recommend
     * @return $this
     */
    public function setRecommend($recommend)
    {
        $this->recommend = $recommend;

        return $this;
    }

    /**
     * Method to set the value of field customer
     *
     * @param integer $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Method to set the value of field first
     *
     * @param double $first
     * @return $this
     */
    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * Method to set the value of field second
     *
     * @param double $second
     * @return $this
     */
    public function setSecond($second)
    {
        $this->second = $second;

        return $this;
    }

    /**
     * Method to set the value of field third
     *
     * @param double $third
     * @return $this
     */
    public function setThird($third)
    {
        $this->third = $third;

        return $this;
    }

    /**
     * Method to set the value of field fourth
     *
     * @param double $fourth
     * @return $this
     */
    public function setFourth($fourth)
    {
        $this->fourth = $fourth;

        return $this;
    }

    /**
     * Method to set the value of field fifth
     *
     * @param double $fifth
     * @return $this
     */
    public function setFifth($fifth)
    {
        $this->fifth = $fifth;

        return $this;
    }

    /**
     * Method to set the value of field sixth
     *
     * @param double $sixth
     * @return $this
     */
    public function setSixth($sixth)
    {
        $this->sixth = $sixth;

        return $this;
    }

    /**
     * Method to set the value of field seventh
     *
     * @param double $seventh
     * @return $this
     */
    public function setSeventh($seventh)
    {
        $this->seventh = $seventh;

        return $this;
    }

    /**
     * Method to set the value of field eighth
     *
     * @param double $eighth
     * @return $this
     */
    public function setEighth($eighth)
    {
        $this->eighth = $eighth;

        return $this;
    }

    /**
     * Method to set the value of field ninth
     *
     * @param double $ninth
     * @return $this
     */
    public function setNinth($ninth)
    {
        $this->ninth = $ninth;

        return $this;
    }

    /**
     * Method to set the value of field tenth
     *
     * @param double $tenth
     * @return $this
     */
    public function setTenth($tenth)
    {
        $this->tenth = $tenth;

        return $this;
    }

    /**
     * Method to set the value of field eleventh
     *
     * @param double $eleventh
     * @return $this
     */
    public function setEleventh($eleventh)
    {
        $this->eleventh = $eleventh;

        return $this;
    }

    /**
     * Method to set the value of field twelfth
     *
     * @param double $twelfth
     * @return $this
     */
    public function setTwelfth($twelfth)
    {
        $this->twelfth = $twelfth;

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
     * Returns the value of field recommend
     *
     * @return integer
     */
    public function getRecommend()
    {
        return $this->recommend;
    }

    /**
     * Returns the value of field customer
     *
     * @return integer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Returns the value of field first
     *
     * @return double
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * Returns the value of field second
     *
     * @return double
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * Returns the value of field third
     *
     * @return double
     */
    public function getThird()
    {
        return $this->third;
    }

    /**
     * Returns the value of field fourth
     *
     * @return double
     */
    public function getFourth()
    {
        return $this->fourth;
    }

    /**
     * Returns the value of field fifth
     *
     * @return double
     */
    public function getFifth()
    {
        return $this->fifth;
    }

    /**
     * Returns the value of field sixth
     *
     * @return double
     */
    public function getSixth()
    {
        return $this->sixth;
    }

    /**
     * Returns the value of field seventh
     *
     * @return double
     */
    public function getSeventh()
    {
        return $this->seventh;
    }

    /**
     * Returns the value of field eighth
     *
     * @return double
     */
    public function getEighth()
    {
        return $this->eighth;
    }

    /**
     * Returns the value of field ninth
     *
     * @return double
     */
    public function getNinth()
    {
        return $this->ninth;
    }

    /**
     * Returns the value of field tenth
     *
     * @return double
     */
    public function getTenth()
    {
        return $this->tenth;
    }

    /**
     * Returns the value of field eleventh
     *
     * @return double
     */
    public function getEleventh()
    {
        return $this->eleventh;
    }

    /**
     * Returns the value of field twelfth
     *
     * @return double
     */
    public function getTwelfth()
    {
        return $this->twelfth;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'bonus_system';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BonusSystem[]|BonusSystem
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BonusSystem
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}