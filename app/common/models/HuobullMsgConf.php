<?php

namespace App\Entities;

class HuobullMsgConf extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_asked;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_asked;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_bid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_bid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_myfail;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_myfail;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_half;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_half;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_bidsuccess;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_bidsuccess;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_fail;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_fail;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_bidrepaid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_bidrepaid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_answer;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_answer;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_transferfail;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_transferfail;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_transfer;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_transfer;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_redenvelope;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_redenvelope;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_rate;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_rate;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_integral;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_integral;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $mail_gift;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $sms_gift;

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
     * Method to set the value of field mail_asked
     *
     * @param integer $mail_asked
     * @return $this
     */
    public function setMailAsked($mail_asked)
    {
        $this->mail_asked = $mail_asked;

        return $this;
    }

    /**
     * Method to set the value of field sms_asked
     *
     * @param integer $sms_asked
     * @return $this
     */
    public function setSmsAsked($sms_asked)
    {
        $this->sms_asked = $sms_asked;

        return $this;
    }

    /**
     * Method to set the value of field mail_bid
     *
     * @param integer $mail_bid
     * @return $this
     */
    public function setMailBid($mail_bid)
    {
        $this->mail_bid = $mail_bid;

        return $this;
    }

    /**
     * Method to set the value of field sms_bid
     *
     * @param integer $sms_bid
     * @return $this
     */
    public function setSmsBid($sms_bid)
    {
        $this->sms_bid = $sms_bid;

        return $this;
    }

    /**
     * Method to set the value of field mail_myfail
     *
     * @param integer $mail_myfail
     * @return $this
     */
    public function setMailMyfail($mail_myfail)
    {
        $this->mail_myfail = $mail_myfail;

        return $this;
    }

    /**
     * Method to set the value of field sms_myfail
     *
     * @param integer $sms_myfail
     * @return $this
     */
    public function setSmsMyfail($sms_myfail)
    {
        $this->sms_myfail = $sms_myfail;

        return $this;
    }

    /**
     * Method to set the value of field mail_half
     *
     * @param integer $mail_half
     * @return $this
     */
    public function setMailHalf($mail_half)
    {
        $this->mail_half = $mail_half;

        return $this;
    }

    /**
     * Method to set the value of field sms_half
     *
     * @param integer $sms_half
     * @return $this
     */
    public function setSmsHalf($sms_half)
    {
        $this->sms_half = $sms_half;

        return $this;
    }

    /**
     * Method to set the value of field mail_bidsuccess
     *
     * @param integer $mail_bidsuccess
     * @return $this
     */
    public function setMailBidsuccess($mail_bidsuccess)
    {
        $this->mail_bidsuccess = $mail_bidsuccess;

        return $this;
    }

    /**
     * Method to set the value of field sms_bidsuccess
     *
     * @param integer $sms_bidsuccess
     * @return $this
     */
    public function setSmsBidsuccess($sms_bidsuccess)
    {
        $this->sms_bidsuccess = $sms_bidsuccess;

        return $this;
    }

    /**
     * Method to set the value of field mail_fail
     *
     * @param integer $mail_fail
     * @return $this
     */
    public function setMailFail($mail_fail)
    {
        $this->mail_fail = $mail_fail;

        return $this;
    }

    /**
     * Method to set the value of field sms_fail
     *
     * @param integer $sms_fail
     * @return $this
     */
    public function setSmsFail($sms_fail)
    {
        $this->sms_fail = $sms_fail;

        return $this;
    }

    /**
     * Method to set the value of field mail_bidrepaid
     *
     * @param integer $mail_bidrepaid
     * @return $this
     */
    public function setMailBidrepaid($mail_bidrepaid)
    {
        $this->mail_bidrepaid = $mail_bidrepaid;

        return $this;
    }

    /**
     * Method to set the value of field sms_bidrepaid
     *
     * @param integer $sms_bidrepaid
     * @return $this
     */
    public function setSmsBidrepaid($sms_bidrepaid)
    {
        $this->sms_bidrepaid = $sms_bidrepaid;

        return $this;
    }

    /**
     * Method to set the value of field mail_answer
     *
     * @param integer $mail_answer
     * @return $this
     */
    public function setMailAnswer($mail_answer)
    {
        $this->mail_answer = $mail_answer;

        return $this;
    }

    /**
     * Method to set the value of field sms_answer
     *
     * @param integer $sms_answer
     * @return $this
     */
    public function setSmsAnswer($sms_answer)
    {
        $this->sms_answer = $sms_answer;

        return $this;
    }

    /**
     * Method to set the value of field mail_transferfail
     *
     * @param integer $mail_transferfail
     * @return $this
     */
    public function setMailTransferfail($mail_transferfail)
    {
        $this->mail_transferfail = $mail_transferfail;

        return $this;
    }

    /**
     * Method to set the value of field sms_transferfail
     *
     * @param integer $sms_transferfail
     * @return $this
     */
    public function setSmsTransferfail($sms_transferfail)
    {
        $this->sms_transferfail = $sms_transferfail;

        return $this;
    }

    /**
     * Method to set the value of field mail_transfer
     *
     * @param integer $mail_transfer
     * @return $this
     */
    public function setMailTransfer($mail_transfer)
    {
        $this->mail_transfer = $mail_transfer;

        return $this;
    }

    /**
     * Method to set the value of field sms_transfer
     *
     * @param integer $sms_transfer
     * @return $this
     */
    public function setSmsTransfer($sms_transfer)
    {
        $this->sms_transfer = $sms_transfer;

        return $this;
    }

    /**
     * Method to set the value of field mail_redenvelope
     *
     * @param integer $mail_redenvelope
     * @return $this
     */
    public function setMailRedenvelope($mail_redenvelope)
    {
        $this->mail_redenvelope = $mail_redenvelope;

        return $this;
    }

    /**
     * Method to set the value of field sms_redenvelope
     *
     * @param integer $sms_redenvelope
     * @return $this
     */
    public function setSmsRedenvelope($sms_redenvelope)
    {
        $this->sms_redenvelope = $sms_redenvelope;

        return $this;
    }

    /**
     * Method to set the value of field mail_rate
     *
     * @param integer $mail_rate
     * @return $this
     */
    public function setMailRate($mail_rate)
    {
        $this->mail_rate = $mail_rate;

        return $this;
    }

    /**
     * Method to set the value of field sms_rate
     *
     * @param integer $sms_rate
     * @return $this
     */
    public function setSmsRate($sms_rate)
    {
        $this->sms_rate = $sms_rate;

        return $this;
    }

    /**
     * Method to set the value of field mail_integral
     *
     * @param integer $mail_integral
     * @return $this
     */
    public function setMailIntegral($mail_integral)
    {
        $this->mail_integral = $mail_integral;

        return $this;
    }

    /**
     * Method to set the value of field sms_integral
     *
     * @param integer $sms_integral
     * @return $this
     */
    public function setSmsIntegral($sms_integral)
    {
        $this->sms_integral = $sms_integral;

        return $this;
    }

    /**
     * Method to set the value of field mail_gift
     *
     * @param integer $mail_gift
     * @return $this
     */
    public function setMailGift($mail_gift)
    {
        $this->mail_gift = $mail_gift;

        return $this;
    }

    /**
     * Method to set the value of field sms_gift
     *
     * @param integer $sms_gift
     * @return $this
     */
    public function setSmsGift($sms_gift)
    {
        $this->sms_gift = $sms_gift;

        return $this;
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
     * Returns the value of field mail_asked
     *
     * @return integer
     */
    public function getMailAsked()
    {
        return $this->mail_asked;
    }

    /**
     * Returns the value of field sms_asked
     *
     * @return integer
     */
    public function getSmsAsked()
    {
        return $this->sms_asked;
    }

    /**
     * Returns the value of field mail_bid
     *
     * @return integer
     */
    public function getMailBid()
    {
        return $this->mail_bid;
    }

    /**
     * Returns the value of field sms_bid
     *
     * @return integer
     */
    public function getSmsBid()
    {
        return $this->sms_bid;
    }

    /**
     * Returns the value of field mail_myfail
     *
     * @return integer
     */
    public function getMailMyfail()
    {
        return $this->mail_myfail;
    }

    /**
     * Returns the value of field sms_myfail
     *
     * @return integer
     */
    public function getSmsMyfail()
    {
        return $this->sms_myfail;
    }

    /**
     * Returns the value of field mail_half
     *
     * @return integer
     */
    public function getMailHalf()
    {
        return $this->mail_half;
    }

    /**
     * Returns the value of field sms_half
     *
     * @return integer
     */
    public function getSmsHalf()
    {
        return $this->sms_half;
    }

    /**
     * Returns the value of field mail_bidsuccess
     *
     * @return integer
     */
    public function getMailBidsuccess()
    {
        return $this->mail_bidsuccess;
    }

    /**
     * Returns the value of field sms_bidsuccess
     *
     * @return integer
     */
    public function getSmsBidsuccess()
    {
        return $this->sms_bidsuccess;
    }

    /**
     * Returns the value of field mail_fail
     *
     * @return integer
     */
    public function getMailFail()
    {
        return $this->mail_fail;
    }

    /**
     * Returns the value of field sms_fail
     *
     * @return integer
     */
    public function getSmsFail()
    {
        return $this->sms_fail;
    }

    /**
     * Returns the value of field mail_bidrepaid
     *
     * @return integer
     */
    public function getMailBidrepaid()
    {
        return $this->mail_bidrepaid;
    }

    /**
     * Returns the value of field sms_bidrepaid
     *
     * @return integer
     */
    public function getSmsBidrepaid()
    {
        return $this->sms_bidrepaid;
    }

    /**
     * Returns the value of field mail_answer
     *
     * @return integer
     */
    public function getMailAnswer()
    {
        return $this->mail_answer;
    }

    /**
     * Returns the value of field sms_answer
     *
     * @return integer
     */
    public function getSmsAnswer()
    {
        return $this->sms_answer;
    }

    /**
     * Returns the value of field mail_transferfail
     *
     * @return integer
     */
    public function getMailTransferfail()
    {
        return $this->mail_transferfail;
    }

    /**
     * Returns the value of field sms_transferfail
     *
     * @return integer
     */
    public function getSmsTransferfail()
    {
        return $this->sms_transferfail;
    }

    /**
     * Returns the value of field mail_transfer
     *
     * @return integer
     */
    public function getMailTransfer()
    {
        return $this->mail_transfer;
    }

    /**
     * Returns the value of field sms_transfer
     *
     * @return integer
     */
    public function getSmsTransfer()
    {
        return $this->sms_transfer;
    }

    /**
     * Returns the value of field mail_redenvelope
     *
     * @return integer
     */
    public function getMailRedenvelope()
    {
        return $this->mail_redenvelope;
    }

    /**
     * Returns the value of field sms_redenvelope
     *
     * @return integer
     */
    public function getSmsRedenvelope()
    {
        return $this->sms_redenvelope;
    }

    /**
     * Returns the value of field mail_rate
     *
     * @return integer
     */
    public function getMailRate()
    {
        return $this->mail_rate;
    }

    /**
     * Returns the value of field sms_rate
     *
     * @return integer
     */
    public function getSmsRate()
    {
        return $this->sms_rate;
    }

    /**
     * Returns the value of field mail_integral
     *
     * @return integer
     */
    public function getMailIntegral()
    {
        return $this->mail_integral;
    }

    /**
     * Returns the value of field sms_integral
     *
     * @return integer
     */
    public function getSmsIntegral()
    {
        return $this->sms_integral;
    }

    /**
     * Returns the value of field mail_gift
     *
     * @return integer
     */
    public function getMailGift()
    {
        return $this->mail_gift;
    }

    /**
     * Returns the value of field sms_gift
     *
     * @return integer
     */
    public function getSmsGift()
    {
        return $this->sms_gift;
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
        return 'huobull_msg_conf';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullMsgConf[]|HuobullMsgConf
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HuobullMsgConf
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
