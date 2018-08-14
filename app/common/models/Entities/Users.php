<?php

namespace Wdxr\Models\Entities;

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Validation;

class Users extends \Phalcon\Mvc\Model
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
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $password;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $phone;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $email;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $last_login_time;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $last_login_ip;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $is_partner;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $number;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $pic;

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
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field last_login_time
     *
     * @param integer $last_login_time
     * @return $this
     */
    public function setLastLoginTime($last_login_time)
    {
        $this->last_login_time = $last_login_time;

        return $this;
    }

    /**
     * Method to set the value of field last_login_ip
     *
     * @param string $last_login_ip
     * @return $this
     */
    public function setLastLoginIp($last_login_ip)
    {
        $this->last_login_ip = $last_login_ip;

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
     * Method to set the value of field is_partner
     *
     * @param integer $is_partner
     * @return $this
     */
    public function setIsPartner($is_partner)
    {
        $this->is_partner = $is_partner;

        return $this;
    }

    /**
     * Method to set the value of field number
     *
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Method to set the value of field pic
     *
     * @param integer $pic
     * @return $this
     */
    public function setPic($pic)
    {
        $this->pic = $pic;

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
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field last_login_time
     *
     * @return integer
     */
    public function getLastLoginTime()
    {
        return $this->last_login_time;
    }

    /**
     * Returns the value of field last_login_ip
     *
     * @return string
     */
    public function getLastLoginIp()
    {
        return $this->last_login_ip;
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
     * Returns the value of field is_partner
     *
     * @return integer
     */
    public function getIsPartner()
    {
        return $this->is_partner;
    }

    /**
     * Returns the value of field number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Returns the value of field pic
     *
     * @return integer
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        return $this->validate($validator);
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
     * @return Users[]|Users
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
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
        return 'users';
    }

}
