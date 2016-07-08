<?php 
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ipn;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Payment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use DateTime;
use DateInterval;
use DateTimeZone;

class MainController extends Controller
{
    /**
     * @Route("/ipn/{limit}", name="ipn")
     */
    public function createAction(Request $request,$limit="today")
    {
        $arg = $this->get('request')->request->all();
        $signature = $request->request->get('signature');

        if (empty($request->request->get('vads_site_id'))){
            return new Response("IPN triggered without valid Data");
        }
        $request->attributes->set('site_id',$request->request->get('vads_site_id'));
        // a faire mode prod ( connecter avec l'user ? )
        
        $key = $this->get('app.Config')->getKey();
        $k = '';
        ksort($arg);
        foreach ($arg as $param => $val) {
            if(substr($param,0,5) == 'vads_') {
               $k .= $val."+";
            }
        }
        $hash = sha1($k.$key);

        // Check signature
        $checked = ($hash == $signature) ? 'true' : 'false';
        $utc = new DateTimeZone('UTC');
        $ipn = new Ipn();
        $ipn->setStatus("NEW");
        $ipn->setTs(new DateTime($ipnOne['ts'],$utc));
        $ipn->setVadsSiteId($ipnOne['vads_site_id']);
        $ipn->setVadsUrlCheckSrc($ipnOne['vads_url_check_src']);
        $ipn->setVadsPaymentSrc($ipnOne['vads_payment_src']);
        $ipn->setVadsShopName($ipnOne['vads_shop_name']);
        $ipn->setVadsCtxMode($ipnOne['vads_ctx_mode']);
        $ipn->setVadsTransUuid($ipnOne['vads_trans_uuid']);
        $ipn->setVadsTransId($ipnOne['vads_trans_id']);
        $ipn->setVadsOrderId($ipnOne['vads_order_id']);
        $ipn->setVadsOrderInfo($ipnOne['vads_order_info']);
        $ipn->setVadsPaymentConfig($ipnOne['vads_payment_config']);
        $ipn->setVadsEffectiveCreationDate(($ipnOne['vads_effective_creation_date'] == "") ? null : new DateTime($ipnOne['vads_effective_creation_date'],$utc));
        $output->writeln($ipnOne['vads_sub_effect_date']);
        $ipn->setVadsOperationType($ipnOne['vads_operation_type']);
        $ipn->setVadsTransStatus($ipnOne['vads_trans_status']);
        $ipn->setVadsResult($ipnOne['vads_result']);
        $ipn->setVadsExtraResult($ipnOne['vads_extra_result']);
        $ipn->setVadsEffectiveAmount($ipnOne['vads_effective_amount']);
        $ipn->setVadsCurrency($ipnOne['vads_currency']);
        $ipn->setVadsContractUsed($ipnOne['vads_contract_used']);
        $ipn->setVadsAuthMode($ipnOne['vads_auth_mode']);
        $ipn->setVadsCardBrand($ipnOne['vads_card_brand']);
        $ipn->setVadsCardNumber($ipnOne['vads_card_number']);
        $ipn->setVadsPaymentSeq($ipnOne['vads_payment_seq']);
        $ipn->setVadsCustEmail($ipnOne['vads_cust_email']);
        $ipn->setVadsCaptureDelay($ipnOne['vads_capture_delay']);
        $ipn->setVadsPresentationDate(($ipnOne['vads_presentation_date'] == "") ? null : new DateTime($ipnOne['vads_presentation_date'],$utc));
        $ipn->setVadsWarrantyResult($ipnOne['vads_warranty_result']);
        $ipn->setVadsRiskControl($ipnOne['vads_risk_control']);
        $ipn->setVadsValidationMode("0");
        $ipn->setVadsRecurrenceStatus($ipnOne['vads_recurrence_status']);
        $ipn->setVadsIdentifierStatus($ipnOne['vads_identifier_status']);
        $ipn->setVadsIdentifier($ipnOne['vads_identifier']);
        $ipn->setVadsSubscription($ipnOne['vads_subscription']);
        $ipn->setVadsSubDesc($ipnOne['vads_sub_desc']);
        $ipn->setVadsSubEffectDate(($ipnOne['vads_sub_effect_date'] == "") ? null : new DateTime($ipnOne['vads_sub_effect_date'],$utc));
        $ipn->setVadsSubCurrency($ipnOne['vads_sub_currency']);
        $ipn->setVadsSubAmount($ipnOne['vads_sub_amount']);
        $ipn->setVadsSubInitAmountNumber($ipnOne['vads_sub_init_amount_number']);
        $ipn->setVadsSubInitAmount($ipnOne['vads_sub_init_amount']);
        $ipn->setVadsContrib($ipnOne['vads_contrib']);
        $ipn->setVadsExtInfoDonation($ipnOne['vads_ext_info_donation']);
        $ipn->setVadsExtInfoDonationRecipient($ipnOne['vads_ext_info_donation_recipient']);
        $ipn->setVadsExtInfoDonationRecipientName($ipnOne['vads_ext_info_donation_recipient_name']);
        $ipn->setVadsExtInfoDonationMerchant($ipnOne['vads_ext_info_donation_merchant']);
        $ipn->setSignature($ipnOne['signature']);
        $ipn->setFull($ipnOne['full']);
        $ipn->setChecked($ipnOne['checked']);
        $data = json_decode($ipnOne['full']);
        $ipn->setIdClient($data->vads_cust_id);

        $em = $this->get('doctrine.orm.entity_manager');

        $em->persist($ipn);

        $em->flush();

        return new Response("Ipn saved");
    }

    /**
     * @Route("/list/{limit}", name="list")
     * @Security("has_role('ROLE_USER')")
     */
    public function listIpnAction(Request $request,$limit="today"){
        $dates = $this->get('app.Tool')->getDates($limit);
        $pagination = $this->getDoctrine()->getRepository('AppBundle:Ipn')->findAllIpn($dates);

        return $this->render('list.html.twig',array('pagination' => $pagination,'limit'=>$limit,'url'=>'list'));
    }

    /**
     * @route("/payment/{limit}/{client}", name="payment")
     * @Security("has_role('ROLE_USER')")
     */
    public function listPaymentAction(Request $request,$client = null,$limit="today"){
        $em = $this->getDoctrine()->getManager();
        $tool = $this->get('app.Tool');
        if ($client){
            $payment_list = $em->getRepository('AppBundle:Payment')->findByVadsCustId($client);
        }else{
            $dates = $tool->getDates($limit);
            $dateAfter = $dates['dateAfter'];
            $dateBefore = $dates['dateBefore'];
            $payment_list = $em->getRepository('AppBundle:Payment')->findAllByDate($dateBefore,$dateAfter);
            if (count($payment_list) < 20){
                $ipn_list = $em->getRepository('AppBundle:Ipn')->getSample($dateBefore,$dateAfter);
                $i = 1;
                ob_start();
                $payment_list = array();
                foreach ($ipn_list as $ipn) {
                    $uuid = $ipn->getVadsTransUuid();
                    $payment = $em->getRepository('AppBundle:Payment')->findOneByUuid($uuid);
                    if(!$payment){
                        if($i%5 == 0){
                            sleep(5);
                        }
                        $request->attributes->set('site_id',$ipn->getVadsSiteId());
                        $vads = $this->get("app.PayzenWSv5");
                        $mode = $this->get("app.Config")->getMode();
                        sleep(2);
                        
                        try {
                            $response = $vads->getPaymentDetails($uuid);
                        }
                        catch (Exception $e) {
                            spip_log("getPaymentDetailsResult : erreur ".$e->getMessage(),$mode."_LOG");
                        }
                        if ($e = $response->getPaymentDetailsResult->commonResponse->responseCode){
                            spip_log($s="getPaymentDetailsResult $uuid : erreur $e : ".$response->getPaymentDetailsResult->commonResponse->responseCodeDetail,$mode."_LOG_RESP");
                        }else{
                            $data = $response->getPaymentDetailsResult;
                            $payment = new Payment();
                            $payment->setUuid($uuid);
                            $payment->setData($data);
                            $payment->setVadsCustId($data->customerResponse->billingDetails->reference);
                            $payment->setVadsCustFirstName($data->customerResponse->billingDetails->firstName);
                            $payment->setVadsCustLastName($data->customerResponse->billingDetails->lastName);
                            $payment->setVadsTransStatus($data->commonResponse->transactionStatusLabel);
                            $payment->setVadsEffectiveCreationDate(new DateTime($data->paymentResponse->creationDate));
                            $payment->setVadsEffectiveAmount($data->paymentResponse->amount);
                            $payment->setVadsRefundAmount(isset($data->captureResponse->refundAmount) ? $data->captureResponse->refundAmount : 0);
                            $payment->setVadsPaymentType($data->paymentResponse->paymentType);

                            $em->persist($payment);
                            $em->flush();
                            $i++;
                            $payment_list[] = $payment;
                        }
                    }
                }
                ob_clean();
            }
        }
        
        return $this->render('payment_list.html.twig',array('payment_list'=>$payment_list,'limit'=>$limit,'url'=>'payment'));
    }

    /**
     * @route("/clients/{limit}", name="clients")
     * @Security("has_role('ROLE_USER')")
     */
    public function listClientAction($limit="today"){
        $client_list = array();
        $dates = $this->get('app.Tool')->getDates($limit);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Payment');
        $clients = $repo->findAllClientByDate($dates);
        foreach ($clients as $client) {
            $client_list[] = array(
                'idClient'=>$client['vadsCustId'],
                'nbCommande'=>$repo->findNbCommandClient($client['vadsCustId'],$dates),
                'nbRefused'=>$repo->findNbRefusedClient($client['vadsCustId'],$dates),
                'amountTt'=>number_format($repo->findTtAmountClient($client['vadsCustId'],$dates)/100,2,',',' '),
                'last_name'=>$client['vadsCustLastName'],
                'first_name'=>$client['vadsCustFirstName']
                );
        }

        

        return $this->render('client_list.html.twig',array('limit'=>$limit,'client_list'=>$client_list,'url'=>'clients'));
    }

    /**
     * @route("/sales/{limit}", name="sales")
     * @Security("has_role('ROLE_USER')")
     */
    public function performanceAction($limit="today"){
        $rp = $this->getDoctrine()->getManager()->getRepository('AppBundle:Payment');
        $perform_list = array();
        $dates = $this->get('app.Tool')->getDates($limit);
        $dateBefore = $dates['dateBefore'];
        $dateAfter = $dates['dateAfter'];
        $oneInterval = $dates['oneInterval'];
        $allInterval = $dates['allInterval'];
        $perform_list[] = array(
            'time' => 'Total',
            'ttAmount'=>number_format($rp->findTtAmount($dateBefore,$dateAfter)/100,2,'.',' '),
            'nbCommands' => $rp->findNbCommand($dateBefore,$dateAfter),
            'nbClients' => $rp->findNbClient($dateBefore,$dateAfter),
            'nbAccepted' => $rp->findNbAccepted($dateBefore,$dateAfter),
            'nbRefused' => $rp->findNbRefused($dateBefore,$dateAfter)
            );
        $dateFin = clone $dateBefore;
        $dateBefore->add($allInterval);
        $dateBefore->sub($oneInterval);
        while($dateAfter > $dateFin) {
            $perform_list[] = array(
                'time' => ($limit == 'today' or $limit =='yesterday') ? $dateBefore->format('H:i') : (($limit == 'week') ? $dateBefore->format('D') : $dateBefore->format('Y-m-d')),
                'ttAmount'=>number_format($rp->findTtAmount($dateBefore,$dateAfter)/100,2,'.',' '),
                'nbCommands' => $rp->findNbCommand($dateBefore,$dateAfter),
                'nbClients' => $rp->findNbClient($dateBefore,$dateAfter),
                'nbAccepted' => $rp->findNbAccepted($dateBefore,$dateAfter),
                'nbRefused' => $rp->findNbRefused($dateBefore,$dateAfter)
            );
            $dateAfter->sub($oneInterval);
            $dateBefore->sub($oneInterval);
        }
        return $this->render('performance.html.twig',array('limit'=>$limit,'perform_list'=>$perform_list,'url'=>'sales'));
    }
}
