<?php

namespace Wdxr\Models\Entities;

class Companys extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=200, nullable=false)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $auditing;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $info_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $recommend_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $device_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $admin_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $manager_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $partner_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $account_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $category;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $add_people;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $is_bad;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $is_rask;

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
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field auditing
     *
     * @param integer $auditing
     * @return $this
     */
    public function setAuditing($auditing)
    {
        $this->auditing = $auditing;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field info_id
     *
     * @param integer $info_id
     * @return $this
     */
    public function setInfoId($info_id)
    {
        $this->info_id = $info_id;

        return $this;
    }

    /**
     * Method to set the value of field recommend_id
     *
     * @param integer $recommend_id
     * @return $this
     */
    public function setRecommendId($recommend_id)
    {
        $this->recommend_id = $recommend_id;

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
     * Method to set the value of field time
     *
     * @param string $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Method to set the value of field manager_id
     *
     * @param integer $manager_id
     * @return $this
     */
    public function setManagerId($manager_id)
    {
        $this->manager_id = $manager_id;

        return $this;
    }

    /**
     * Method to set the value of field partner_id
     *
     * @param integer $partner_id
     * @return $this
     */
    public function setPartnerId($partner_id)
    {
        $this->partner_id = $partner_id;

        return $this;
    }

    /**
     * Method to set the value of field account_id
     *
     * @param integer $account_id
     * @return $this
     */
    public function setAccountId($account_id)
    {
        $this->account_id = $account_id;

        return $this;
    }

    /**
     * Method to set the value of field category
     *
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Method to set the value of field add_people
     *
     * @param integer $add_people
     * @return $this
     */
    public function setAddPeople($add_people)
    {
        $this->add_people = $add_people;

        return $this;
    }

    /**
     * Method to set the value of field is_bad
     *
     * @param integer $is_bad
     * @return $this
     */
    public function setIsBad($is_bad)
    {
        $this->is_bad = $is_bad;

        return $this;
    }

    /**
     * Method to set the value of field is_rask
     *
     * @param integer $is_rask
     * @return $this
     */
    public function setIsRask($is_rask)
    {
        $this->is_rask = $is_rask;

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
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field auditing
     *
     * @return integer
     */
    public function getAuditing()
    {
        return $this->auditing;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field info_id
     *
     * @return integer
     */
    public function getInfoId()
    {
        return $this->info_id;
    }

    /**
     * Returns the value of field recommend_id
     *
     * @return integer
     */
    public function getRecommendId()
    {
        return $this->recommend_id;
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
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * Returns the value of field time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Returns the value of field manager_id
     *
     * @return integer
     */
    public function getManagerId()
    {
        return $this->manager_id;
    }

    /**
     * Returns the value of field partner_id
     *
     * @return integer
     */
    public function getPartnerId()
    {
        return $this->partner_id;
    }

    /**
     * Returns the value of field account_id
     *
     * @return integer
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * Returns the value of field category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns the value of field add_people
     *
     * @return integer
     */
    public function getAddPeople()
    {
        return $this->add_people;
    }

    /**
     * Returns the value of field is_bad
     *
     * @return integer
     */
    public function getIsBad()
    {
        return $this->is_bad;
    }

    /**
     * Returns the value of field is_rask
     *
     * @return integer
     */
    public function getIsRask()
    {
        return $this->is_rask;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
        $this->hasOne('user_id', __NAMESPACE__ . '\Users', 'id', [
            'alias' => 'users',
        ]);
        $this->hasOne('info_id', __NAMESPACE__ . '\CompanyInfo', 'id', [
            'alias' => 'company_info',
        ]);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Companys[]|Companys
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Companys
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
        return 'companys';
    }

}
