<?php

namespace Wdxr\Models\Entities;

class ResourcesAccesses extends \Phalcon\Mvc\Model
{

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
     * @return ResourcesAccesses[]|ResourcesAccesses
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResourcesAccesses
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
        return 'resources_accesses';
    }

}
