<?php

namespace Wdxr\Models\Entities;

class Category extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $code;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $top;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $first;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $second;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $third;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $image;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $alias;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     * Method to set the value of field code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Method to set the value of field top
     *
     * @param string $top
     * @return $this
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Method to set the value of field first
     *
     * @param string $first
     * @return $this
     */
    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * Method to set the value of field second
     *
     * @param string $second
     * @return $this
     */
    public function setSecond($second)
    {
        $this->second = $second;

        return $this;
    }

    /**
     * Method to set the value of field third
     *
     * @param string $third
     * @return $this
     */
    public function setThird($third)
    {
        $this->third = $third;

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
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Method to set the value of field alias
     *
     * @param string $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

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
     * Returns the value of field code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the value of field top
     *
     * @return string
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Returns the value of field first
     *
     * @return string
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * Returns the value of field second
     *
     * @return string
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * Returns the value of field third
     *
     * @return string
     */
    public function getThird()
    {
        return $this->third;
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
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Returns the value of field alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
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
     * @return Category[]|Category
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category
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
        return 'category';
    }

}
