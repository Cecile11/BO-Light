<?php
namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Entity\Ipn;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Entity\Payment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\Exception;
use stdClass;

class JioController extends Controller{

  /**
   * @Route("/allPayments/{id}", name="allPayments")
   */
   public function allPaymentsAction(Request $request,$id = null)
   {
    if ($id == null){
      $data = $this->getDoctrine()->getRepository('CoreBundle:Payment')->findAllData();
     return new JsonResponse($this->get('Core.Tool')->aplanArray(array_column($data,'data')));
    } else {
      $payment = $this->getDoctrine()->getRepository('CoreBundle:Payment')->findOneByUuid($id);
      $data = new stdClass();
      $this->get('Core.Tool')->aplanObject($payment->getData(),$data);
      return new JsonResponse($data);
    }
   }

   /**
    * @Route("/test",name="test")
    * @Security("has_role('ROLE_USER')")é
    */
   public function testAction(Request $request){
     return $this->render('CoreBundle:Core:jio/index.html.twig');
   }

   /**
    * @Route("/gadget/{name}",name="gadget")
    */
   public function getPaymentGadgetAction($name){
   	return $this->render('CoreBundle:Core:jio/'.$name.'Gadget.html.twig');
   }
}