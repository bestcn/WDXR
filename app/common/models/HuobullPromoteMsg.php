<?php

namespace App\Entities;

class HuobullPromoteMsg extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $content;

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
    protected $send_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $deal_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $send_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $send_type_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $send_define_data;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_html;

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
     * Method to set the value of field send_status
     *
     * @param integer $send_status
     * @return $this
     */
    public function setSendStatus($send_status)
    {
        $this->send_status = $send_status;

        return $this;
    }

    /**
     * Method to set the value of field deal_id
     *
     * @param integer $deal_id
     * @return $this
     */
    public function setDealId($deal_id)
    {
        $this->deal_id = $deal_id;

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
     * Method to set the value of field send_type_id
     *
     * @param integer $send_type_id
     * @return $this
     */
    public function setSendTypeId($send_type_id)
    {
        $this->send_type_id = $send_type_id;

        return $this;
    }

    /**
     * Method to set the value of field send_define_data
     *
     * @param string $send_define_data
     * @return $this
     */
    public function setSendDefineData($send_define_data)
    {
        $this->send_define_data = $send_define_data;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Returns the value of field time_send
     *
     * @return integer
     */
    public function getTimeSend()
    {
        return $this->time_send;
    }

    /**
     * Returns the value of field send_status
     *
     * @return integer
     */
    public function getSendStatus()
    {
        return $this->send_status;
    }

    /**
     * Returns the value of field deal_id
     *
     * @return integer
     */
    public function getDealId()
    {
        return $this->deal_id;
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
     * Returns the value of field send_type_id
     *
     * @return integer
     */
    public function getSendTypeId()
    {
        return $this->send_type_id;
    }

    /**
     * Returns the value of field send_define_data
     *
     * @return string
     */
    public function getSendDefineData()
    {
        return $this->send_define_data;
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
        return 'huobull_promote_msg';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullPromoteMsg[]|HuobullPromoteMsg
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullPromoteMsg
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
