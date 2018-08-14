<?php

namespace App\Entities;

class HuobullUserLog extends \Phalcon\Mvc\Model
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
    protected $log_info;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $log_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $log_admin_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $log_user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $score;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $point;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $quota;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $lock_money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $user_id;

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
     * Method to set the value of field log_info
     *
     * @param string $log_info
     * @return $this
     */
    public function setLogInfo($log_info)
    {
        $this->log_info = $log_info;

        return $this;
    }

    /**
     * Method to set the value of field log_time
     *
     * @param integer $log_time
     * @return $this
     */
    public function setLogTime($log_time)
    {
        $this->log_time = $log_time;

        return $this;
    }

    /**
     * Method to set the value of field log_admin_id
     *
     * @param integer $log_admin_id
     * @return $this
     */
    public function setLogAdminId($log_admin_id)
    {
        $this->log_admin_id = $log_admin_id;

        return $this;
    }

    /**
     * Method to set the value of field log_user_id
     *
     * @param integer $log_user_id
     * @return $this
     */
    public function setLogUserId($log_user_id)
    {
        $this->log_user_id = $log_user_id;

        return $this;
    }

    /**
     * Method to set the value of field money
     *
     * @param integer $money
     * @return $this
     */
    public function setMoney($money)
    {
        $this->money = $money;

        return $this;
    }

    /**
     * Method to set the value of field score
     *
     * @param integer $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Method to set the value of field point
     *
     * @param integer $point
     * @return $this
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Method to set the value of field quota
     *
     * @param integer $quota
     * @return $this
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Method to set the value of field lock_money
     *
     * @param integer $lock_money
     * @return $this
     */
    public function setLockMoney($lock_money)
    {
        $this->lock_money = $lock_money;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

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
     * Returns the value of field log_info
     *
     * @return string
     */
    public function getLogInfo()
    {
        return $this->log_info;
    }

    /**
     * Returns the value of field log_time
     *
     * @return integer
     */
    public function getLogTime()
    {
        return $this->log_time;
    }

    /**
     * Returns the value of field log_admin_id
     *
     * @return integer
     */
    public function getLogAdminId()
    {
        return $this->log_admin_id;
    }

    /**
     * Returns the value of field log_user_id
     *
     * @return integer
     */
    public function getLogUserId()
    {
        return $this->log_user_id;
    }

    /**
     * Returns the value of field money
     *
     * @return integer
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Returns the value of field score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Returns the value of field point
     *
     * @return integer
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Returns the value of field quota
     *
     * @return integer
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Returns the value of field lock_money
     *
     * @return integer
     */
    public function getLockMoney()
    {
        return $this->lock_money;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
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
        return 'huobull_user_log';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserLog[]|HuobullUserLog
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullUserLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
