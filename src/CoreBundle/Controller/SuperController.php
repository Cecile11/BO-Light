<?php

namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Entity\Payment;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Serializer\Exception\Exception;
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
        $siteIdList = $this->get('core.Config')->getSiteIdList();
        return $this->render('CoreBundle:Core:super_option.html.twig',array('site_id_list'=>$siteIdList));
    }

    /**
     * @Route("/super_option/delete",name="purgeData")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function purgeDataAction(Request $request){
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery('DELETE CoreBundle:Ipn');
    	$query->getResult();
    	return new Response('Ipn delete');
    }

    /**
     * @Route("/super_option/delete_payment",name="purgePayment")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function purgePaymentAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $query = $query = $em->createQuery('DELETE CoreBundle:Payment');
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
        $ipn_list = $em->getRepository('CoreBundle:Ipn')->getLast($request->request->get('number'),$this->get('core.Tool')->getDates($limit));
        $i = 1;
        ob_start();
        foreach ($ipn_list as $ipn) {
            $uuid = $ipn['vadsTransUuid'];
            $payment = $em->getRepository('CoreBundle:Payment')->findOneByUuid($uuid);
            if(!$payment){
                if($i%5 == 0){
                    sleep(5);
                }
                $request->attributes->set('site_id',$ipn['vadsSiteId']);
                $test = $this->get('core.Loader')->loadPayment($uuid);
                if (!$test){
                    throw new Exception("Can't load payment");
                }
                $i++;
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
        $dates = $this->get('core.Tool')->getDates($limit);
        return new Response($this->getDoctrine()->getManager()->getRepository('CoreBundle:Ipn')->countDayIpn($dates));
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
           'db' => $this->getParameter('core.path_old_database'),
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
        $dates = $this->get('core.Tool')->getDates($limit);
        $payment_list = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Payment')->findAllByDate($dates['dateBefore'],$dates['dateAfter']);
        return $this->render('CoreBundle:Core:payment_table.html.twig',array('payment_list'=>$payment_list,'limit'=>$limit,'url'=>'payment_table'));
    }

    /**
     * @Route("/super_option/payment_from_uuid",name="get_payment_from_uuid")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function getPaymentFromUuidAction(Request $request){
        ob_start();
        $uuid = $request->request->get('uuid');
        $siteId = $request->request->get('site_id');
        $request->attributes->set('site_id',$siteId);
        $payment = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Payment')->findOneByUuid($uuid);
        if(!$payment){
            if($this->get('core.Loader')->loadPayment($uuid)){
                $message = "Payment loaded";
            } else {
                $message = "Can't load payment";
            }
        } else {
            $message = "Payment already loaded";
        }
        ob_clean();
        $siteIdList = $this->get('core.Config')->getSiteIdList();
        return $this->render('CoreBundle:Core:super_option.html.twig',array('site_id_list'=>$siteIdList,'message'=>$message));
    }
}
