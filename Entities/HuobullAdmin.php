<?php

namespace App\Entities;

class HuobullAdmin extends \Phalcon\Mvc\Model
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
    protected $adm_name;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    protected $adm_password;

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
    protected $role_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_login;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $login_ip;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $work_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_department;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $pid;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $referrals_rate;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $referrals_count;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $referrals_money;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $role_ids;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $real_name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $mobile;

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
     * Method to set the value of field adm_name
     *
     * @param string $adm_name
     * @return $this
     */
    public function setAdmName($adm_name)
    {
        $this->adm_name = $adm_name;

        return $this;
    }

    /**
     * Method to set the value of field adm_password
     *
     * @param string $adm_password
     * @return $this
     */
    public function setAdmPassword($adm_password)
    {
        $this->adm_password = $adm_password;

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
     * Method to set the value of field role_id
     *
     * @param integer $role_id
     * @return $this
     */
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;

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
     * Method to set the value of field work_id
     *
     * @param string $work_id
     * @return $this
     */
    public function setWorkId($work_id)
    {
        $this->work_id = $work_id;

        return $this;
    }

    /**
     * Method to set the value of field is_department
     *
     * @param integer $is_department
     * @return $this
     */
    public function setIsDepartment($is_department)
    {
        $this->is_department = $is_department;

        return $this;
    }

    /**
     * Method to set the value of field pid
     *
     * @param integer $pid
     * @return $this
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Method to set the value of field referrals_rate
     *
     * @param string $referrals_rate
     * @return $this
     */
    public function setReferralsRate($referrals_rate)
    {
        $this->referrals_rate = $referrals_rate;

        return $this;
    }

    /**
     * Method to set the value of field referrals_count
     *
     * @param integer $referrals_count
     * @return $this
     */
    public function setReferralsCount($referrals_count)
    {
        $this->referrals_count = $referrals_count;

        return $this;
    }

    /**
     * Method to set the value of field referrals_money
     *
     * @param integer $referrals_money
     * @return $this
     */
    public function setReferralsMoney($referrals_money)
    {
        $this->referrals_money = $referrals_money;

        return $this;
    }

    /**
     * Method to set the value of field role_ids
     *
     * @param string $role_ids
     * @return $this
     */
    public function setRoleIds($role_ids)
    {
        $this->role_ids = $role_ids;

        return $this;
    }

    /**
     * Method to set the value of field real_name
     *
     * @param string $real_name
     * @return $this
     */
    public function setRealName($real_name)
    {
        $this->real_name = $real_name;

        return $this;
    }

    /**
     * Method to set the value of field mobile
     *
     * @param string $mobile
     * @return $this
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

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
     * Returns the value of field adm_name
     *
     * @return string
     */
    public function getAdmName()
    {
        return $this->adm_name;
    }

    /**
     * Returns the value of field adm_password
     *
     * @return string
     */
    public function getAdmPassword()
    {
        return $this->adm_password;
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
     * Returns the value of field role_id
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->role_id;
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
     * Returns the value of field work_id
     *
     * @return string
     */
    public function getWorkId()
    {
        return $this->work_id;
    }

    /**
     * Returns the value of field is_department
     *
     * @return integer
     */
    public function getIsDepartment()
    {
        return $this->is_department;
    }

    /**
     * Returns the value of field pid
     *
     * @return integer
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Returns the value of field referrals_rate
     *
     * @return string
     */
    public function getReferralsRate()
    {
        return $this->referrals_rate;
    }

    /**
     * Returns the value of field referrals_count
     *
     * @return integer
     */
    public function getReferralsCount()
    {
        return $this->referrals_count;
    }

    /**
     * Returns the value of field referrals_money
     *
     * @return integer
     */
    public function getReferralsMoney()
    {
        return $this->referrals_money;
    }

    /**
     * Returns the value of field role_ids
     *
     * @return string
     */
    public function getRoleIds()
    {
        return $this->role_ids;
    }

    /**
     * Returns the value of field real_name
     *
     * @return string
     */
    public function getRealName()
    {
        return $this->real_name;
    }

    /**
     * Returns the value of field mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
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
        return 'huobull_admin';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullAdmin[]|HuobullAdmin
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullAdmin
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
