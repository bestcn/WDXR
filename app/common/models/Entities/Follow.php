<?php

namespace Wdxr\Models\Entities;

class Follow extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $device_id;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=true)
     */
    protected $follow;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=true)
     */
    protected $unfollow;

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
     * Method to set the value of field device_id
     *
     * @param integer $device_id
     * @return $this
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;

        return $this;
    }

    /**
     * Method to set the value of field follow
     *
     * @param string $follow
     * @return $this
     */
    public function setFollow($follow)
    {
        $this->follow = $follow;

        return $this;
    }

    /**
     * Method to set the value of field unfollow
     *
     * @param string $unfollow
     * @return $this
     */
    public function setUnfollow($unfollow)
    {
        $this->unfollow = $unfollow;

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
     * Returns the value of field device_id
     *
     * @return integer
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * Returns the value of field follow
     *
     * @return string
     */
    public function getFollow()
    {
        return $this->follow;
    }

    /**
     * Returns the value of field unfollow
     *
     * @return string
     */
    public function getUnfollow()
    {
        return $this->unfollow;
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
     * @return Follow[]|Follow
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Follow
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
        return 'follow';
    }

}
