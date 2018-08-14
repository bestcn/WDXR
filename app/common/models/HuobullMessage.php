<?php

namespace App\Entities;

class HuobullMessage extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=2000, nullable=false)
     */
    protected $content;

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
    protected $time_update;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $admin_reply;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $admin_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $rel_table;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $rel_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $pid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_effect;

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
     * Method to set the value of field admin_reply
     *
     * @param string $admin_reply
     * @return $this
     */
    public function setAdminReply($admin_reply)
    {
        $this->admin_reply = $admin_reply;

        return $this;
    }

    /**
     * Method to set the value of field admin_id
     *
     * @param integer $admin_id
     * @return $this
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;

        return $this;
    }

    /**
     * Method to set the value of field rel_table
     *
     * @param string $rel_table
     * @return $this
     */
    public function setRelTable($rel_table)
    {
        $this->rel_table = $rel_table;

        return $this;
    }

    /**
     * Method to set the value of field rel_id
     *
     * @param integer $rel_id
     * @return $this
     */
    public function setRelId($rel_id)
    {
        $this->rel_id = $rel_id;

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
     * Returns the value of field time_create
     *
     * @return integer
     */
    public function getTimeCreate()
    {
        return $this->time_create;
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
     * Returns the value of field admin_reply
     *
     * @return string
     */
    public function getAdminReply()
    {
        return $this->admin_reply;
    }

    /**
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * Returns the value of field rel_table
     *
     * @return string
     */
    public function getRelTable()
    {
        return $this->rel_table;
    }

    /**
     * Returns the value of field rel_id
     *
     * @return integer
     */
    public function getRelId()
    {
        return $this->rel_id;
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
     * Returns the value of field pid
     *
     * @return integer
     */
    public function getPid()
    {
        return $this->pid;
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
        return 'huobull_message';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullMessage[]|HuobullMessage
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullMessage
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
