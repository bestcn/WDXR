<?php

namespace App\Entities;

class HuobullPromoteMsgList extends \Phalcon\Mvc\Model
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
    protected $dest;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $send_type;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $content;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_send;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_send;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_create;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $result;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_success;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_html;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $msg_id;

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
     * Method to set the value of field dest
     *
     * @param string $dest
     * @return $this
     */
    public function setDest($dest)
    {
        $this->dest = $dest;

        return $this;
    }

    /**
     * Method to set the value of field send_type
     *
     * @param integer $send_type
     * @return $this
     */
    public function setSendType($send_type)
    {
        $this->send_type = $send_type;

        return $this;
    }

    /**
     * Method to set the value of field content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field time_send
     *
     * @param integer $time_send
     * @return $this
     */
    public function setTimeSend($time_send)
    {
        $this->time_send = $time_send;

        return $this;
    }

    /**
     * Method to set the value of field is_send
     *
     * @param integer $is_send
     * @return $this
     */
    public function setIsSend($is_send)
    {
        $this->is_send = $is_send;

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
     * Method to set the value of field is_success
     *
     * @param integer $is_success
     * @return $this
     */
    public function setIsSuccess($is_success)
    {
        $this->is_success = $is_success;

        return $this;
    }

    /**
     * Method to set the value of field is_html
     *
     * @param integer $is_html
     * @return $this
     */
    public function setIsHtml($is_html)
    {
        $this->is_html = $is_html;

        return $this;
    }

    /**
     * Method to set the value of field msg_id
     *
     * @param integer $msg_id
     * @return $this
     */
    public function setMsgId($msg_id)
    {
        $this->msg_id = $msg_id;

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
     * Returns the value of field dest
     *
     * @return string
     */
    public function getDest()
    {
        return $this->dest;
    }

    /**
     * Returns the value of field send_type
     *
     * @return integer
     */
    public function getSendType()
    {
        return $this->send_type;
    }

    /**
     * Returns the value of field content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field time_send
     *
     * @return integer
     */
    public function getTimeSend()
    {
        return $this->time_send;
    }

    /**
     * Returns the value of field is_send
     *
     * @return integer
     */
    public function getIsSend()
    {
        return $this->is_send;
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
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
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
     * Returns the value of field is_success
     *
     * @return integer
     */
    public function getIsSuccess()
    {
        return $this->is_success;
    }

    /**
     * Returns the value of field is_html
     *
     * @return integer
     */
    public function getIsHtml()
    {
        return $this->is_html;
    }

    /**
     * Returns the value of field msg_id
     *
     * @return integer
     */
    public function getMsgId()
    {
        return $this->msg_id;
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
        return 'huobull_promote_msg_list';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullPromoteMsgList[]|HuobullPromoteMsgList
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullPromoteMsgList
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
