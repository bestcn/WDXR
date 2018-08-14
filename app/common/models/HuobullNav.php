<?php

namespace App\Entities;

class HuobullNav extends \Phalcon\Mvc\Model
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
    protected $name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $url;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $blank;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $sort;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_effect;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $u_module;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $u_action;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $u_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $u_param;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_shop;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $app_index;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $pid;

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
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Method to set the value of field blank
     *
     * @param integer $blank
     * @return $this
     */
    public function setBlank($blank)
    {
        $this->blank = $blank;

        return $this;
    }

    /**
     * Method to set the value of field sort
     *
     * @param integer $sort
     * @return $this
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

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
     * Method to set the value of field u_module
     *
     * @param string $u_module
     * @return $this
     */
    public function setUModule($u_module)
    {
        $this->u_module = $u_module;

        return $this;
    }

    /**
     * Method to set the value of field u_action
     *
     * @param string $u_action
     * @return $this
     */
    public function setUAction($u_action)
    {
        $this->u_action = $u_action;

        return $this;
    }

    /**
     * Method to set the value of field u_id
     *
     * @param integer $u_id
     * @return $this
     */
    public function setUId($u_id)
    {
        $this->u_id = $u_id;

        return $this;
    }

    /**
     * Method to set the value of field u_param
     *
     * @param string $u_param
     * @return $this
     */
    public function setUParam($u_param)
    {
        $this->u_param = $u_param;

        return $this;
    }

    /**
     * Method to set the value of field is_shop
     *
     * @param integer $is_shop
     * @return $this
     */
    public function setIsShop($is_shop)
    {
        $this->is_shop = $is_shop;

        return $this;
    }

    /**
     * Method to set the value of field app_index
     *
     * @param string $app_index
     * @return $this
     */
    public function setAppIndex($app_index)
    {
        $this->app_index = $app_index;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the value of field blank
     *
     * @return integer
     */
    public function getBlank()
    {
        return $this->blank;
    }

    /**
     * Returns the value of field sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
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
     * Returns the value of field u_module
     *
     * @return string
     */
    public function getUModule()
    {
        return $this->u_module;
    }

    /**
     * Returns the value of field u_action
     *
     * @return string
     */
    public function getUAction()
    {
        return $this->u_action;
    }

    /**
     * Returns the value of field u_id
     *
     * @return integer
     */
    public function getUId()
    {
        return $this->u_id;
    }

    /**
     * Returns the value of field u_param
     *
     * @return string
     */
    public function getUParam()
    {
        return $this->u_param;
    }

    /**
     * Returns the value of field is_shop
     *
     * @return integer
     */
    public function getIsShop()
    {
        return $this->is_shop;
    }

    /**
     * Returns the value of field app_index
     *
     * @return string
     */
    public function getAppIndex()
    {
        return $this->app_index;
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
        return 'huobull_nav';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullNav[]|HuobullNav
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullNav
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
