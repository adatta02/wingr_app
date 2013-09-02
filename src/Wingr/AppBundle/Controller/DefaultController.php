<?php

namespace Wingr\AppBundle\Controller;

require_once( dirname(__FILE__) . "/../../../../bin/stripe-php/lib/Stripe.php" );

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Wingr\AppBundle\Type\Form\RegistrationType;
use Wingr\AppBundle\Type\Form\RequestType;
use Wingr\AppBundle\Entity\User;

class DefaultController extends Controller
{
	
	/**
	 * @Route("/dashboard", name="user_dashboard")
	 * @Template()
	 */
	public function dashboardAction()
	{		
		$form = $this->createForm(new RequestType());
		
		if( $this->getRequest()->isMethod("POST") ){
			
			$form->bind( $this->getRequest() );
			
			if( $form->isValid() ){
				$headers = 'From: ' . $this->getUser()->getEmail() . "\r\n" .
						   'Reply-To: ' . $this->getUser()->getEmail() . "\r\n";
				
				$formData = $form->getData();				
				mail("support@wingr.zendesk.com", $formData["subject"], $formData["body"], $headers);
				
				$this->get('session')->getFlashBag()->add('notice', "Your message has been sent. We'll get back to you over email!");
				
				return $this->redirect( $this->generateUrl("user_dashboard") );
			}
		}
		
		return array("user" => $this->getUser(), "form" => $form->createView());
	}	
	
	/**
	 * @Route("/ajax/validate-form", name="registration_ajax_validate")
	 * @Template()
	 */
	public function ajaxValidateAction(){
		
		$res = array("isValid" => false, "html" => "");
		
		$user = new User();
		$form = $this->createForm(new RegistrationType("Wingr\\AppBundle\\Entity\\User"), $user);
		$form->bind( $this->getRequest() );
		
		$res["isValid"] = $form->isValid();
		$res["html"] = $this->renderView('WingrAppBundle:Default:renderSignupForm.html.twig', array('form' => $form->createView()));
		
		return new JsonResponse( $res );
	}
	
    /**
     * @Route("/", name="registration_index")
     * @Template()
     */
    public function indexAction()
    {    
    	
    	$user = new User();
    	$form = $this->createForm(new RegistrationType("Wingr\\AppBundle\\Entity\\User"), $user);
    	
    	if( $this->getRequest()->isMethod("POST") ){
    		
    		$form->bind( $this->getRequest() );
    		
    		if( $form->isValid() ){
    			
    			$res = \Stripe_Customer::create( array("card" => $user->getStripeToken(), 
    										   		   "plan" => "regular", "email" => $user->getEmail()), "sk_test_tIVB0G66iuZu2kt4pZt4IHTc");    			
    			
    			$user->setStripeToken( $res["id"] );    			
    			$this->getDoctrine()->getManager()->persist( $user );
    			$this->getDoctrine()->getManager()->flush();
    			
    			$providerKey = $this->container->getParameter('fos_user.firewall_name');
    			$token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
    			$this->container->get('security.context')->setToken($token);

    			return $this->redirect( $this->generateUrl("user_dashboard") );
    		}
    	}
    	
        return array("form" => $form->createView());
    }
}
