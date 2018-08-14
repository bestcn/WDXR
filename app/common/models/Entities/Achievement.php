<?php

namespace Wdxr\Models\Entities;

class Achievement extends \Phalcon\Mvc\Model
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
    protected $admin_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $branch_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $contract_num;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $money;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $time;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $recommender;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $administrator;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $company_name;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    protected $commission;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $admin_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $service_id;

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
     * Method to set the value of field admin_name
     *
     * @param string $admin_name
     * @return $this
     */
    public function setAdminName($admin_name)
    {
        $this->admin_name = $admin_name;

        return $this;
    }

    /**
     * Method to set the value of field branch_id
     *
     * @param integer $branch_id
     * @return $this
     */
    public function setBranchId($branch_id)
    {
        $this->branch_id = $branch_id;

        return $this;
    }

    /**
     * Method to set the value of field contract_num
     *
     * @param string $contract_num
     * @return $this
     */
    public function setContractNum($contract_num)
    {
        $this->contract_num = $contract_num;

        return $this;
    }

    /**
     * Method to set the value of field money
     *
     * @param double $money
     * @return $this
     */
    public function setMoney($money)
    {
        $this->money = $money;

        return $this;
    }

    /**
     * Method to set the value of field time
     *
     * @param integer $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Method to set the value of field recommender
     *
     * @param string $recommender
     * @return $this
     */
    public function setRecommender($recommender)
    {
        $this->recommender = $recommender;

        return $this;
    }

    /**
     * Method to set the value of field administrator
     *
     * @param string $administrator
     * @return $this
     */
    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;

        return $this;
    }

    /**
     * Method to set the value of field company_name
     *
     * @param string $company_name
     * @return $this
     */
    public function setCompanyName($company_name)
    {
        $this->company_name = $company_name;

        return $this;
    }

    /**
     * Method to set the value of field commission
     *
     * @param double $commission
     * @return $this
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;

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
     * Method to set the value of field service_id
     *
     * @param integer $service_id
     * @return $this
     */
    public function setServiceId($service_id)
    {
        $this->service_id = $service_id;

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
     * Returns the value of field admin_name
     *
     * @return string
     */
    public function getAdminName()
    {
        return $this->admin_name;
    }

    /**
     * Returns the value of field branch_id
     *
     * @return integer
     */
    public function getBranchId()
    {
        return $this->branch_id;
    }

    /**
     * Returns the value of field contract_num
     *
     * @return string
     */
    public function getContractNum()
    {
        return $this->contract_num;
    }

    /**
     * Returns the value of field money
     *
     * @return double
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Returns the value of field time
     *
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Returns the value of field recommender
     *
     * @return string
     */
    public function getRecommender()
    {
        return $this->recommender;
    }

    /**
     * Returns the value of field administrator
     *
     * @return string
     */
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * Returns the value of field company_name
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Returns the value of field commission
     *
     * @return double
     */
    public function getCommission()
    {
        return $this->commission;
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
     * Returns the value of field admin_id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * Returns the value of field service_id
     *
     * @return integer
     */
    public function getServiceId()
    {
        return $this->service_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
        $this->addBehavior(
            new \Phalcon\Mvc\Model\Behavior\SoftDelete([
                'field' => 'is_delete',
                'value' => 1,
            ])
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Achievement[]|Achievement
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Achievement
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
        return 'achievement';
    }

}
