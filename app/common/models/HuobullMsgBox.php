<?php

namespace App\Entities;

class HuobullMsgBox extends \Phalcon\Mvc\Model
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
    protected $from_user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $to_user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time_create;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_read;

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
    protected $system_msg_id;

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
    protected $group_key;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_notice;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $fav_id;

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
     * Method to set the value of field from_user_id
     *
     * @param integer $from_user_id
     * @return $this
     */
    public function setFromUserId($from_user_id)
    {
        $this->from_user_id = $from_user_id;

        return $this;
    }

    /**
     * Method to set the value of field to_user_id
     *
     * @param integer $to_user_id
     * @return $this
     */
    public function setToUserId($to_user_id)
    {
        $this->to_user_id = $to_user_id;

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
     * Method to set the value of field is_read
     *
     * @param integer $is_read
     * @return $this
     */
    public function setIsRead($is_read)
    {
        $this->is_read = $is_read;

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
     * Method to set the value of field system_msg_id
     *
     * @param integer $system_msg_id
     * @return $this
     */
    public function setSystemMsgId($system_msg_id)
    {
        $this->system_msg_id = $system_msg_id;

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
     * Method to set the value of field group_key
     *
     * @param string $group_key
     * @return $this
     */
    public function setGroupKey($group_key)
    {
        $this->group_key = $group_key;

        return $this;
    }

    /**
     * Method to set the value of field is_notice
     *
     * @param integer $is_notice
     * @return $this
     */
    public function setIsNotice($is_notice)
    {
        $this->is_notice = $is_notice;

        return $this;
    }

    /**
     * Method to set the value of field fav_id
     *
     * @param integer $fav_id
     * @return $this
     */
    public function setFavId($fav_id)
    {
        $this->fav_id = $fav_id;

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
     * Returns the value of field from_user_id
     *
     * @return integer
     */
    public function getFromUserId()
    {
        return $this->from_user_id;
    }

    /**
     * Returns the value of field to_user_id
     *
     * @return integer
     */
    public function getToUserId()
    {
        return $this->to_user_id;
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
     * Returns the value of field is_read
     *
     * @return integer
     */
    public function getIsRead()
    {
        return $this->is_read;
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
     * Returns the value of field system_msg_id
     *
     * @return integer
     */
    public function getSystemMsgId()
    {
        return $this->system_msg_id;
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
     * Returns the value of field group_key
     *
     * @return string
     */
    public function getGroupKey()
    {
        return $this->group_key;
    }

    /**
     * Returns the value of field is_notice
     *
     * @return integer
     */
    public function getIsNotice()
    {
        return $this->is_notice;
    }

    /**
     * Returns the value of field fav_id
     *
     * @return integer
     */
    public function getFavId()
    {
        return $this->fav_id;
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
        return 'huobull_msg_box';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullMsgBox[]|HuobullMsgBox
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullMsgBox
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
