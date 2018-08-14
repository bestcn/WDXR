<?php

namespace Wdxr\Models\Entities;

class SmsLog extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=128, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $result;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $error;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $sid;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=false)
     */
    protected $phone;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $fee;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $ext;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $time;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $template_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $params;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $user_receive_time;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $nationcode;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $mobile;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $report_status;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=true)
     */
    protected $errmsg;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * Method to set the value of field id
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field result
     *
     * @param string $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Method to set the value of field error
     *
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Method to set the value of field sid
     *
     * @param string $sid
     * @return $this
     */
    public function setSid($sid)
    {
        $this->sid = $sid;

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
     * Method to set the value of field ext
     *
     * @param string $ext
     * @return $this
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

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
     * Method to set the value of field template_id
     *
     * @param string $template_id
     * @return $this
     */
    public function setTemplateId($template_id)
    {
        $this->template_id = $template_id;

        return $this;
    }

    /**
     * Method to set the value of field params
     *
     * @param string $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Method to set the value of field user_receive_time
     *
     * @param string $user_receive_time
     * @return $this
     */
    public function setUserReceiveTime($user_receive_time)
    {
        $this->user_receive_time = $user_receive_time;

        return $this;
    }

    /**
     * Method to set the value of field nationcode
     *
     * @param string $nationcode
     * @return $this
     */
    public function setNationcode($nationcode)
    {
        $this->nationcode = $nationcode;

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
     * Method to set the value of field report_status
     *
     * @param string $report_status
     * @return $this
     */
    public function setReportStatus($report_status)
    {
        $this->report_status = $report_status;

        return $this;
    }

    /**
     * Method to set the value of field errmsg
     *
     * @param string $errmsg
     * @return $this
     */
    public function setErrmsg($errmsg)
    {
        $this->errmsg = $errmsg;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Returns the value of field error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Returns the value of field sid
     *
     * @return string
     */
    public function getSid()
    {
        return $this->sid;
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
     * Returns the value of field fee
     *
     * @return integer
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Returns the value of field ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
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
     * Returns the value of field template_id
     *
     * @return string
     */
    public function getTemplateId()
    {
        return $this->template_id;
    }

    /**
     * Returns the value of field params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns the value of field user_receive_time
     *
     * @return string
     */
    public function getUserReceiveTime()
    {
        return $this->user_receive_time;
    }

    /**
     * Returns the value of field nationcode
     *
     * @return string
     */
    public function getNationcode()
    {
        return $this->nationcode;
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
     * Returns the value of field report_status
     *
     * @return string
     */
    public function getReportStatus()
    {
        return $this->report_status;
    }

    /**
     * Returns the value of field errmsg
     *
     * @return string
     */
    public function getErrmsg()
    {
        return $this->errmsg;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @return SmsLog[]|SmsLog
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SmsLog
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
        return 'sms_log';
    }

}
