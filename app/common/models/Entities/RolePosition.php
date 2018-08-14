<?php

namespace Wdxr\Models\Entities;

class RolePosition extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=128, nullable=false)
     */
    protected $role_name;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $position_id;

    /**
     * Method to set the value of field role_name
     *
     * @param string $role_name
     * @return $this
     */
    public function setRoleName($role_name)
    {
        $this->role_name = $role_name;

        return $this;
    }

    /**
     * Method to set the value of field position_id
     *
     * @param integer $position_id
     * @return $this
     */
    public function setPositionId($position_id)
    {
        $this->position_id = $position_id;

        return $this;
    }

    /**
     * Returns the value of field role_name
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->role_name;
    }

    /**
     * Returns the value of field position_id
     *
     * @return integer
     */
    public function getPositionId()
    {
        return $this->position_id;
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
     * @return RolePosition[]|RolePosition
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RolePosition
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
        return 'role_position';
    }

}
