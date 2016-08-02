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

class JioController extends Controller{

  /**
   * @Route("/allPayments/{id}", name="allPayments")
   */
   public function allPaymentsAction(Request $request,$id = null)
   {
    if ($id == null){
      $data = $this->getDoctrine()->getRepository('CoreBundle:Payment')->findAllData();
     return new JsonResponse(array_column($data,'data'));
    } else {
      $payment = $this->getDoctrine()->getRepository('CoreBundle:Payment')->findOneByUuid($id);
      return new JsonResponse($payment->getData());
    }
   }

   /**
    * @Route("/test",name="test")
    */
   public function testAction(Request $request){
     return $this->render('CoreBundle:Core:index.html.twig');
   }

   /**
    * @Route("/payzenGadget",name="payzenGadget")
    */
   public function getGadget(Request $request){
   	return $this->render('CoreBundle:Core:payzenGadget.html.twig');
   }
}