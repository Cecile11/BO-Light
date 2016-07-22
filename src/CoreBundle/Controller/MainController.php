<?php 
namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Entity\Ipn;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Entity\Payment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\Exception;
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
        
        $key = $this->get('core.Config')->getKey();
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
     * @Route("/list/{limit}/{offset}", name="list")
     * @Security("has_role('ROLE_USER')")
     */
    public function listIpnAction(Request $request,$limit="today",$offset=0){
        $dates = $this->get('core.Tool')->getDates($limit,$offset);
        if ($offset < 0){
            $offset = 0;
        }
        $decalage = $this->get('core.Tool')->getDecalage('UTC','Europe/Paris');
        $date = $dates['date'];
        $pagination = $this->getDoctrine()->getRepository('CoreBundle:Ipn')->findAllIpn($dates);

        return $this->render('CoreBundle:Core:list.html.twig',array('pagination' => $pagination,'limit'=>$limit,'url'=>'list','decalage'=>$decalage,'offset'=>$offset,'date'=>$date));
    }

    /**
     * @route("/payment/{limit}/{offset}/{client}", name="payment")
     * @Security("has_role('ROLE_USER')")
     */
    public function listPaymentAction(Request $request,$client = null,$limit="today",$offset=0){
        $decalage = $this->get('core.Tool')->getDecalage('UTC','Europe/Paris');
        $em = $this->getDoctrine()->getManager();
        $tool = $this->get('core.Tool');
        if ($offset < 0){
            $offset = 0;
        }
        $date = "None";
        if ($client){
            $payment_list = $em->getRepository('CoreBundle:Payment')->findByVadsCustId($client);
        }else{
            $dates = $tool->getDates($limit,$offset);
            $dateAfter = $dates['dateAfter'];
            $dateBefore = $dates['dateBefore'];
            $date = $dates['date'];
            $payment_list = $em->getRepository('CoreBundle:Payment')->findAllByDate($dateBefore,$dateAfter);
            if (count($payment_list) < 20){
                $ipn_list = $em->getRepository('CoreBundle:Ipn')->getSample($dateBefore,$dateAfter);
                $i = 1;
                ob_start();
                $payment_list = array();
                foreach ($ipn_list as $ipn) {
                    $uuid = $ipn->getVadsTransUuid();
                    $payment = $em->getRepository('CoreBundle:Payment')->findOneByUuid($uuid);
                    if(!$payment){
                        if($i%5 == 0){
                            sleep(5);
                        }
                        $request->attributes->set('site_id',$ipn->getVadsSiteId());
                        $vads = $this->get("core.PayzenWSv5");
                        $mode = $this->get("core.Config")->getMode();
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
                            $date = DateTime::createFromFormat(DateTime::W3C,$data->paymentResponse->creationDate,new DateTimeZone('Europe/Paris'));
                            $date->setTimezone(new DateTimeZone('UTC'));
                            $payment->setVadsEffectiveCreationDate($date);
                            $payment->setVadsEffectiveAmount($data->paymentResponse->amount);
                            $payment->setVadsRefundAmount(isset($data->captureResponse->refundAmount) ? $data->captureResponse->refundAmount : 0);
                            $payment->setVadsPaymentType($data->paymentResponse->paymentType);
                            $payment->setVadsOperationType($data->paymentResponse->operationType == 0 or $data->paymentResponse->operationType == '0' ? 'DEBIT' : 'CREDIT');
                            $payment->setVadsCurrency($data->paymentResponse->currency);
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
        
        return $this->render('CoreBundle:Core:payment_list.html.twig',array('payment_list'=>$payment_list,'limit'=>$limit,'url'=>'payment','decalage'=>$decalage,'offset'=>$offset,'date'=>$date));
    }

    /**
     * @route("/clients/{limit}/{offset}", name="clients")
     * @Security("has_role('ROLE_USER')")
     */
    public function listClientAction($limit="today",$offset=0){
        $client_list = array();
        if ($offset < 0){
            $offset = 0;
        }
        $dates = $this->get('core.Tool')->getDates($limit,$offset);
        $date = $dates['date'];
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CoreBundle:Payment');
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

        

        return $this->render('CoreBundle:Core:client_list.html.twig',array('limit'=>$limit,'client_list'=>$client_list,'url'=>'clients','offset'=>$offset,'date'=>$date));
    }

    /**
     * @route("/sales/{limit}/{offset}", name="sales")
     * @Security("has_role('ROLE_USER')")
     */
    public function performanceAction($limit="today",$offset=0){
        $rp = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Payment');
        $perform_list = array();
        if ($offset < 0){
            $offset = 0;
        }
        $dates = $this->get('core.Tool')->getDates($limit,$offset);
        $dateSelect = $dates['date'];
        $dateBefore = $dates['dateBefore'];
        $dateAfter = $dates['dateAfter'];
        $oneInterval = $dates['oneInterval'];
        $allInterval = $dates['allInterval'];
        $heure = (int) $dateBefore->format('H');
        if ($limit == "day-2" or $limit == "day-3" or $limit == "yesterday" or $limit == "today"){
            $perform_list[] = array(
                'time' => 'Total',
                'ttAmount'=>number_format($rp->findTtAmount($dateBefore,$dateAfter)/100,2,'.',' '),
                'nbCommands' => $rp->findNbCommand($dateBefore,$dateAfter),
                'nbClients' => $rp->findNbClient($dateBefore,$dateAfter),
                'nbAccepted' => $rp->findNbAccepted($dateBefore,$dateAfter),
                'nbRefused' => $rp->findNbRefused($dateBefore,$dateAfter)
                );
            $perform_list[] = array(
                'time' => 'Credit',
                'ttAmount'=>number_format($rp->findTtAmountCredit($dateBefore,$dateAfter)/100,2,'.',' '),
                'nbCommands' => $rp->findNbCommandCredit($dateBefore,$dateAfter),
                'nbClients' => $rp->findNbClientCredit($dateBefore,$dateAfter),
                'nbAccepted' => $rp->findNbAcceptedCredit($dateBefore,$dateAfter),
                'nbRefused' => $rp->findNbRefusedCredit($dateBefore,$dateAfter)
                );
            $dateFin = clone $dateBefore;
            $dateBefore->add($allInterval);
            $dateBefore->sub($oneInterval);
            while($dateAfter > $dateFin) {
                $dateBefore->setTimeZone(new DateTimeZone('Europe/Paris'));
                $date = $dateBefore->format('H:i');
                $dateBefore->setTimeZone(new DateTimeZone('UTC'));
                $perform_list[] = array(
                    'time' => $date,
                    'ttAmount'=>number_format($rp->findTtAmount($dateBefore,$dateAfter)/100,2,'.',' '),
                    'nbCommands' => $rp->findNbCommand($dateBefore,$dateAfter),
                    'nbClients' => $rp->findNbClient($dateBefore,$dateAfter),
                    'nbAccepted' => $rp->findNbAccepted($dateBefore,$dateAfter),
                    'nbRefused' => $rp->findNbRefused($dateBefore,$dateAfter)
                );
                $dateAfter->sub($oneInterval);
                $dateBefore->sub($oneInterval);
            }
            return $this->render('CoreBundle:Core:sales.html.twig',array('limit'=>$limit,'perform_list'=>$perform_list,'url'=>'sales','offset'=>$offset,'date'=>$dateSelect));
        }else{
            if ($limit == "week"){
                //Total
                $performDay = array('time'=>'Total');
                $performCredit = array('time'=>'Credit');
                $day_list = array();
                $oneWeek = new DateInterval('P8D');
                $dateBefore->add($oneWeek);
                $dateBefore->sub($allInterval);
                for ($i=1; $i < 9; $i++) {
                    $dateBefore->setTimeZone(new DateTimeZone('Europe/Paris'));
                    $day_list[$i] = $dateBefore->format('l')." ".$dateBefore->format('d/m');
                    $dateBefore->setTimeZone(new DateTimeZone('UTC'));
                    $performDay = array_merge($performDay,array(
                        'ttAmount'.$i=>number_format($rp->findTtAmount($dateBefore,$dateAfter)/100,2,'.',' '),
                        'nbCommands'.$i=>$rp->findNbAccepted($dateBefore,$dateAfter)
                        ));
                    $performCredit = array_merge($performCredit,array(
                        'ttAmount'.$i=>number_format($rp->findTtAmountCredit($dateBefore,$dateAfter)/100,2,'.',' '),
                        'nbCommands'.$i=>$rp->findNbAcceptedCredit($dateBefore,$dateAfter)
                        ));
                    $dateAfter->sub($allInterval);
                    $dateBefore->sub($allInterval);
                }
                $perform_list[] = $performDay;
                $perform_list[] = $performCredit;
                $dateAfter->add($oneWeek);
                $dateBefore->add($oneWeek);
                $dateBefore->add($allInterval);
                $dateBefore->sub($oneInterval);
                for ($i=0; $i < 24 ; $i++) {
                    $dateBefore->setTimeZone(new DateTimeZone('Europe/Paris'));
                    $performDay = array('time'=>$dateBefore->format('H:i'));
                    $dateBefore->setTimeZone(new DateTimeZone('UTC'));
                    for ($j=1; $j < 9; $j++) {
                        $performDay = array_merge($performDay,array(
                            'ttAmount'.$j=>number_format($rp->findTtAmount($dateBefore,$dateAfter)/100,2,'.',' '),
                            'nbCommands'.$j=>$rp->findNbAccepted($dateBefore,$dateAfter)
                            ));
                        $dateAfter->sub($allInterval);
                        $dateBefore->sub($allInterval);
                    }
                    $dateAfter->add($oneWeek);
                    $dateBefore->add($oneWeek);
                    $dateAfter->sub($oneInterval);
                    $dateBefore->sub($oneInterval);
                $perform_list[] = $performDay;
                }
            return $this->render('CoreBundle:Core:salesWeek.html.twig',array('limit'=>$limit,'perform_list'=>$perform_list,'url'=>'sales','day_list'=>$day_list,'offset'=>$offset,'date'=>$dateSelect));
            }else{
                return $this->redirectToRoute('sales');
            }
        }
    }
}
