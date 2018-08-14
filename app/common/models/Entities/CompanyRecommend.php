<?php

namespace Wdxr\Models\Entities;

class CompanyRecommend extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $recommend_id;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $recommender;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=false)
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
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $status;

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
     * Method to set the value of field recommender
     *
     * @param integer $recommender
     * @return $this
     */
    public function setRecommender($recommender)
    {
        $this->recommender = $recommender;

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
     * Returns the value of field recommend_id
     *
     * @return integer
     */
    public function getRecommendId()
    {
        return $this->recommend_id;
    }

    /**
     * Returns the value of field recommender
     *
     * @return integer
     */
    public function getRecommender()
    {
        return $this->recommender;
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
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
        $this->hasOne('recommend_id', __NAMESPACE__.'\Companys', 'id', [
            'alias' => 'companys',
        ]);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyRecommend[]|CompanyRecommend
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CompanyRecommend
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
        return 'company_recommend';
    }

}
