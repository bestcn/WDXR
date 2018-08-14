<?php

namespace App\Entities;

class HuobullArticle extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=255, nullable=false)
     */
    protected $icon;

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
    protected $cate_id;

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
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $add_admin_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $is_effect;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $rel_url;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $update_admin_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $is_delete;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $click_count;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $sort;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $seo_title;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $seo_keyword;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $seo_description;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $uname;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $sub_title;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $brief;

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
     * Method to set the value of field icon
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

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
     * Method to set the value of field cate_id
     *
     * @param integer $cate_id
     * @return $this
     */
    public function setCateId($cate_id)
    {
        $this->cate_id = $cate_id;

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
     * Method to set the value of field add_admin_id
     *
     * @param integer $add_admin_id
     * @return $this
     */
    public function setAddAdminId($add_admin_id)
    {
        $this->add_admin_id = $add_admin_id;

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
     * Method to set the value of field rel_url
     *
     * @param string $rel_url
     * @return $this
     */
    public function setRelUrl($rel_url)
    {
        $this->rel_url = $rel_url;

        return $this;
    }

    /**
     * Method to set the value of field update_admin_id
     *
     * @param integer $update_admin_id
     * @return $this
     */
    public function setUpdateAdminId($update_admin_id)
    {
        $this->update_admin_id = $update_admin_id;

        return $this;
    }

    /**
     * Method to set the value of field is_delete
     *
     * @param integer $is_delete
     * @return $this
     */
    public function setIsDelete($is_delete)
    {
        $this->is_delete = $is_delete;

        return $this;
    }

    /**
     * Method to set the value of field click_count
     *
     * @param integer $click_count
     * @return $this
     */
    public function setClickCount($click_count)
    {
        $this->click_count = $click_count;

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
     * Method to set the value of field seo_title
     *
     * @param string $seo_title
     * @return $this
     */
    public function setSeoTitle($seo_title)
    {
        $this->seo_title = $seo_title;

        return $this;
    }

    /**
     * Method to set the value of field seo_keyword
     *
     * @param string $seo_keyword
     * @return $this
     */
    public function setSeoKeyword($seo_keyword)
    {
        $this->seo_keyword = $seo_keyword;

        return $this;
    }

    /**
     * Method to set the value of field seo_description
     *
     * @param string $seo_description
     * @return $this
     */
    public function setSeoDescription($seo_description)
    {
        $this->seo_description = $seo_description;

        return $this;
    }

    /**
     * Method to set the value of field uname
     *
     * @param string $uname
     * @return $this
     */
    public function setUname($uname)
    {
        $this->uname = $uname;

        return $this;
    }

    /**
     * Method to set the value of field sub_title
     *
     * @param string $sub_title
     * @return $this
     */
    public function setSubTitle($sub_title)
    {
        $this->sub_title = $sub_title;

        return $this;
    }

    /**
     * Method to set the value of field brief
     *
     * @param string $brief
     * @return $this
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;

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
     * Returns the value of field icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
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
     * Returns the value of field cate_id
     *
     * @return integer
     */
    public function getCateId()
    {
        return $this->cate_id;
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
     * Returns the value of field add_admin_id
     *
     * @return integer
     */
    public function getAddAdminId()
    {
        return $this->add_admin_id;
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
     * Returns the value of field rel_url
     *
     * @return string
     */
    public function getRelUrl()
    {
        return $this->rel_url;
    }

    /**
     * Returns the value of field update_admin_id
     *
     * @return integer
     */
    public function getUpdateAdminId()
    {
        return $this->update_admin_id;
    }

    /**
     * Returns the value of field is_delete
     *
     * @return integer
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }

    /**
     * Returns the value of field click_count
     *
     * @return integer
     */
    public function getClickCount()
    {
        return $this->click_count;
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
     * Returns the value of field seo_title
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seo_title;
    }

    /**
     * Returns the value of field seo_keyword
     *
     * @return string
     */
    public function getSeoKeyword()
    {
        return $this->seo_keyword;
    }

    /**
     * Returns the value of field seo_description
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seo_description;
    }

    /**
     * Returns the value of field uname
     *
     * @return string
     */
    public function getUname()
    {
        return $this->uname;
    }

    /**
     * Returns the value of field sub_title
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->sub_title;
    }

    /**
     * Returns the value of field brief
     *
     * @return string
     */
    public function getBrief()
    {
        return $this->brief;
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
        return 'huobull_article';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullArticle[]|HuobullArticle
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullArticle
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
