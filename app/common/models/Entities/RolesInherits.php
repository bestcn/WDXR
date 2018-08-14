<?php

namespace Wdxr\Models\Entities;

class RolesInherits extends \Phalcon\Mvc\Model
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
    protected $roles_inherit;

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
     * Method to set the value of field roles_inherit
     *
     * @param string $roles_inherit
     * @return $this
     */
    public function setRolesInherit($roles_inherit)
    {
        $this->roles_inherit = $roles_inherit;

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
     * Returns the value of field roles_inherit
     *
     * @return string
     */
    public function getRolesInherit()
    {
        return $this->roles_inherit;
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
     * @return RolesInherits[]|RolesInherits
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RolesInherits
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
        return 'roles_inherits';
    }

}
