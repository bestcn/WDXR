<?php

namespace App\Entities;

use Phalcon\Validation;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;

class HuobullUser extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=40, nullable=false)
     */
    protected $user_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    protected $pwd;

    /**
     *
     * @var string
     * @Column(type="string", length=6, nullable=false)
     */
    protected $pwd_salt;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_pwd_set;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    protected $pay_pwd;

    /**
     *
     * @var string
     * @Column(type="string", length=6, nullable=false)
     */
    protected $pay_pwd_salt;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_pay_pwd_set;

    /**
     *
     * @var string
     * @Column(type="string", length=11, nullable=false)
     */
    protected $phone;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    protected $realname;

    /**
     *
     * @var integer
     * @Column(type="integer", length=2, nullable=false)
     */
    protected $idcard_type;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=false)
     */
    protected $idcard;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_realname_auth;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_realname_auth;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    protected $email;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_email_auth;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_email_auth;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $ips_acct_no;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $province_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $city_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $referer_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_login;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    protected $login_ip;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $login_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_effect;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_delete;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_update;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_create;

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
     * Method to set the value of field user_name
     *
     * @param string $user_name
     * @return $this
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;

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
     * Method to set the value of field pwd
     *
     * @param string $pwd
     * @return $this
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;

        return $this;
    }

    /**
     * Method to set the value of field pwd_salt
     *
     * @param string $pwd_salt
     * @return $this
     */
    public function setPwdSalt($pwd_salt)
    {
        $this->pwd_salt = $pwd_salt;

        return $this;
    }

    /**
     * Method to set the value of field time_pwd_set
     *
     * @param integer $time_pwd_set
     * @return $this
     */
    public function setTimePwdSet($time_pwd_set)
    {
        $this->time_pwd_set = $time_pwd_set;

        return $this;
    }

    /**
     * Method to set the value of field pay_pwd
     *
     * @param string $pay_pwd
     * @return $this
     */
    public function setPayPwd($pay_pwd)
    {
        $this->pay_pwd = $pay_pwd;

        return $this;
    }

    /**
     * Method to set the value of field pay_pwd_salt
     *
     * @param string $pay_pwd_salt
     * @return $this
     */
    public function setPayPwdSalt($pay_pwd_salt)
    {
        $this->pay_pwd_salt = $pay_pwd_salt;

        return $this;
    }

    /**
     * Method to set the value of field time_pay_pwd_set
     *
     * @param integer $time_pay_pwd_set
     * @return $this
     */
    public function setTimePayPwdSet($time_pay_pwd_set)
    {
        $this->time_pay_pwd_set = $time_pay_pwd_set;

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
     * Method to set the value of field realname
     *
     * @param string $realname
     * @return $this
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    /**
     * Method to set the value of field idcard_type
     *
     * @param integer $idcard_type
     * @return $this
     */
    public function setIdcardType($idcard_type)
    {
        $this->idcard_type = $idcard_type;

        return $this;
    }

    /**
     * Method to set the value of field idcard
     *
     * @param string $idcard
     * @return $this
     */
    public function setIdcard($idcard)
    {
        $this->idcard = $idcard;

        return $this;
    }

    /**
     * Method to set the value of field is_realname_auth
     *
     * @param integer $is_realname_auth
     * @return $this
     */
    public function setIsRealnameAuth($is_realname_auth)
    {
        $this->is_realname_auth = $is_realname_auth;

        return $this;
    }

    /**
     * Method to set the value of field time_realname_auth
     *
     * @param integer $time_realname_auth
     * @return $this
     */
    public function setTimeRealnameAuth($time_realname_auth)
    {
        $this->time_realname_auth = $time_realname_auth;

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
     * Method to set the value of field is_email_auth
     *
     * @param integer $is_email_auth
     * @return $this
     */
    public function setIsEmailAuth($is_email_auth)
    {
        $this->is_email_auth = $is_email_auth;

        return $this;
    }

    /**
     * Method to set the value of field time_email_auth
     *
     * @param integer $time_email_auth
     * @return $this
     */
    public function setTimeEmailAuth($time_email_auth)
    {
        $this->time_email_auth = $time_email_auth;

        return $this;
    }

    /**
     * Method to set the value of field ips_acct_no
     *
     * @param string $ips_acct_no
     * @return $this
     */
    public function setIpsAcctNo($ips_acct_no)
    {
        $this->ips_acct_no = $ips_acct_no;

        return $this;
    }

    /**
     * Method to set the value of field province_id
     *
     * @param integer $province_id
     * @return $this
     */
    public function setProvinceId($province_id)
    {
        $this->province_id = $province_id;

        return $this;
    }

    /**
     * Method to set the value of field city_id
     *
     * @param integer $city_id
     * @return $this
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;

        return $this;
    }

    /**
     * Method to set the value of field referer_id
     *
     * @param integer $referer_id
     * @return $this
     */
    public function setRefererId($referer_id)
    {
        $this->referer_id = $referer_id;

        return $this;
    }

    /**
     * Method to set the value of field time_login
     *
     * @param integer $time_login
     * @return $this
     */
    public function setTimeLogin($time_login)
    {
        $this->time_login = $time_login;

        return $this;
    }

    /**
     * Method to set the value of field login_ip
     *
     * @param string $login_ip
     * @return $this
     */
    public function setLoginIp($login_ip)
    {
        $this->login_ip = $login_ip;

        return $this;
    }

    /**
     * Method to set the value of field login_type
     *
     * @param integer $login_type
     * @return $this
     */
    public function setLoginType($login_type)
    {
        $this->login_type = $login_type;

        return $this;
    }

    /**
     * Method to set the value of field is_effect
     *
     * @param integer $is_effect
     * @return $this
     */
    public function setIsEffect($is_effect)
    {
        $this->is_effect = $is_effect;

        return $this;
    }

    /**
     * Method to set the value of field is_delete
     *
     * @param integer $is_delete
     * @return $this
     */
    public function setIsDelete($is_delete)
    {
        $this->is_delete = $is_delete;

        return $this;
    }

    /**
     * Method to set the value of field time_update
     *
     * @param integer $time_update
     * @return $this
     */
    public function setTimeUpdate($time_update)
    {
        $this->time_update = $time_update;

        return $this;
    }

    /**
     * Method to set the value of field time_create
     *
     * @param integer $time_create
     * @return $this
     */
    public function setTimeCreate($time_create)
    {
        $this->time_create = $time_create;

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
     * Returns the value of field user_name
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->user_name;
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
     * Returns the value of field pwd
     *
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * Returns the value of field pwd_salt
     *
     * @return string
     */
    public function getPwdSalt()
    {
        return $this->pwd_salt;
    }

    /**
     * Returns the value of field time_pwd_set
     *
     * @return integer
     */
    public function getTimePwdSet()
    {
        return $this->time_pwd_set;
    }

    /**
     * Returns the value of field pay_pwd
     *
     * @return string
     */
    public function getPayPwd()
    {
        return $this->pay_pwd;
    }

    /**
     * Returns the value of field pay_pwd_salt
     *
     * @return string
     */
    public function getPayPwdSalt()
    {
        return $this->pay_pwd_salt;
    }

    /**
     * Returns the value of field time_pay_pwd_set
     *
     * @return integer
     */
    public function getTimePayPwdSet()
    {
        return $this->time_pay_pwd_set;
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
     * Returns the value of field realname
     *
     * @return string
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Returns the value of field idcard_type
     *
     * @return integer
     */
    public function getIdcardType()
    {
        return $this->idcard_type;
    }

    /**
     * Returns the value of field idcard
     *
     * @return string
     */
    public function getIdcard()
    {
        return $this->idcard;
    }

    /**
     * Returns the value of field is_realname_auth
     *
     * @return integer
     */
    public function getIsRealnameAuth()
    {
        return $this->is_realname_auth;
    }

    /**
     * Returns the value of field time_realname_auth
     *
     * @return integer
     */
    public function getTimeRealnameAuth()
    {
        return $this->time_realname_auth;
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
     * Returns the value of field is_email_auth
     *
     * @return integer
     */
    public function getIsEmailAuth()
    {
        return $this->is_email_auth;
    }

    /**
     * Returns the value of field time_email_auth
     *
     * @return integer
     */
    public function getTimeEmailAuth()
    {
        return $this->time_email_auth;
    }

    /**
     * Returns the value of field ips_acct_no
     *
     * @return string
     */
    public function getIpsAcctNo()
    {
        return $this->ips_acct_no;
    }

    /**
     * Returns the value of field province_id
     *
     * @return integer
     */
    public function getProvinceId()
    {
        return $this->province_id;
    }

    /**
     * Returns the value of field city_id
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * Returns the value of field referer_id
     *
     * @return integer
     */
    public function getRefererId()
    {
        return $this->referer_id;
    }

    /**
     * Returns the value of field time_login
     *
     * @return integer
     */
    public function getTimeLogin()
    {
        return $this->time_login;
    }

    /**
     * Returns the value of field login_ip
     *
     * @return string
     */
    public function getLoginIp()
    {
        return $this->login_ip;
    }

    /**
     * Returns the value of field login_type
     *
     * @return integer
     */
    public function getLoginType()
    {
        return $this->login_type;
    }

    /**
     * Returns the value of field is_effect
     *
     * @return integer
     */
    public function getIsEffect()
    {
        return $this->is_effect;
    }

    /**
     * Returns the value of field is_delete
     *
     * @return integer
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }

    /**
     * Returns the value of field time_update
     *
     * @return integer
     */
    public function getTimeUpdate()
    {
        return $this->time_update;
    }

    /**
     * Returns the value of field time_create
     *
     * @return integer
     */
    public function getTimeCreate()
    {
        return $this->time_create;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
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
        return 'huobull_user';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUser[]|HuobullUser
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUser
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
