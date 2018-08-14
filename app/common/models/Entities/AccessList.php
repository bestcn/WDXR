<?php

namespace Wdxr\Models\Entities;

class AccessList extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=32, nullable=false)
     */
    protected $roles_name;

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=32, nullable=false)
     */
    protected $resources_name;

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=32, nullable=false)
     */
    protected $access_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    protected $allowed;

    /**
     * Method to set the value of field roles_name
     *
     * @param string $roles_name
     * @return $this
     */
    public function setRolesName($roles_name)
    {
        $this->roles_name = $roles_name;

        return $this;
    }

    /**
     * Method to set the value of field resources_name
     *
     * @param string $resources_name
     * @return $this
     */
    public function setResourcesName($resources_name)
    {
        $this->resources_name = $resources_name;

        return $this;
    }

    /**
     * Method to set the value of field access_name
     *
     * @param string $access_name
     * @return $this
     */
    public function setAccessName($access_name)
    {
        $this->access_name = $access_name;

        return $this;
    }

    /**
     * Method to set the value of field allowed
     *
     * @param integer $allowed
     * @return $this
     */
    public function setAllowed($allowed)
    {
        $this->allowed = $allowed;

        return $this;
    }

    /**
     * Returns the value of field roles_name
     *
     * @return string
     */
    public function getRolesName()
    {
        return $this->roles_name;
    }

    /**
     * Returns the value of field resources_name
     *
     * @return string
     */
    public function getResourcesName()
    {
        return $this->resources_name;
    }

    /**
     * Returns the value of field access_name
     *
     * @return string
     */
    public function getAccessName()
    {
        return $this->access_name;
    }

    /**
     * Returns the value of field allowed
     *
     * @return integer
     */
    public function getAllowed()
    {
        return $this->allowed;
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
     * @return AccessList[]|AccessList
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AccessList
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
        return 'access_list';
    }

}
