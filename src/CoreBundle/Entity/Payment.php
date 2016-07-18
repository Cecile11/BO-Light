<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Uuid", type="string", length=255, unique=true)
     */
    private $uuid;

    /**
     * @var array
     *
     * @ORM\Column(name="Data", type="object")
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_cust_id", type="string", length=255, nullable=true)
     */
    private $vadsCustId;

    /**
     *@var string
     *
     * @ORM\Column(name="vads_cust_first_name", type="string", length=255, nullable=true)
     */
    private $vadsCustFirstName;

    /**
     *@var string
     *
     * @ORM\Column(name="vads_trans_status", type="string", length=255, nullable=true)
     */
    private $vadsTransStatus;

    /**
     *@var string
     *
     * @ORM\Column(name="vads_cust_last_name", type="string", length=255, nullable=true)
     */
    private $vadsCustLastName;

    /**
     * @var \DateTime
     * @ORM\Column(name="vads_effective_creation_date", type="datetime", nullable=true)
     */
    private $vadsEffectiveCreationDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="vads_effective_amount", type="string", length=255, nullable=true)
     */
    private $vadsEffectiveAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_refund_amount", type="string", length=255,nullable=true)
     */
    private $vadsRefundAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_payment_type", type="string", length=255,nullable=true)
     */
    private $vadsPaymentType;
    /**
     * @var string
     *
     * @ORM\Column(name="vads_operation_type", type="string", length=255,nullable=true)
     */
    private $vadsOperationType;
    /**
     * @var string
     *
     * @ORM\Column(name="vads_currency", type="string", length=255,nullable=true)
     */
    private $vadsCurrency;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     *
     * @return Payment
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return Payment
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set vadsCustId
     *
     * @param string $vadsCustId
     *
     * @return Payment
     */
    public function setVadsCustId($vadsCustId)
    {
        $this->vadsCustId = $vadsCustId;

        return $this;
    }

    /**
     * Get vadsCustId
     *
     * @return string
     */
    public function getVadsCustId()
    {
        return $this->vadsCustId;
    }

    /**
     * Set vadsCustFirstName
     *
     * @param string $vadsCustFirstName
     *
     * @return Payment
     */
    public function setVadsCustFirstName($vadsCustFirstName)
    {
        $this->vadsCustFirstName = $vadsCustFirstName;

        return $this;
    }

    /**
     * Get vadsCustFirstName
     *
     * @return string
     */
    public function getVadsCustFirstName()
    {
        return $this->vadsCustFirstName;
    }

    /**
     * Set vadsTransStatus
     *
     * @param string $vadsTransStatus
     *
     * @return Payment
     */
    public function setVadsTransStatus($vadsTransStatus)
    {
        $this->vadsTransStatus = $vadsTransStatus;

        return $this;
    }

    /**
     * Get vadsTransStatus
     *
     * @return string
     */
    public function getVadsTransStatus()
    {
        return $this->vadsTransStatus;
    }

    /**
     * Set vadsCustLastName
     *
     * @param string $vadsCustLastName
     *
     * @return Payment
     */
    public function setVadsCustLastName($vadsCustLastName)
    {
        $this->vadsCustLastName = $vadsCustLastName;

        return $this;
    }

    /**
     * Get vadsCustLastName
     *
     * @return string
     */
    public function getVadsCustLastName()
    {
        return $this->vadsCustLastName;
    }

    /**
     * Set vadsEffectiveCreationDate
     *
     * @param \DateTime $vadsEffectiveCreationDate
     *
     * @return Payment
     */
    public function setVadsEffectiveCreationDate($vadsEffectiveCreationDate)
    {
        $this->vadsEffectiveCreationDate = $vadsEffectiveCreationDate;

        return $this;
    }

    /**
     * Get vadsEffectiveCreationDate
     *
     * @return \DateTime
     */
    public function getVadsEffectiveCreationDate()
    {
        return $this->vadsEffectiveCreationDate;
    }

    /**
     * Set vadsEffectiveAmount
     *
     * @param string $vadsEffectiveAmount
     *
     * @return Payment
     */
    public function setVadsEffectiveAmount($vadsEffectiveAmount)
    {
        $this->vadsEffectiveAmount = $vadsEffectiveAmount;

        return $this;
    }

    /**
     * Get vadsEffectiveAmount
     *
     * @return string
     */
    public function getVadsEffectiveAmount()
    {
        return $this->vadsEffectiveAmount;
    }

    /**
     * Set vadsRefundAmount
     *
     * @param string $vadsRefundAmount
     *
     * @return Payment
     */
    public function setVadsRefundAmount($vadsRefundAmount)
    {
        $this->vadsRefundAmount = $vadsRefundAmount;

        return $this;
    }

    /**
     * Get vadsRefundAmount
     *
     * @return string
     */
    public function getVadsRefundAmount()
    {
        return $this->vadsRefundAmount;
    }

    /**
     * Set vadsPaymentType
     *
     * @param string $vadsPaymentType
     *
     * @return Payment
     */
    public function setVadsPaymentType($vadsPaymentType)
    {
        $this->vadsPaymentType = $vadsPaymentType;

        return $this;
    }

    /**
     * Get vadsPaymentType
     *
     * @return string
     */
    public function getVadsPaymentType()
    {
        return $this->vadsPaymentType;
    }

    /**
     * Set vadsOperationType
     *
     * @param string $vadsOperationType
     *
     * @return Payment
     */
    public function setVadsOperationType($vadsOperationType)
    {
        $this->vadsOperationType = $vadsOperationType;

        return $this;
    }

    /**
     * Get vadsOperationType
     *
     * @return string
     */
    public function getVadsOperationType()
    {
        return $this->vadsOperationType;
    }

    /**
     * Set vadsCurrency
     *
     * @param string $vadsCurrency
     *
     * @return Payment
     */
    public function setVadsCurrency($vadsCurrency)
    {
        $this->vadsCurrency = $vadsCurrency;

        return $this;
    }

    /**
     * Get vadsCurrency
     *
     * @return string
     */
    public function getVadsCurrency()
    {
        return $this->vadsCurrency;
    }
}
