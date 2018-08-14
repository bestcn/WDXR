<?php

namespace App\Entities;

class HuobullLog extends \Phalcon\Mvc\Model
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
    protected $time_log;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $log_admin;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $log_ip;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $log_status;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $module;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $action;

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
     * Method to set the value of field time_log
     *
     * @param integer $time_log
     * @return $this
     */
    public function setTimeLog($time_log)
    {
        $this->time_log = $time_log;

        return $this;
    }

    /**
     * Method to set the value of field log_admin
     *
     * @param integer $log_admin
     * @return $this
     */
    public function setLogAdmin($log_admin)
    {
        $this->log_admin = $log_admin;

        return $this;
    }

    /**
     * Method to set the value of field log_ip
     *
     * @param string $log_ip
     * @return $this
     */
    public function setLogIp($log_ip)
    {
        $this->log_ip = $log_ip;

        return $this;
    }

    /**
     * Method to set the value of field log_status
     *
     * @param integer $log_status
     * @return $this
     */
    public function setLogStatus($log_status)
    {
        $this->log_status = $log_status;

        return $this;
    }

    /**
     * Method to set the value of field module
     *
     * @param string $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Method to set the value of field action
     *
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

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
     * Returns the value of field time_log
     *
     * @return integer
     */
    public function getTimeLog()
    {
        return $this->time_log;
    }

    /**
     * Returns the value of field log_admin
     *
     * @return integer
     */
    public function getLogAdmin()
    {
        return $this->log_admin;
    }

    /**
     * Returns the value of field log_ip
     *
     * @return string
     */
    public function getLogIp()
    {
        return $this->log_ip;
    }

    /**
     * Returns the value of field log_status
     *
     * @return integer
     */
    public function getLogStatus()
    {
        return $this->log_status;
    }

    /**
     * Returns the value of field module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Returns the value of field action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
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
        return 'huobull_log';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullLog[]|HuobullLog
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
