<?php

namespace Wdxr\Models\Entities;

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;

class Admins extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=20, nullable=false)
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
     * @Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     *
     * @var string
     * @Column(type="string", length=60, nullable=false)
     */
    protected $password;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $position_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $avatar;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $branch_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $department_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $is_probation;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $on_job;

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
    protected $is_lock;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $update_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    protected $created_by;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $entry_time;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $formal_time;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $created_at;

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
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field phone
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field position_id
     *
     * @param integer $position_id
     * @return $this
     */
    public function setPositionId($position_id)
    {
        $this->position_id = $position_id;

        return $this;
    }

    /**
     * Method to set the value of field avatar
     *
     * @param integer $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

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
     * Method to set the value of field department_id
     *
     * @param integer $department_id
     * @return $this
     */
    public function setDepartmentId($department_id)
    {
        $this->department_id = $department_id;

        return $this;
    }

    /**
     * Method to set the value of field is_probation
     *
     * @param integer $is_probation
     * @return $this
     */
    public function setIsProbation($is_probation)
    {
        $this->is_probation = $is_probation;

        return $this;
    }

    /**
     * Method to set the value of field on_job
     *
     * @param integer $on_job
     * @return $this
     */
    public function setOnJob($on_job)
    {
        $this->on_job = $on_job;

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
     * Method to set the value of field is_lock
     *
     * @param integer $is_lock
     * @return $this
     */
    public function setIsLock($is_lock)
    {
        $this->is_lock = $is_lock;

        return $this;
    }

    /**
     * Method to set the value of field update_at
     *
     * @param string $update_at
     * @return $this
     */
    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;

        return $this;
    }

    /**
     * Method to set the value of field created_by
     *
     * @param integer $created_by
     * @return $this
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * Method to set the value of field entry_time
     *
     * @param string $entry_time
     * @return $this
     */
    public function setEntryTime($entry_time)
    {
        $this->entry_time = $entry_time;

        return $this;
    }

    /**
     * Method to set the value of field formal_time
     *
     * @param string $formal_time
     * @return $this
     */
    public function setFormalTime($formal_time)
    {
        $this->formal_time = $formal_time;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

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
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field position_id
     *
     * @return integer
     */
    public function getPositionId()
    {
        return $this->position_id;
    }

    /**
     * Returns the value of field avatar
     *
     * @return integer
     */
    public function getAvatar()
    {
        return $this->avatar;
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
     * Returns the value of field department_id
     *
     * @return integer
     */
    public function getDepartmentId()
    {
        return $this->department_id;
    }

    /**
     * Returns the value of field is_probation
     *
     * @return integer
     */
    public function getIsProbation()
    {
        return $this->is_probation;
    }

    /**
     * Returns the value of field on_job
     *
     * @return integer
     */
    public function getOnJob()
    {
        return $this->on_job;
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
     * Returns the value of field is_lock
     *
     * @return integer
     */
    public function getIsLock()
    {
        return $this->is_lock;
    }

    /**
     * Returns the value of field update_at
     *
     * @return string
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * Returns the value of field created_by
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Returns the value of field entry_time
     *
     * @return string
     */
    public function getEntryTime()
    {
        return $this->entry_time;
    }

    /**
     * Returns the value of field formal_time
     *
     * @return string
     */
    public function getFormalTime()
    {
        return $this->formal_time;
    }

    /**
     * Returns the value of field created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new \Phalcon\Validation();

        $validator->rules('name', [
            new \Phalcon\Validation\Validator\Uniqueness([
                'message' => '该用户名已经存在'
            ])
        ]);

        $validator->rules('email', [
            new \Phalcon\Validation\Validator\Uniqueness([
                'message' => '该邮箱已经存在'
            ]),
        ]);

        $validator->rules('phone', [
            new \Phalcon\Validation\Validator\Uniqueness([
                'message' => '该手机号已经存在'
            ]),
        ]);

        $this->validate($validator);

        return $this->validationHasFailed() != true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("guanjia16_new");
        $this->belongsTo('position_id', __NAMESPACE__ . '\Positions', 'id', [
            'alias' => 'positions',
            'reusable' => true
        ]);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Admins[]|Admins
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Admins
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
        return 'admins';
    }

}
