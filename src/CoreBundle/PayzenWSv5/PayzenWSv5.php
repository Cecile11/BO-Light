<?php
/**
 * StandardWS class
 *
 * @author    
 *  Cedric Morin, Nursit.com
 * @copyright 
 * 
 */
namespace CoreBundle\PayzenWSv5;

use SoapClient;
use DateTime;
use DateTimeZone;
use SoapHeader;
use DOMDocument;
use DOMXPath;

include_once(__DIR__.'/functions.php');

class PayzenWSv5 extends SoapClient {
	protected $header_namespace;
	private $config = null;
	private $test;
	private static $classmap = array(
	);
	public function __construct(
		$config,
		$wsdl = "https://secure.payzen.eu/vads-ws/v5?wsdl",
		$namespace_url = "http://v5.ws.vads.lyra.com/Header/",
		$options = array('trace' => 1, 'encoding' => 'UTF-8')){
		$this->config = $config->getConfig();
		$this->header_namespace = $namespace_url;
		foreach (self::$classmap as $key => $value){
			if (!isset($options['classmap'][$key])){
				$options['classmap'][$key] = $value;
			}
		}
		$this->test = 1;
		parent::__construct($wsdl, $options);
	}
	/**
	 * @return string
	 */
	public function gen_uuid(){
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}
	/**
	 *
	 */
	public function buildAuthHeader(){
		$this->__setSoapHeaders();
		$headers = array();
		$uuid = $this->gen_uuid();
		$timestamp = gmdate("Y-m-d\TH:i:s\Z");
		$key = $this->config['CLE'];
		$headers[] = new SoapHeader($this->header_namespace,"shopId",$this->config['SITE_ID']);
		$headers[] = new SoapHeader($this->header_namespace,"requestId",$uuid);
		$headers[] = new SoapHeader($this->header_namespace,"timestamp",$timestamp);
		$headers[] = new SoapHeader($this->header_namespace,"mode",'PRODUCTION');
		$headers[] = new SoapHeader($this->header_namespace,"authToken",base64_encode(hash_hmac("sha256", $uuid.$timestamp, $key, true)));
		$this->__setSoapHeaders($headers);
	}
	/**
	 * @param $method
	 * @param $args
	 * @return bool|mixed
	 */
	protected function call_ws($method,$args){
		$this->buildAuthHeader();
		$response = $this->__soapCall($method, $args);
		/* logs XML*/
		spip_log("[Request Header]\n".htmlspecialchars($this->__getLastRequestHeaders()),"payzen_ws".LOG_DEBUG);
		spip_log("[Request]\n".htmlspecialchars($this->__getLastRequest()),"payzen_ws".LOG_DEBUG);
		spip_log("[Response Header]\n".htmlspecialchars($this->__getLastResponseHeaders()),"payzen_ws".LOG_DEBUG);
		spip_log("[Response]\n".htmlspecialchars($this->__getLastResponse()),"payzen_ws".LOG_DEBUG);
		//Analyse de la répons
		//Récupération du SOAP Header de la réponse afin de stocker les en-têtes dans un tableau
		// (ici $responseHeader)
		$dom = new DOMDocument;
		$dom->loadXML($this->__getLastResponse(), LIBXML_NOWARNING);
		$path = new DOMXPath($dom);
		$headers = $path->query('//*[local-name()="Header"]/*');
		$responseHeader = array();
		foreach ($headers as $headerItem){
			$responseHeader[$headerItem->nodeName] = $headerItem->nodeValue;
		}
		//Calcul du jeton d'authentification de la réponse
		$authTokenResponse = base64_encode(hash_hmac('sha256', $responseHeader['timestamp'] . $responseHeader['requestId'], payzen_key($this->config), true));
		if ($authTokenResponse!==$responseHeader['authToken']){
			//Erreur de calcul ou tentative de fraude
			spip_log("call_ws:$method: Erreur signature reponse","payzen_ws".LOG_ERR);
			return false;
		}
		return $response;
	}

/**
 *
 *
 */


	/**
	 * @param $paymentToken
	 * @return bool|mixed
	 */
	public function cancelToken($paymentToken){
		//Génération du body
		$commonRequest = new commonRequest();
		$commonRequest->submissionDate = new DateTime('now', new DateTimeZone('UTC'));
		$queryRequest = new queryRequest();
		$queryRequest->paymentToken = $paymentToken;
		$cancelToken = new cancelToken();
		$cancelToken->commonRequest = $commonRequest;
		$cancelToken->queryRequest = $queryRequest;
		return $this->call_ws('cancelToken', array($cancelToken));
	}

	/**
	 * @param $uuid
	 * @return bool|mixed
	 */
	public function createTokenFromTransaction($uuid){
		//Génération du body
		$commonRequest = new commonRequest();
		$commonRequest->submissionDate = new DateTime('now', new DateTimeZone('UTC'));
		$queryRequest = new queryRequest();
		$queryRequest->uuid = $uuid;
		$createTokenFromTransaction = new createTokenFromTransaction();
		$createTokenFromTransaction->commonRequest = $commonRequest;
		$createTokenFromTransaction->queryRequest = $queryRequest;
		return $this->call_ws('createTokenFromTransaction', array($createTokenFromTransaction));
	}

	/**
	 * @param $paymentToken
	 * @return bool|mixed
	 */
	public function reactivateToken($paymentToken){
		//Génération du body
		$queryRequest = new queryRequest();
		$queryRequest->paymentToken = $paymentToken;
		$reactivateToken = new reactivateToken();
		$reactivateToken->queryRequest = $queryRequest;
		return $this->call_ws('reactivateToken', array($reactivateToken));
	}

	/**
	 * @param $paymentToken
	 * @return bool|mixed
	 */
	public function getTokenDetails($paymentToken){
		//Génération du body
		$queryRequest = new queryRequest();
		$queryRequest->paymentToken = $paymentToken;
		$getTokenDetails = new getTokenDetails();
		$getTokenDetails->queryRequest = $queryRequest;
		return $this->call_ws('getTokenDetails', array($getTokenDetails));
	}



	/**
	 * @param $paymentToken
	 * @param $subscriptionId
	 * @return bool|mixed
	 */
	public function cancelSubscription($paymentToken, $subscriptionId){
		//Génération du body
		$commonRequest = new commonRequest();
		$commonRequest->submissionDate = new DateTime('now', new DateTimeZone('UTC'));
		$queryRequest = new queryRequest();
		$queryRequest->paymentToken = $paymentToken;
		$queryRequest->subscriptionId = $subscriptionId;
		$cancelSubscription = new cancelSubscription();
		$cancelSubscription->commonRequest = $commonRequest;
		$cancelSubscription->queryRequest = $queryRequest;
		return $this->call_ws('cancelSubscription', array($cancelSubscription));
	}

	public function createSubscription($order, $subscription, $card){
		//Génération du body
		$commonRequest = new commonRequest();
		$commonRequest->submissionDate = new DateTime('now', new DateTimeZone('UTC'));
		$orderRequest = new orderRequest();
		$orderRequest->orderId = $order['orderId'];
		$orderRequest->extInfo = $order['extInfo'];
		$subscriptionRequest = new subscriptionRequest();
		$subscriptionRequest->effectDate = $subscription['effectDate'];
		$subscriptionRequest->amount = $subscription['amount'];
		$subscriptionRequest->currency = $subscription['currency'];
		$subscriptionRequest->initialAmount = $subscription['initialAmount'];
		$subscriptionRequest->initialAmountNumber = $subscription['initialAmountNumber'];
		$subscriptionRequest->rrule = $subscription['rrule'];
		$subscriptionRequest->subscriptionId = $subscription['subscriptionId'];
		$subscriptionRequest->description = $subscription['description'];
		$cardRequest = new cardRequest();
		$cardRequest->paymentToken = $card['paymentToken'];
		$createSubscription = new createSubscription();
		$createSubscription->commonRequest = $commonRequest;
		$createSubscription->orderRequest = $orderRequest;
		$createSubscription->subscriptionRequest = $subscriptionRequest;
		$createSubscription->cardRequest = $cardRequest;
		return $this->call_ws('createSubscription', array($createSubscription));
	}

	public function getPaymentDetails($uuid){
		$queryRequest = new queryRequest();
		$queryRequest->uuid = $uuid;
		$getPaymentDetails = new getTokenDetails();
		$getPaymentDetails->queryRequest = $queryRequest;
		return $this->call_ws('getPaymentDetails', array($getPaymentDetails));
	}


}

/**
 *
 *
 */

class commonRequest {
	public $paymentSource; // string
	public $submissionDate; // dateTime
	public $contractNumber; // string
	public $comment; // string
}
class commonResponse {
	public $responseCode; // int
	public $responseCodeDetail; // string
	public $transactionStatusLabel; // string
	public $shopId; // string
	public $paymentSource; // string
	public $submissionDate; // dateTime
	public $contractNumber; // string
	public $paymentToken; // string
}
class cardRequest {
	public $number; // string
	public $scheme; // string
	public $expiryMonth; // int
	public $expiryYear; // int
	public $cardSecurityCode; // string
	public $cardHolderBirthDay; // dateTime
	public $paymentToken; // string
}
class customerRequest {
	public $billingDetails; // billingDetailsRequest
	public $shippingDetails; // shippingDetailsRequest
	public $extraDetails; // extraDetailsRequest
}
class billingDetailsRequest {
	public $reference; // string
	public $title; // string
	public $type; // custStatus
	public $firstName; // string
	public $lastName; // string
	public $phoneNumber; // string
	public $email; // string
	public $streetNumber; // string
	public $address; // string
	public $district; // string
	public $zipCode; // string
	public $city; // string
	public $state; // string
	public $country; // string
	public $language; // string
	public $cellPhoneNumber; // string
	public $legalName; // string
}
class shippingDetailsRequest {
	public $type; // custStatus
	public $firstName; // string
	public $lastName; // string
	public $phoneNumber; // string
	public $streetNumber; // string
	public $address; // string
	public $address2; // string
	public $district; // string
	public $zipCode; // string
	public $city; // string
	public $state; // string
	public $country; // string
	public $deliveryCompanyName; // string
	public $shippingSpeed; // deliverySpeed
	public $shippingMethod; // deliveryType
	public $legalName; // string
}
class extraDetailsRequest {
	public $ipAddress; // string
}
class paymentRequest {
	public $transactionId; // string
	public $amount; // long
	public $currency; // int
	public $expectedCaptureDate; // dateTime
	public $manualValidation; // int
	public $paymentOptionCode; // string
}
class paymentResponse {
	public $transactionId; // string
	public $amount; // long
	public $currency; // int
	public $effectiveAmount; // long
	public $effectiveCurrency; // int
	public $expectedCaptureDate; // dateTime
	public $manualValidation; // int
	public $operationType; // int
	public $creationDate; // dateTime
	public $externalTransactionId; // string
	public $liabilityShift; // string
	public $transactionUuid; // string
}
class orderResponse {
	public $orderId; // string
	public $extInfo; // extInfo
}
class extInfo {
	public $key; // string
	public $value; // string
}
class cardResponse {
	public $number; // string
	public $scheme; // string
	public $brand; // string
	public $country; // string
	public $productCode; // string
	public $bankCode; // string
	public $expiryMonth; // int
	public $expiryYear; // int
}
class authorizationResponse {
	public $mode; // string
	public $amount; // long
	public $currency; // int
	public $date; // dateTime
	public $number; // string
	public $result; // int
}
class captureResponse {
	public $date; // dateTime
	public $number; // int
	public $reconciliationStatus; // int
	public $refundAmount; // long
	public $refundCurrency; // int
}
class customerResponse {
	public $billingDetails; // billingDetailsResponse
	public $shippingDetails; // shippingDetailsResponse
	public $extraDetails; // extraDetailsResponse
}
class billingDetailsResponse {
	public $reference; // string
	public $title; // string
	public $type; // custStatus
	public $firstName; // string
	public $lastName; // string
	public $phoneNumber; // string
	public $email; // string
	public $streetNumber; // string
	public $address; // string
	public $district; // string
	public $zipCode; // string
	public $city; // string
	public $state; // string
	public $country; // string
	public $language; // string
	public $cellPhoneNumber; // string
	public $legalName; // string
}
class shippingDetailsResponse {
	public $type; // custStatus
	public $firstName; // string
	public $lastName; // string
	public $phoneNumber; // string
	public $streetNumber; // string
	public $address; // string
	public $address2; // string
	public $district; // string
	public $zipCode; // string
	public $city; // string
	public $state; // string
	public $country; // string
	public $deliveryCompanyName; // string
	public $shippingSpeed; // deliverySpeed
	public $shippingMethod; // deliveryType
	public $legalName; // string
}
class extraDetailsResponse {
	public $ipAddress; // string
}
class markResponse {
	public $amount; // long
	public $currency; // int
	public $date; // dateTime
	public $number; // string
	public $result; // int
}
class threeDSResponse {
	public $authenticationRequestData; // authenticationRequestData
	public $authenticationResultData; // authenticationResultData
}
class authenticationRequestData {
	public $threeDSAcctId; // string
	public $threeDSAcsUrl; // string
	public $threeDSBrand; // string
	public $threeDSEncodedPareq; // string
	public $threeDSEnrolled; // string
	public $threeDSRequestId; // string
}
class authenticationResultData {
	public $brand; // string
	public $enrolled; // string
	public $status; // string
	public $eci; // string
	public $xid; // string
	public $cavv; // string
	public $cavvAlgorithm; // string
	public $signValid; // string
	public $transactionCondition; // string
}
class extraResponse {
	public $paymentOptionCode; // string
	public $paymentOptionOccNumber; // int
}
class fraudManagementResponse {
	public $riskControl; // riskControl
	public $riskAnalysis; // riskAnalysis
}
class riskControl {
	public $name; // string
	public $result; // string
}
class riskAnalysis {
	public $score; // string
	public $resultCode; // string
	public $status; // vadRiskAnalysisProcessingStatus
	public $requestId; // string
	public $extraInfo; // extInfo
}
class techRequest {
	public $browserUserAgent; // string
	public $browserAccept; // string
}
class orderRequest {
	public $orderId; // string
	public $extInfo; // extInfo
}
class createPayment {
	public $commonRequest; // commonRequest
	public $threeDSRequest; // threeDSRequest
	public $paymentRequest; // paymentRequest
	public $orderRequest; // orderRequest
	public $cardRequest; // cardRequest
	public $customerRequest; // customerRequest
	public $techRequest; // techRequest
}
class threeDSRequest {
	public $mode; // threeDSMode
	public $requestId; // string
	public $pares; // string
	public $brand; // string
	public $enrolled; // string
	public $status; // string
	public $eci; // string
	public $xid; // string
	public $cavv; // string
	public $algorithm; // string
}
class createPaymentResponse {
	public $createPaymentResult; // createPaymentResult
}
class createPaymentResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $subscriptionResponse; // subscriptionResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class cancelToken {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
}
class cancelTokenResponse {
	public $cancelTokenResult; // cancelTokenResult
}
class createTokenFromTransaction {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
}
class createTokenFromTransactionResponse {
	public $createTokenFromTransactionResult; // createTokenFromTransactionResult
}
class createTokenFromTransactionResult {
	public $commonResponse; // commonResponse
	public $authorizationResponse; // authorizationResponse
}
class cancelTokenResult {
	public $commonResponse; // commonResponse
}
class queryRequest {
	public $uuid; // string
	public $orderId; // string
	public $subscriptionId; // string
	public $paymentToken; // string
}
class wsResponse {
	public $requestId; // string
}
class createToken {
	public $commonRequest; // commonRequest
	public $cardRequest; // cardRequest
	public $customerRequest; // customerRequest
}
class createTokenResponse {
	public $createTokenResult; // createTokenResult
}
class createTokenResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $subscriptionResponse; // subscriptionResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class subscriptionResponse {
	public $subscriptionId; // string
	public $effectDate; // dateTime
	public $cancelDate; // dateTime
	public $initialAmount; // long
	public $rrule; // string
	public $description; // string
	public $initialAmountNumber; // int
	public $pastPaymentNumber; // int
	public $totalPaymentNumber; // int
	public $amount; // long
	public $currency; // int
}
class getTokenDetails {
	public $queryRequest; // queryRequest
}
class getTokenDetailsResponse {
	public $getTokenDetailsResult; // getTokenDetailsResult
}
class getTokenDetailsResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $subscriptionResponse; // subscriptionResponse
	public $extraResponse; // extraResponse
	public $fraudManagementResponse; // fraudManagementResponse
	public $threeDSResponse; // threeDSResponse
}
class updateSubscription {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
	public $subscriptionRequest; // subscriptionRequest
}
class subscriptionRequest {
	public $subscriptionId; // string
	public $effectDate; // dateTime
	public $amount; // long
	public $currency; // int
	public $initialAmount; // long
	public $initialAmountNumber; // int
	public $rrule; // string
	public $description; // string
}
class updateSubscriptionResponse {
	public $updateSubscriptionResult; // updateSubscriptionResult
}
class updateSubscriptionResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $subscriptionResponse; // subscriptionResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class capturePayment {
	public $settlementRequest; // settlementRequest
}
class settlementRequest {
	public $transactionIds; // string
	public $commission; // double
	public $date; // dateTime
}
class capturePaymentResponse {
	public $capturePaymentResult; // capturePaymentResult
}
class capturePaymentResult {
	public $commonResponse; // commonResponse
}
class findPayments {
	public $queryRequest; // queryRequest
}
class findPaymentsResponse {
	public $findPaymentsResult; // findPaymentsResult
}
class findPaymentsResult {
	public $commonResponse; // commonResponse
	public $orderResponse; // orderResponse
	public $transactionItem; // transactionItem
}
class transactionItem {
	public $transactionUuid; // string
	public $transactionStatusLabel; // string
	public $amount; // long
	public $currency; // int
	public $expectedCaptureDate; // dateTime
}
class refundPayment {
	public $commonRequest; // commonRequest
	public $paymentRequest; // paymentRequest
	public $queryRequest; // queryRequest
}
class refundPaymentResponse {
	public $refundPaymentResult; // refundPaymentResult
}
class refundPaymentResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class verifyThreeDSEnrollment {
	public $commonRequest; // commonRequest
	public $paymentRequest; // paymentRequest
	public $cardRequest; // cardRequest
	public $techRequest; // techRequest
}
class verifyThreeDSEnrollmentResponse {
	public $verifyThreeDSEnrollmentResult; // verifyThreeDSEnrollmentResult
}
class verifyThreeDSEnrollmentResult {
	public $commonResponse; // commonResponse
	public $threeDSResponse; // threeDSResponse
}
class reactivateToken {
	public $queryRequest; // queryRequest
}
class reactivateTokenResponse {
	public $reactivateTokenResult; // reactivateTokenResult
}
class reactivateTokenResult {
	public $commonResponse; // commonResponse
}
class createSubscription {
	public $commonRequest; // commonRequest
	public $orderRequest; // orderRequest
	public $subscriptionRequest; // subscriptionRequest
	public $cardRequest; // cardRequest
}
class createSubscriptionResponse {
	public $createSubscriptionResult; // createSubscriptionResult
}
class createSubscriptionResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $subscriptionResponse; // subscriptionResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class cancelSubscription {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
}
class cancelSubscriptionResponse {
	public $cancelSubscriptionResult; // cancelSubscriptionResult
}
class cancelSubscriptionResult {
	public $commonResponse; // commonResponse
}
class updatePayment {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
	public $paymentRequest; // paymentRequest
}
class updatePaymentResponse {
	public $updatePaymentResult; // updatePaymentResult
}
class updatePaymentResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $subscriptionResponse; // subscriptionResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class validatePayment {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
}
class validatePaymentResponse {
	public $validatePaymentResult; // validatePaymentResult
}
class validatePaymentResult {
	public $commonResponse; // commonResponse
}
class cancelPayment {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
}
class cancelPaymentResponse {
	public $cancelPaymentResult; // cancelPaymentResult
}
class cancelPaymentResult {
	public $commonResponse; // commonResponse
}
class checkThreeDSAuthentication {
	public $commonRequest; // commonRequest
	public $threeDSRequest; // threeDSRequest
}
class checkThreeDSAuthenticationResponse {
	public $checkThreeDSAuthenticationResult; // checkThreeDSAuthenticationResult
}
class checkThreeDSAuthenticationResult {
	public $commonResponse; // commonResponse
	public $threeDSResponse; // threeDSResponse
}
class getPaymentDetails {
	public $queryRequest; // queryRequest
}
class getPaymentDetailsResponse {
	public $getPaymentDetailsResult; // getPaymentDetailsResult
}
class getPaymentDetailsResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $subscriptionResponse; // subscriptionResponse
	public $extraResponse; // extraResponse
	public $fraudManagementResponse; // fraudManagementResponse
	public $threeDSResponse; // threeDSResponse
}
class duplicatePayment {
	public $commonRequest; // commonRequest
	public $paymentRequest; // paymentRequest
	public $orderRequest; // orderRequest
	public $queryRequest; // queryRequest
}
class duplicatePaymentResponse {
	public $duplicatePaymentResult; // duplicatePaymentResult
}
class duplicatePaymentResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class updateToken {
	public $commonRequest; // commonRequest
	public $queryRequest; // queryRequest
	public $cardRequest; // cardRequest
	public $customerRequest; // customerRequest
}
class updateTokenResponse {
	public $updateTokenResult; // updateTokenResult
}
class updateTokenResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $threeDSResponse; // threeDSResponse
	public $extraResponse; // extraResponse
	public $subscriptionResponse; // subscriptionResponse
	public $fraudManagementResponse; // fraudManagementResponse
}
class getPaymentUuid {
	public $legacyTransactionKeyRequest; // legacyTransactionKeyRequest
}
class legacyTransactionKeyRequest {
	public $transactionId; // string
	public $sequenceNumber; // int
	public $creationDate; // dateTime
}
class getPaymentUuidResponse {
	public $legacyTransactionKeyResult; // legacyTransactionKeyResult
}
class legacyTransactionKeyResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
}
class getSubscriptionDetails {
	public $queryRequest; // queryRequest
}
class getSubscriptionDetailsResponse {
	public $getSubscriptionDetailsResult; // getSubscriptionDetailsResult
}
class getSubscriptionDetailsResult {
	public $commonResponse; // commonResponse
	public $paymentResponse; // paymentResponse
	public $orderResponse; // orderResponse
	public $cardResponse; // cardResponse
	public $authorizationResponse; // authorizationResponse
	public $captureResponse; // captureResponse
	public $customerResponse; // customerResponse
	public $markResponse; // markResponse
	public $subscriptionResponse; // subscriptionResponse
	public $extraResponse; // extraResponse
	public $fraudManagementResponse; // fraudManagementResponse
	public $threeDSResponse; // threeDSResponse
}
class custStatus {
	const _PRIVATE = 'PRIVATE';
	const COMPANY = 'COMPANY';
}
class deliverySpeed {
	const STANDARD = 'STANDARD';
	const EXPRESS = 'EXPRESS';
}
class deliveryType {
	const RECLAIM_IN_SHOP = 'RECLAIM_IN_SHOP';
	const RELAY_POINT = 'RELAY_POINT';
	const RECLAIM_IN_STATION = 'RECLAIM_IN_STATION';
	const PACKAGE_DELIVERY_COMPANY = 'PACKAGE_DELIVERY_COMPANY';
	const ETICKET = 'ETICKET';
}
class vadRiskAnalysisProcessingStatus {
	const P_TO_SEND = 'P_TO_SEND';
	const P_SEND_KO = 'P_SEND_KO';
	const P_PENDING_AT_ANALYZER = 'P_PENDING_AT_ANALYZER';
	const P_SEND_OK = 'P_SEND_OK';
	const P_MANUAL = 'P_MANUAL';
	const P_SKIPPED = 'P_SKIPPED';
	const P_SEND_EXPIRED = 'P_SEND_EXPIRED';
}
class threeDSMode {
	const DISABLED = 'DISABLED';
	const ENABLED_CREATE = 'ENABLED_CREATE';
	const ENABLED_FINALIZE = 'ENABLED_FINALIZE';
	const MERCHANT_3DS = 'MERCHANT_3DS';
}
