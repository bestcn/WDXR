<?php

namespace Wdxr\Models\Entities;

class AdminLock extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $admin_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $lock_time;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $remaek;

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
     * Method to set the value of field lock_time
     *
     * @param integer $lock_time
     * @return $this
     */
    public function setLockTime($lock_time)
    {
        $this->lock_time = $lock_time;

        return $this;
    }

    /**
     * Method to set the value of field remaek
     *
     * @param string $remaek
     * @return $this
     */
    public function setRemaek($remaek)
    {
        $this->remaek = $remaek;

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
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * Returns the value of field lock_time
     *
     * @return integer
     */
    public function getLockTime()
    {
        return $this->lock_time;
    }

    /**
     * Returns the value of field remaek
     *
     * @return string
     */
    public function getRemaek()
    {
        return $this->remaek;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'admin_lock';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminLock[]|AdminLock
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminLock
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
