<?php
namespace CoreBundle\PayzenWSv5;
use CoreBundle\Entity\Payment;
use DateTime;
use DateTimeZone;
use SoapFault;
use Exception;

class Loader{

	private $payzenWS;
	private $em;

	public function __construct(\Doctrine\ORM\EntityManager $em,\CoreBundle\PayzenWSv5\PayzenWSv5 $payzenWS){

		$this->em = $em;
		$this->payzenWS = $payzenWS;
	}

	public function loadPayment($uuid){
		$mode = "inter";
        try {
            $response = $this->payzenWS->getPaymentDetails($uuid);
        }
        catch (Exception $e) {
            spip_log("getPaymentDetailsResult : erreur ".$e->getMessage(),$mode."_LOG");
            return false;
        }catch(SoapFault $ex){
            return false;
        }
        if ($e = $response->getPaymentDetailsResult->commonResponse->responseCode){
            spip_log($s="getPaymentDetailsResult $uuid : erreur $e : ".$response->getPaymentDetailsResult->commonResponse->responseCodeDetail,$mode."_LOG_RESP");
            return false;
        }else{
            $data = $response->getPaymentDetailsResult;
            $payment = new Payment();
            $payment->setUuid($uuid);
            $payment->setData($data);
            $payment->setVadsCustId($data->customerResponse->billingDetails->reference);
            $payment->setVadsCustFirstName($data->customerResponse->billingDetails->firstName);
            $payment->setVadsCustLastName($data->customerResponse->billingDetails->lastName);
            $payment->setVadsTransStatus($data->commonResponse->transactionStatusLabel);
            $date = DateTime::createFromFormat(DateTime::W3C,$data->paymentResponse->creationDate,new DateTimeZone('Europe/Paris'));
            $date->setTimezone(new DateTimeZone('UTC'));
            $payment->setVadsEffectiveCreationDate($date);
            $payment->setVadsEffectiveAmount($data->paymentResponse->amount);
            $payment->setVadsRefundAmount(isset($data->captureResponse->refundAmount) ? $data->captureResponse->refundAmount : 0);
            $payment->setVadsPaymentType($data->paymentResponse->paymentType);
            $payment->setVadsOperationType($data->paymentResponse->operationType);
            $payment->setVadsCurrency($data->paymentResponse->currency);
            $this->em->persist($payment);
            $this->em->flush();
            return true;
		}
	}

}