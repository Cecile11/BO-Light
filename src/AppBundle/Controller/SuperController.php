<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Payment;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use DateTime;
use DateInterval;
use DateTimeZone;

class SuperController extends Controller
{
    /**
     * @Route("/super_option", name="super_option")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('super_option.html.twig');
    }

    /**
     * @Route("/super_option/delete",name="purgeData")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function purgeDataAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery('DELETE * FROM AppBundle:Ipn');
    	$query->getResult();
    	$query = $query = $em->createQuery('DELETE AppBundle:Payment');
    	$query->getResult();
    	return new Response('Database purged');
    }

    /**
     * @Route("/super_option/delete_payment",name="purgePayment")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function purgePaymentAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $query = $query = $em->createQuery('DELETE AppBundle:Payment');
        $query->getResult();
        return new Response('Payment delete');
    }

    /**
     * @Route("/super_option/get_payment",name="getPayments")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function getPaymentAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $limit = strtolower($request->request->get('limit'));
        $ipn_list = $em->getRepository('AppBundle:Ipn')->getLast($request->request->get('number'),$this->get('app.Tool')->getDates($limit));
        $i = 1;

        ob_start();
        foreach ($ipn_list as $ipn) {
            $uuid = $ipn['vadsTransUuid'];
            $payment = $em->getRepository('AppBundle:Payment')->findOneByUuid($uuid);
            if(!$payment){
                if($i%5 == 0){
                    sleep(5);
                }
                $request->attributes->set('site_id',$ipn['vadsSiteId']);
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
                    $date = DateTime::createFromFormat(DateTime::W3C,$data->paymentResponse->creationDate,new DateTimeZone('Europe/Paris'));
                    $date->setTimezone(new DateTimeZone('UTC'));
                    $payment->setVadsEffectiveCreationDate($date);
                    $payment->setVadsEffectiveAmount($data->paymentResponse->amount);
                    $payment->setVadsRefundAmount(isset($data->captureResponse->refundAmount) ? $data->captureResponse->refundAmount : 0);
                    $payment->setVadsPaymentType($data->paymentResponse->paymentType);
                    $payment->setVadsOperationType($data->paymentResponse->operationType);
                    $payment->setVadsCurrency($data->paymentResponse->currency);
                    $em->persist($payment);
                    $em->flush();
                    $i++;
                }
            }
        }
        ob_clean();
        return new Response($limit);
    }

    /**
     * @Route("/super_option/payment_from",name="payment_from")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function getPaymentFromAction(Request $request){
        $limit = strtolower($request->request->get('time'));
        $dates = $this->get('app.Tool')->getDates($limit);
        return new Response($this->getDoctrine()->getManager()->getRepository('AppBundle:Ipn')->countDayIpn($dates));
    }

    /**
     * @Route("/super_option/loaddb",name="loaddb")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function loaddbAction(Request $request){
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
           'command' => 'loaddb',
           'db' => $this->getParameter('app.path_old_database'),
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);
        return new Response($output->fetch());
    }

    /**
     * @Route("/super_option/payment_table/{limit}",name="payment_table")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function listPaymentAction(Request $request,$limit = "today"){
        $dates = $this->get('app.Tool')->getDates($limit);
        $payment_list = $this->getDoctrine()->getManager()->getRepository('AppBundle:Payment')->findAllByDate($dates['dateBefore'],$dates['dateAfter']);
        return $this->render('payment_table.html.twig',array('payment_list'=>$payment_list,'limit'=>$limit,'url'=>'payment_table'));
    }
}
