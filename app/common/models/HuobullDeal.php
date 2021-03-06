<?php

namespace App\Entities;

class HuobullDeal extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $ id;

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
    protected $short_name;

    /**
     *
     * @var string
     * @Column(type="string", length=32, nullable=false)
     */
    protected $sn;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $ips_sn;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $brw_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $cate_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $type_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_guarantee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $agency_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $risk_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=19, nullable=false)
     */
    protected $amount;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=false)
     */
    protected $rate;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
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
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_hidden;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_recommend;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_new;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $is_apart;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $apart_pid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $repay_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=false)
     */
    protected $repay_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $repay_time_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $invest_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=5, nullable=false)
     */
    protected $invest_count;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $min_unit;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $service_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $borrow_manage_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $invest_manage_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $interest_manage_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $interest_penalties_general_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $interest_penalties_serious_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $overdue_general_manage_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $overdue_serious_manage_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=6, nullable=true)
     */
    protected $creditor_transfer_fee;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $creditor_transfer_limit_date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_invest_start;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_invest_end;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_invest_success;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_doloan;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_finish;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_refund;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $contract_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $tcontract_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_update;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    protected $time_create;

    /**
     * Method to set the value of field  id
     *
     * @param integer $ id
     * @return $this
     */
    public function set id($ id)
    {
        $this-> id = $ id;

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
     * Method to set the value of field short_name
     *
     * @param string $short_name
     * @return $this
     */
    public function setShortName($short_name)
    {
        $this->short_name = $short_name;

        return $this;
    }

    /**
     * Method to set the value of field sn
     *
     * @param string $sn
     * @return $this
     */
    public function setSn($sn)
    {
        $this->sn = $sn;

        return $this;
    }

    /**
     * Method to set the value of field ips_sn
     *
     * @param string $ips_sn
     * @return $this
     */
    public function setIpsSn($ips_sn)
    {
        $this->ips_sn = $ips_sn;

        return $this;
    }

    /**
     * Method to set the value of field brw_id
     *
     * @param integer $brw_id
     * @return $this
     */
    public function setBrwId($brw_id)
    {
        $this->brw_id = $brw_id;

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
     * Method to set the value of field type_id
     *
     * @param integer $type_id
     * @return $this
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;

        return $this;
    }

    /**
     * Method to set the value of field is_guarantee
     *
     * @param integer $is_guarantee
     * @return $this
     */
    public function setIsGuarantee($is_guarantee)
    {
        $this->is_guarantee = $is_guarantee;

        return $this;
    }

    /**
     * Method to set the value of field agency_id
     *
     * @param integer $agency_id
     * @return $this
     */
    public function setAgencyId($agency_id)
    {
        $this->agency_id = $agency_id;

        return $this;
    }

    /**
     * Method to set the value of field risk_type
     *
     * @param integer $risk_type
     * @return $this
     */
    public function setRiskType($risk_type)
    {
        $this->risk_type = $risk_type;

        return $this;
    }

    /**
     * Method to set the value of field amount
     *
     * @param integer $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Method to set the value of field rate
     *
     * @param integer $rate
     * @return $this
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

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
     * Method to set the value of field is_hidden
     *
     * @param integer $is_hidden
     * @return $this
     */
    public function setIsHidden($is_hidden)
    {
        $this->is_hidden = $is_hidden;

        return $this;
    }

    /**
     * Method to set the value of field is_recommend
     *
     * @param integer $is_recommend
     * @return $this
     */
    public function setIsRecommend($is_recommend)
    {
        $this->is_recommend = $is_recommend;

        return $this;
    }

    /**
     * Method to set the value of field is_new
     *
     * @param integer $is_new
     * @return $this
     */
    public function setIsNew($is_new)
    {
        $this->is_new = $is_new;

        return $this;
    }

    /**
     * Method to set the value of field is_apart
     *
     * @param integer $is_apart
     * @return $this
     */
    public function setIsApart($is_apart)
    {
        $this->is_apart = $is_apart;

        return $this;
    }

    /**
     * Method to set the value of field apart_pid
     *
     * @param integer $apart_pid
     * @return $this
     */
    public function setApartPid($apart_pid)
    {
        $this->apart_pid = $apart_pid;

        return $this;
    }

    /**
     * Method to set the value of field repay_type
     *
     * @param integer $repay_type
     * @return $this
     */
    public function setRepayType($repay_type)
    {
        $this->repay_type = $repay_type;

        return $this;
    }

    /**
     * Method to set the value of field repay_time
     *
     * @param integer $repay_time
     * @return $this
     */
    public function setRepayTime($repay_time)
    {
        $this->repay_time = $repay_time;

        return $this;
    }

    /**
     * Method to set the value of field repay_time_type
     *
     * @param integer $repay_time_type
     * @return $this
     */
    public function setRepayTimeType($repay_time_type)
    {
        $this->repay_time_type = $repay_time_type;

        return $this;
    }

    /**
     * Method to set the value of field invest_type
     *
     * @param integer $invest_type
     * @return $this
     */
    public function setInvestType($invest_type)
    {
        $this->invest_type = $invest_type;

        return $this;
    }

    /**
     * Method to set the value of field invest_count
     *
     * @param integer $invest_count
     * @return $this
     */
    public function setInvestCount($invest_count)
    {
        $this->invest_count = $invest_count;

        return $this;
    }

    /**
     * Method to set the value of field min_unit
     *
     * @param integer $min_unit
     * @return $this
     */
    public function setMinUnit($min_unit)
    {
        $this->min_unit = $min_unit;

        return $this;
    }

    /**
     * Method to set the value of field service_fee
     *
     * @param integer $service_fee
     * @return $this
     */
    public function setServiceFee($service_fee)
    {
        $this->service_fee = $service_fee;

        return $this;
    }

    /**
     * Method to set the value of field borrow_manage_fee
     *
     * @param integer $borrow_manage_fee
     * @return $this
     */
    public function setBorrowManageFee($borrow_manage_fee)
    {
        $this->borrow_manage_fee = $borrow_manage_fee;

        return $this;
    }

    /**
     * Method to set the value of field invest_manage_fee
     *
     * @param integer $invest_manage_fee
     * @return $this
     */
    public function setInvestManageFee($invest_manage_fee)
    {
        $this->invest_manage_fee = $invest_manage_fee;

        return $this;
    }

    /**
     * Method to set the value of field interest_manage_fee
     *
     * @param integer $interest_manage_fee
     * @return $this
     */
    public function setInterestManageFee($interest_manage_fee)
    {
        $this->interest_manage_fee = $interest_manage_fee;

        return $this;
    }

    /**
     * Method to set the value of field interest_penalties_general_fee
     *
     * @param integer $interest_penalties_general_fee
     * @return $this
     */
    public function setInterestPenaltiesGeneralFee($interest_penalties_general_fee)
    {
        $this->interest_penalties_general_fee = $interest_penalties_general_fee;

        return $this;
    }

    /**
     * Method to set the value of field interest_penalties_serious_fee
     *
     * @param integer $interest_penalties_serious_fee
     * @return $this
     */
    public function setInterestPenaltiesSeriousFee($interest_penalties_serious_fee)
    {
        $this->interest_penalties_serious_fee = $interest_penalties_serious_fee;

        return $this;
    }

    /**
     * Method to set the value of field overdue_general_manage_fee
     *
     * @param integer $overdue_general_manage_fee
     * @return $this
     */
    public function setOverdueGeneralManageFee($overdue_general_manage_fee)
    {
        $this->overdue_general_manage_fee = $overdue_general_manage_fee;

        return $this;
    }

    /**
     * Method to set the value of field overdue_serious_manage_fee
     *
     * @param integer $overdue_serious_manage_fee
     * @return $this
     */
    public function setOverdueSeriousManageFee($overdue_serious_manage_fee)
    {
        $this->overdue_serious_manage_fee = $overdue_serious_manage_fee;

        return $this;
    }

    /**
     * Method to set the value of field creditor_transfer_fee
     *
     * @param integer $creditor_transfer_fee
     * @return $this
     */
    public function setCreditorTransferFee($creditor_transfer_fee)
    {
        $this->creditor_transfer_fee = $creditor_transfer_fee;

        return $this;
    }

    /**
     * Method to set the value of field creditor_transfer_limit_date
     *
     * @param integer $creditor_transfer_limit_date
     * @return $this
     */
    public function setCreditorTransferLimitDate($creditor_transfer_limit_date)
    {
        $this->creditor_transfer_limit_date = $creditor_transfer_limit_date;

        return $this;
    }

    /**
     * Method to set the value of field time_invest_start
     *
     * @param integer $time_invest_start
     * @return $this
     */
    public function setTimeInvestStart($time_invest_start)
    {
        $this->time_invest_start = $time_invest_start;

        return $this;
    }

    /**
     * Method to set the value of field time_invest_end
     *
     * @param integer $time_invest_end
     * @return $this
     */
    public function setTimeInvestEnd($time_invest_end)
    {
        $this->time_invest_end = $time_invest_end;

        return $this;
    }

    /**
     * Method to set the value of field time_invest_success
     *
     * @param integer $time_invest_success
     * @return $this
     */
    public function setTimeInvestSuccess($time_invest_success)
    {
        $this->time_invest_success = $time_invest_success;

        return $this;
    }

    /**
     * Method to set the value of field time_doloan
     *
     * @param integer $time_doloan
     * @return $this
     */
    public function setTimeDoloan($time_doloan)
    {
        $this->time_doloan = $time_doloan;

        return $this;
    }

    /**
     * Method to set the value of field time_finish
     *
     * @param integer $time_finish
     * @return $this
     */
    public function setTimeFinish($time_finish)
    {
        $this->time_finish = $time_finish;

        return $this;
    }

    /**
     * Method to set the value of field time_refund
     *
     * @param integer $time_refund
     * @return $this
     */
    public function setTimeRefund($time_refund)
    {
        $this->time_refund = $time_refund;

        return $this;
    }

    /**
     * Method to set the value of field contract_id
     *
     * @param integer $contract_id
     * @return $this
     */
    public function setContractId($contract_id)
    {
        $this->contract_id = $contract_id;

        return $this;
    }

    /**
     * Method to set the value of field tcontract_id
     *
     * @param integer $tcontract_id
     * @return $this
     */
    public function setTcontractId($tcontract_id)
    {
        $this->tcontract_id = $tcontract_id;

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
     * Returns the value of field  id
     *
     * @return integer
     */
    public function get id()
    {
        return $this-> id;
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
     * Returns the value of field short_name
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * Returns the value of field sn
     *
     * @return string
     */
    public function getSn()
    {
        return $this->sn;
    }

    /**
     * Returns the value of field ips_sn
     *
     * @return string
     */
    public function getIpsSn()
    {
        return $this->ips_sn;
    }

    /**
     * Returns the value of field brw_id
     *
     * @return integer
     */
    public function getBrwId()
    {
        return $this->brw_id;
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
     * Returns the value of field type_id
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Returns the value of field is_guarantee
     *
     * @return integer
     */
    public function getIsGuarantee()
    {
        return $this->is_guarantee;
    }

    /**
     * Returns the value of field agency_id
     *
     * @return integer
     */
    public function getAgencyId()
    {
        return $this->agency_id;
    }

    /**
     * Returns the value of field risk_type
     *
     * @return integer
     */
    public function getRiskType()
    {
        return $this->risk_type;
    }

    /**
     * Returns the value of field amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the value of field rate
     *
     * @return integer
     */
    public function getRate()
    {
        return $this->rate;
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
     * Returns the value of field is_hidden
     *
     * @return integer
     */
    public function getIsHidden()
    {
        return $this->is_hidden;
    }

    /**
     * Returns the value of field is_recommend
     *
     * @return integer
     */
    public function getIsRecommend()
    {
        return $this->is_recommend;
    }

    /**
     * Returns the value of field is_new
     *
     * @return integer
     */
    public function getIsNew()
    {
        return $this->is_new;
    }

    /**
     * Returns the value of field is_apart
     *
     * @return integer
     */
    public function getIsApart()
    {
        return $this->is_apart;
    }

    /**
     * Returns the value of field apart_pid
     *
     * @return integer
     */
    public function getApartPid()
    {
        return $this->apart_pid;
    }

    /**
     * Returns the value of field repay_type
     *
     * @return integer
     */
    public function getRepayType()
    {
        return $this->repay_type;
    }

    /**
     * Returns the value of field repay_time
     *
     * @return integer
     */
    public function getRepayTime()
    {
        return $this->repay_time;
    }

    /**
     * Returns the value of field repay_time_type
     *
     * @return integer
     */
    public function getRepayTimeType()
    {
        return $this->repay_time_type;
    }

    /**
     * Returns the value of field invest_type
     *
     * @return integer
     */
    public function getInvestType()
    {
        return $this->invest_type;
    }

    /**
     * Returns the value of field invest_count
     *
     * @return integer
     */
    public function getInvestCount()
    {
        return $this->invest_count;
    }

    /**
     * Returns the value of field min_unit
     *
     * @return integer
     */
    public function getMinUnit()
    {
        return $this->min_unit;
    }

    /**
     * Returns the value of field service_fee
     *
     * @return integer
     */
    public function getServiceFee()
    {
        return $this->service_fee;
    }

    /**
     * Returns the value of field borrow_manage_fee
     *
     * @return integer
     */
    public function getBorrowManageFee()
    {
        return $this->borrow_manage_fee;
    }

    /**
     * Returns the value of field invest_manage_fee
     *
     * @return integer
     */
    public function getInvestManageFee()
    {
        return $this->invest_manage_fee;
    }

    /**
     * Returns the value of field interest_manage_fee
     *
     * @return integer
     */
    public function getInterestManageFee()
    {
        return $this->interest_manage_fee;
    }

    /**
     * Returns the value of field interest_penalties_general_fee
     *
     * @return integer
     */
    public function getInterestPenaltiesGeneralFee()
    {
        return $this->interest_penalties_general_fee;
    }

    /**
     * Returns the value of field interest_penalties_serious_fee
     *
     * @return integer
     */
    public function getInterestPenaltiesSeriousFee()
    {
        return $this->interest_penalties_serious_fee;
    }

    /**
     * Returns the value of field overdue_general_manage_fee
     *
     * @return integer
     */
    public function getOverdueGeneralManageFee()
    {
        return $this->overdue_general_manage_fee;
    }

    /**
     * Returns the value of field overdue_serious_manage_fee
     *
     * @return integer
     */
    public function getOverdueSeriousManageFee()
    {
        return $this->overdue_serious_manage_fee;
    }

    /**
     * Returns the value of field creditor_transfer_fee
     *
     * @return integer
     */
    public function getCreditorTransferFee()
    {
        return $this->creditor_transfer_fee;
    }

    /**
     * Returns the value of field creditor_transfer_limit_date
     *
     * @return integer
     */
    public function getCreditorTransferLimitDate()
    {
        return $this->creditor_transfer_limit_date;
    }

    /**
     * Returns the value of field time_invest_start
     *
     * @return integer
     */
    public function getTimeInvestStart()
    {
        return $this->time_invest_start;
    }

    /**
     * Returns the value of field time_invest_end
     *
     * @return integer
     */
    public function getTimeInvestEnd()
    {
        return $this->time_invest_end;
    }

    /**
     * Returns the value of field time_invest_success
     *
     * @return integer
     */
    public function getTimeInvestSuccess()
    {
        return $this->time_invest_success;
    }

    /**
     * Returns the value of field time_doloan
     *
     * @return integer
     */
    public function getTimeDoloan()
    {
        return $this->time_doloan;
    }

    /**
     * Returns the value of field time_finish
     *
     * @return integer
     */
    public function getTimeFinish()
    {
        return $this->time_finish;
    }

    /**
     * Returns the value of field time_refund
     *
     * @return integer
     */
    public function getTimeRefund()
    {
        return $this->time_refund;
    }

    /**
     * Returns the value of field contract_id
     *
     * @return integer
     */
    public function getContractId()
    {
        return $this->contract_id;
    }

    /**
     * Returns the value of field tcontract_id
     *
     * @return integer
     */
    public function getTcontractId()
    {
        return $this->tcontract_id;
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
     * Returns the value of field time_create
     *
     * @return integer
     */
    public function getTimeCreate()
    {
        return $this->time_create;
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
        return 'huobull_deal';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDeal[]|HuobullDeal
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullDeal
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
