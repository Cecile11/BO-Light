<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ipn
 *
 * @ORM\Table(name="ipn")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IpnRepository")
 */
class Ipn
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
     * @var \DateTime
     *
     * @ORM\Column(name="ts", type="datetime")
     */
    private $ts;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_site_id", type="string", length=255,nullable=true)
     */
    private $vadsSiteId;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_url_check_src", type="string", length=255,nullable=true)
     */
    private $vadsUrlCheckSrc;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_payment_src", type="string", length=255,nullable=true)
     */
    private $vadsPaymentSrc;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_shop_name", type="string", length=255,nullable=true)
     */
    private $vadsShopName;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_ctx_mode", type="string", length=255,nullable=true)
     */
    private $vadsCtxMode;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_trans_uuid", type="string", length=255,nullable=true)
     */
    private $vadsTransUuid;

    /**
     * @var strng
     *
     * @ORM\Column(name="vads_trans_id", type="string", length=255,nullable=true)
     */
    private $vadsTransId;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_order_id", type="string", length=255,nullable=true)
     */
    private $vadsOrderId;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_order_info", type="string", length=255,nullable=true)
     */
    private $vadsOrderInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_payment_config", type="string", length=255,nullable=true)
     */
    private $vadsPaymentConfig;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vads_effective_creation_date", type="datetime",nullable=true)
     */
    private $vadsEffectiveCreationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_operation_type", type="string", length=255,nullable=true)
     */
    private $vadsOperationType;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_trans_status", type="string", length=255,nullable=true)
     */
    private $vadsTransStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_result", type="string", length=255,nullable=true)
     */
    private $vadsResult;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_extra_result", type="string", length=255,nullable=true)
     */
    private $vadsExtraResult;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_effective_amount", type="string", length=255,nullable=true)
     */
    private $vadsEffectiveAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_currency", type="string", length=255,nullable=true)
     */
    private $vadsCurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_contract_used", type="string", length=255,nullable=true)
     */
    private $vadsContractUsed;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_auth_mode", type="string", length=255,nullable=true)
     */
    private $vadsAuthMode;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_card_brand", type="string", length=255,nullable=true)
     */
    private $vadsCardBrand;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_card_number", type="string", length=255,nullable=true)
     */
    private $vadsCardNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_payment_seq", type="blob",nullable=true)
     */
    private $vadsPaymentSeq;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_cust_email", type="string", length=255,nullable=true)
     */
    private $vadsCustEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_capture_delay", type="string", length=255,nullable=true)
     */
    private $vadsCaptureDelay;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vads_presentation_date", type="datetime",nullable=true)
     */
    private $vadsPresentationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_warranty_result", type="string", length=255,nullable=true)
     */
    private $vadsWarrantyResult;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_risk_control", type="string", length=255,nullable=true)
     */
    private $vadsRiskControl;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_validation_mode", type="string", length=255,nullable=true)
     */
    private $vadsValidationMode;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_recurrence_status", type="string", length=255,nullable=true)
     */
    private $vadsRecurrenceStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_identifier_status", type="string", length=255,nullable=true)
     */
    private $vadsIdentifierStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_identifier", type="string", length=255,nullable=true)
     */
    private $vadsIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_subscription", type="string", length=255,nullable=true)
     */
    private $vadsSubscription;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_sub_desc", type="string", length=255,nullable=true)
     */
    private $vadsSubDesc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vads_sub_effect_date", type="date",nullable=true,nullable=true)
     */
    private $vadsSubEffectDate;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_sub_currency", type="string", length=255,nullable=true)
     */
    private $vadsSubCurrency;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_sub_amount", type="string", length=255,nullable=true)
     */
    private $vadsSubAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_sub_init_amount_number", type="string", length=255,nullable=true)
     */
    private $vadsSubInitAmountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_sub_init_amount", type="string", length=255,nullable=true)
     */
    private $vadsSubInitAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_contrib", type="string", length=255,nullable=true)
     */
    private $vadsContrib;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_ext_info_donation", type="string", length=255,nullable=true)
     */
    private $vadsExtInfoDonation;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_ext_info_donation_recipient", type="string", length=255,nullable=true)
     */
    private $vadsExtInfoDonationRecipient;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_ext_info_donation_recipient_name", type="string", length=255,nullable=true)
     */
    private $vadsExtInfoDonationRecipientName;

    /**
     * @var string
     *
     * @ORM\Column(name="vads_ext_info_donation_merchant", type="string", length=255,nullable=true)
     */
    private $vadsExtInfoDonationMerchant;

    /**
     * @var string
     *
     * @ORM\Column(name="full", type="blob",nullable=true)
     */
    private $full;

    /**
     * @var string
     *
     * @ORM\Column(name="signature", type="string", length=255,nullable=true)
     */
    private $signature;

    /**
     * @var string
     *
     * @ORM\Column(name="checked", type="string", length=255,nullable=true)
     */
    private $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_client",type="integer", nullable=true)
     */
    private $idClient;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ts
     *
     * @param \DateTime $ts
     * @return Ipn
     */
    public function setTs($ts)
    {
        $this->ts = $ts;

        return $this;
    }

    /**
     * Get ts
     *
     * @return \DateTime 
     */
    public function getTs()
    {
        return $this->ts;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Ipn
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set vadsSiteId
     *
     * @param string $vadsSiteId
     * @return Ipn
     */
    public function setVadsSiteId($vadsSiteId)
    {
        $this->vadsSiteId = $vadsSiteId;

        return $this;
    }

    /**
     * Get vadsSiteId
     *
     * @return string 
     */
    public function getVadsSiteId()
    {
        return $this->vadsSiteId;
    }

    /**
     * Set vadsUrlCheckSrc
     *
     * @param string $vadsUrlCheckSrc
     * @return Ipn
     */
    public function setVadsUrlCheckSrc($vadsUrlCheckSrc)
    {
        $this->vadsUrlCheckSrc = $vadsUrlCheckSrc;

        return $this;
    }

    /**
     * Get vadsUrlCheckSrc
     *
     * @return string 
     */
    public function getVadsUrlCheckSrc()
    {
        return $this->vadsUrlCheckSrc;
    }

    /**
     * Set vadsPaymentSrc
     *
     * @param string $vadsPaymentSrc
     * @return Ipn
     */
    public function setVadsPaymentSrc($vadsPaymentSrc)
    {
        $this->vadsPaymentSrc = $vadsPaymentSrc;

        return $this;
    }

    /**
     * Get vadsPaymentSrc
     *
     * @return string 
     */
    public function getVadsPaymentSrc()
    {
        return $this->vadsPaymentSrc;
    }

    /**
     * Set vadsShopName
     *
     * @param string $vadsShopName
     * @return Ipn
     */
    public function setVadsShopName($vadsShopName)
    {
        $this->vadsShopName = $vadsShopName;

        return $this;
    }

    /**
     * Get vadsShopName
     *
     * @return string 
     */
    public function getVadsShopName()
    {
        return $this->vadsShopName;
    }

    /**
     * Set vadsCtxMode
     *
     * @param string $vadsCtxMode
     * @return Ipn
     */
    public function setVadsCtxMode($vadsCtxMode)
    {
        $this->vadsCtxMode = $vadsCtxMode;

        return $this;
    }

    /**
     * Get vadsCtxMode
     *
     * @return string 
     */
    public function getVadsCtxMode()
    {
        return $this->vadsCtxMode;
    }

    /**
     * Set vadsTransUuid
     *
     * @param string $vadsTransUuid
     * @return Ipn
     */
    public function setVadsTransUuid($vadsTransUuid)
    {
        $this->vadsTransUuid = $vadsTransUuid;

        return $this;
    }

    /**
     * Get vadsTransUuid
     *
     * @return string 
     */
    public function getVadsTransUuid()
    {
        return $this->vadsTransUuid;
    }

    /**
     * Set vadsTransId
     *
     * @param integer $vadsTransId
     * @return Ipn
     */
    public function setVadsTransId($vadsTransId)
    {
        $this->vadsTransId = $vadsTransId;

        return $this;
    }

    /**
     * Get vadsTransId
     *
     * @return integer 
     */
    public function getVadsTransId()
    {
        return $this->vadsTransId;
    }

    /**
     * Set vadsOrderId
     *
     * @param string $vadsOrderId
     * @return Ipn
     */
    public function setVadsOrderId($vadsOrderId)
    {
        $this->vadsOrderId = $vadsOrderId;

        return $this;
    }

    /**
     * Get vadsOrderId
     *
     * @return string 
     */
    public function getVadsOrderId()
    {
        return $this->vadsOrderId;
    }

    /**
     * Set vadsOrderInfo
     *
     * @param string $vadsOrderInfo
     * @return Ipn
     */
    public function setVadsOrderInfo($vadsOrderInfo)
    {
        $this->vadsOrderInfo = $vadsOrderInfo;

        return $this;
    }

    /**
     * Get vadsOrderInfo
     *
     * @return string 
     */
    public function getVadsOrderInfo()
    {
        return $this->vadsOrderInfo;
    }

    /**
     * Set vadsPaymentConfig
     *
     * @param string $vadsPaymentConfig
     * @return Ipn
     */
    public function setVadsPaymentConfig($vadsPaymentConfig)
    {
        $this->vadsPaymentConfig = $vadsPaymentConfig;

        return $this;
    }

    /**
     * Get vadsPaymentConfig
     *
     * @return string 
     */
    public function getVadsPaymentConfig()
    {
        return $this->vadsPaymentConfig;
    }

    /**
     * Set vadsEffectiveCreationDate
     *
     * @param \DateTime $vadsEffectiveCreationDate
     * @return Ipn
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
     * Set vadsOperationType
     *
     * @param string $vadsOperationType
     * @return Ipn
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
     * Set vadsTransStatus
     *
     * @param string $vadsTransStatus
     * @return Ipn
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
     * Set vadsResult
     *
     * @param string $vadsResult
     * @return Ipn
     */
    public function setVadsResult($vadsResult)
    {
        $this->vadsResult = $vadsResult;

        return $this;
    }

    /**
     * Get vadsResult
     *
     * @return string 
     */
    public function getVadsResult()
    {
        return $this->vadsResult;
    }

    /**
     * Set vadsExtraResult
     *
     * @param string $vadsExtraResult
     * @return Ipn
     */
    public function setVadsExtraResult($vadsExtraResult)
    {
        $this->vadsExtraResult = $vadsExtraResult;

        return $this;
    }

    /**
     * Get vadsExtraResult
     *
     * @return string 
     */
    public function getVadsExtraResult()
    {
        return $this->vadsExtraResult;
    }

    /**
     * Set vadsEffectiveAmount
     *
     * @param string $vadsEffectiveAmount
     * @return Ipn
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
     * Set vadsCurrency
     *
     * @param string $vadsCurrency
     * @return Ipn
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

    /**
     * Set vadsContractUsed
     *
     * @param string $vadsContractUsed
     * @return Ipn
     */
    public function setVadsContractUsed($vadsContractUsed)
    {
        $this->vadsContractUsed = $vadsContractUsed;

        return $this;
    }

    /**
     * Get vadsContractUsed
     *
     * @return string 
     */
    public function getVadsContractUsed()
    {
        return $this->vadsContractUsed;
    }

    /**
     * Set vadsAuthMode
     *
     * @param string $vadsAuthMode
     * @return Ipn
     */
    public function setVadsAuthMode($vadsAuthMode)
    {
        $this->vadsAuthMode = $vadsAuthMode;

        return $this;
    }

    /**
     * Get vadsAuthMode
     *
     * @return string 
     */
    public function getVadsAuthMode()
    {
        return $this->vadsAuthMode;
    }

    /**
     * Set vadsCardBrand
     *
     * @param string $vadsCardBrand
     * @return Ipn
     */
    public function setVadsCardBrand($vadsCardBrand)
    {
        $this->vadsCardBrand = $vadsCardBrand;

        return $this;
    }

    /**
     * Get vadsCardBrand
     *
     * @return string 
     */
    public function getVadsCardBrand()
    {
        return $this->vadsCardBrand;
    }

    /**
     * Set vadsCardNumber
     *
     * @param string $vadsCardNumber
     * @return Ipn
     */
    public function setVadsCardNumber($vadsCardNumber)
    {
        $this->vadsCardNumber = $vadsCardNumber;

        return $this;
    }

    /**
     * Get vadsCardNumber
     *
     * @return string 
     */
    public function getVadsCardNumber()
    {
        return $this->vadsCardNumber;
    }

    /**
     * Set vadsPaymentSeq
     *
     * @param string $vadsPaymentSeq
     * @return Ipn
     */
    public function setVadsPaymentSeq($vadsPaymentSeq)
    {
        $this->vadsPaymentSeq = $vadsPaymentSeq;

        return $this;
    }

    /**
     * Get vadsPaymentSeq
     *
     * @return string 
     */
    public function getVadsPaymentSeq()
    {
        return $this->vadsPaymentSeq;
    }

    /**
     * Set vadsCustEmail
     *
     * @param string $vadsCustEmail
     * @return Ipn
     */
    public function setVadsCustEmail($vadsCustEmail)
    {
        $this->vadsCustEmail = $vadsCustEmail;

        return $this;
    }

    /**
     * Get vadsCustEmail
     *
     * @return string 
     */
    public function getVadsCustEmail()
    {
        return $this->vadsCustEmail;
    }

    /**
     * Set vadsCaptureDelay
     *
     * @param string $vadsCaptureDelay
     * @return Ipn
     */
    public function setVadsCaptureDelay($vadsCaptureDelay)
    {
        $this->vadsCaptureDelay = $vadsCaptureDelay;

        return $this;
    }

    /**
     * Get vadsCaptureDelay
     *
     * @return string 
     */
    public function getVadsCaptureDelay()
    {
        return $this->vadsCaptureDelay;
    }

    /**
     * Set vadsPresentationDate
     *
     * @param \DateTime $vadsPresentationDate
     * @return Ipn
     */
    public function setVadsPresentationDate($vadsPresentationDate)
    {
        $this->vadsPresentationDate = $vadsPresentationDate;

        return $this;
    }

    /**
     * Get vadsPresentationDate
     *
     * @return \DateTime 
     */
    public function getVadsPresentationDate()
    {
        return $this->vadsPresentationDate;
    }

    /**
     * Set vadsWarrantyResult
     *
     * @param string $vadsWarrantyResult
     * @return Ipn
     */
    public function setVadsWarrantyResult($vadsWarrantyResult)
    {
        $this->vadsWarrantyResult = $vadsWarrantyResult;

        return $this;
    }

    /**
     * Get vadsWarrantyResult
     *
     * @return string 
     */
    public function getVadsWarrantyResult()
    {
        return $this->vadsWarrantyResult;
    }

    /**
     * Set vadsRiskControl
     *
     * @param string $vadsRiskControl
     * @return Ipn
     */
    public function setVadsRiskControl($vadsRiskControl)
    {
        $this->vadsRiskControl = $vadsRiskControl;

        return $this;
    }

    /**
     * Get vadsRiskControl
     *
     * @return string 
     */
    public function getVadsRiskControl()
    {
        return $this->vadsRiskControl;
    }

    /**
     * Set vadsValidationMode
     *
     * @param string $vadsValidationMode
     * @return Ipn
     */
    public function setVadsValidationMode($vadsValidationMode)
    {
        $this->vadsValidationMode = $vadsValidationMode;

        return $this;
    }

    /**
     * Get vadsValidationMode
     *
     * @return string 
     */
    public function getVadsValidationMode()
    {
        return $this->vadsValidationMode;
    }

    /**
     * Set vadsRecurrenceStatus
     *
     * @param string $vadsRecurrenceStatus
     * @return Ipn
     */
    public function setVadsRecurrenceStatus($vadsRecurrenceStatus)
    {
        $this->vadsRecurrenceStatus = $vadsRecurrenceStatus;

        return $this;
    }

    /**
     * Get vadsRecurrenceStatus
     *
     * @return string 
     */
    public function getVadsRecurrenceStatus()
    {
        return $this->vadsRecurrenceStatus;
    }

    /**
     * Set vadsIdentifierStatus
     *
     * @param string $vadsIdentifierStatus
     * @return Ipn
     */
    public function setVadsIdentifierStatus($vadsIdentifierStatus)
    {
        $this->vadsIdentifierStatus = $vadsIdentifierStatus;

        return $this;
    }

    /**
     * Get vadsIdentifierStatus
     *
     * @return string 
     */
    public function getVadsIdentifierStatus()
    {
        return $this->vadsIdentifierStatus;
    }

    /**
     * Set vadsIdentifier
     *
     * @param string $vadsIdentifier
     * @return Ipn
     */
    public function setVadsIdentifier($vadsIdentifier)
    {
        $this->vadsIdentifier = $vadsIdentifier;

        return $this;
    }

    /**
     * Get vadsIdentifier
     *
     * @return string 
     */
    public function getVadsIdentifier()
    {
        return $this->vadsIdentifier;
    }

    /**
     * Set vadsSubscription
     *
     * @param string $vadsSubscription
     * @return Ipn
     */
    public function setVadsSubscription($vadsSubscription)
    {
        $this->vadsSubscription = $vadsSubscription;

        return $this;
    }

    /**
     * Get vadsSubscription
     *
     * @return string 
     */
    public function getVadsSubscription()
    {
        return $this->vadsSubscription;
    }

    /**
     * Set vadsSubDesc
     *
     * @param string $vadsSubDesc
     * @return Ipn
     */
    public function setVadsSubDesc($vadsSubDesc)
    {
        $this->vadsSubDesc = $vadsSubDesc;

        return $this;
    }

    /**
     * Get vadsSubDesc
     *
     * @return string 
     */
    public function getVadsSubDesc()
    {
        return $this->vadsSubDesc;
    }

    /**
     * Set vadsSubEffectDate
     *
     * @param \DateTime $vadsSubEffectDate
     * @return Ipn
     */
    public function setVadsSubEffectDate($vadsSubEffectDate)
    {
        $this->vadsSubEffectDate = $vadsSubEffectDate;

        return $this;
    }

    /**
     * Get vadsSubEffectDate
     *
     * @return \DateTime 
     */
    public function getVadsSubEffectDate()
    {
        return $this->vadsSubEffectDate;
    }

    /**
     * Set vadsSubCurrency
     *
     * @param string $vadsSubCurrency
     * @return Ipn
     */
    public function setVadsSubCurrency($vadsSubCurrency)
    {
        $this->vadsSubCurrency = $vadsSubCurrency;

        return $this;
    }

    /**
     * Get vadsSubCurrency
     *
     * @return string 
     */
    public function getVadsSubCurrency()
    {
        return $this->vadsSubCurrency;
    }

    /**
     * Set vadsSubAmount
     *
     * @param string $vadsSubAmount
     * @return Ipn
     */
    public function setVadsSubAmount($vadsSubAmount)
    {
        $this->vadsSubAmount = $vadsSubAmount;

        return $this;
    }

    /**
     * Get vadsSubAmount
     *
     * @return string 
     */
    public function getVadsSubAmount()
    {
        return $this->vadsSubAmount;
    }

    /**
     * Set vadsSubInitAmountNumber
     *
     * @param string $vadsSubInitAmountNumber
     * @return Ipn
     */
    public function setVadsSubInitAmountNumber($vadsSubInitAmountNumber)
    {
        $this->vadsSubInitAmountNumber = $vadsSubInitAmountNumber;

        return $this;
    }

    /**
     * Get vadsSubInitAmountNumber
     *
     * @return string 
     */
    public function getVadsSubInitAmountNumber()
    {
        return $this->vadsSubInitAmountNumber;
    }

    /**
     * Set vadsSubInitAmount
     *
     * @param string $vadsSubInitAmount
     * @return Ipn
     */
    public function setVadsSubInitAmount($vadsSubInitAmount)
    {
        $this->vadsSubInitAmount = $vadsSubInitAmount;

        return $this;
    }

    /**
     * Get vadsSubInitAmount
     *
     * @return string 
     */
    public function getVadsSubInitAmount()
    {
        return $this->vadsSubInitAmount;
    }

    /**
     * Set vadsContrib
     *
     * @param string $vadsContrib
     * @return Ipn
     */
    public function setVadsContrib($vadsContrib)
    {
        $this->vadsContrib = $vadsContrib;

        return $this;
    }

    /**
     * Get vadsContrib
     *
     * @return string 
     */
    public function getVadsContrib()
    {
        return $this->vadsContrib;
    }

    /**
     * Set vadsExtInfoDonation
     *
     * @param string $vadsExtInfoDonation
     * @return Ipn
     */
    public function setVadsExtInfoDonation($vadsExtInfoDonation)
    {
        $this->vadsExtInfoDonation = $vadsExtInfoDonation;

        return $this;
    }

    /**
     * Get vadsExtInfoDonation
     *
     * @return string 
     */
    public function getVadsExtInfoDonation()
    {
        return $this->vadsExtInfoDonation;
    }

    /**
     * Set vadsExtInfoDonationRecipient
     *
     * @param string $vadsExtInfoDonationRecipient
     * @return Ipn
     */
    public function setVadsExtInfoDonationRecipient($vadsExtInfoDonationRecipient)
    {
        $this->vadsExtInfoDonationRecipient = $vadsExtInfoDonationRecipient;

        return $this;
    }

    /**
     * Get vadsExtInfoDonationRecipient
     *
     * @return string 
     */
    public function getVadsExtInfoDonationRecipient()
    {
        return $this->vadsExtInfoDonationRecipient;
    }

    /**
     * Set vadsExtInfoDonationRecipientName
     *
     * @param string $vadsExtInfoDonationRecipientName
     * @return Ipn
     */
    public function setVadsExtInfoDonationRecipientName($vadsExtInfoDonationRecipientName)
    {
        $this->vadsExtInfoDonationRecipientName = $vadsExtInfoDonationRecipientName;

        return $this;
    }

    /**
     * Get vadsExtInfoDonationRecipientName
     *
     * @return string 
     */
    public function getVadsExtInfoDonationRecipientName()
    {
        return $this->vadsExtInfoDonationRecipientName;
    }

    /**
     * Set vadsExtInfoDonationMerchant
     *
     * @param string $vadsExtInfoDonationMerchant
     * @return Ipn
     */
    public function setVadsExtInfoDonationMerchant($vadsExtInfoDonationMerchant)
    {
        $this->vadsExtInfoDonationMerchant = $vadsExtInfoDonationMerchant;

        return $this;
    }

    /**
     * Get vadsExtInfoDonationMerchant
     *
     * @return string 
     */
    public function getVadsExtInfoDonationMerchant()
    {
        return $this->vadsExtInfoDonationMerchant;
    }

    /**
     * Set full
     *
     * @param string $full
     * @return Ipn
     */
    public function setFull($full)
    {
        $this->full = $full;

        return $this;
    }

    /**
     * Get full
     *
     * @return string 
     */
    public function getFull()
    {
        return str_replace(',','<br>',stream_get_contents($this->full));
    }

    /**
     * Set signature
     *
     * @param string $signature
     * @return Ipn
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string 
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set checked
     *
     * @param string $checked
     * @return Ipn
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return string 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set idClient
     *
     * @param integer $idClient
     *
     * @return Ipn
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Get idClient
     *
     * @return integer
     */
    public function getIdClient()
    {
        return $this->idClient;
    }
}
