<?php

namespace Wdxr\Models\Entities;

class BranchsLevels extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=255, nullable=true)
     */
    protected $level_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=true)
     */
    protected $level_status;

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
     * Method to set the value of field level_name
     *
     * @param string $level_name
     * @return $this
     */
    public function setLevelName($level_name)
    {
        $this->level_name = $level_name;

        return $this;
    }

    /**
     * Method to set the value of field level_status
     *
     * @param integer $level_status
     * @return $this
     */
    public function setLevelStatus($level_status)
    {
        $this->level_status = $level_status;

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
     * Returns the value of field level_name
     *
     * @return string
     */
    public function getLevelName()
    {
        return $this->level_name;
    }

    /**
     * Returns the value of field level_status
     *
     * @return integer
     */
    public function getLevelStatus()
    {
        return $this->level_status;
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
     * @return Levels[]|Levels
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Levels
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
        return 'branchs_levels';
    }

}
