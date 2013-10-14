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
	 * @Route("/test-creatives", name="test_creatives")
	 * @Template()
	 */
	public function testCreativesAction()
	{
		
		$allFiles = glob( $this->get('kernel')->getRootDir() . '/../web/creatives/*.png' );		
		$allFiles = array_merge( $allFiles , glob( $this->get('kernel')->getRootDir() . '/../web/creatives/*.gif' ) );		
		
		$files = array();
		foreach( $allFiles as $fn ){
			$files[] = basename($fn);
		}
		
		$adStats = array();
		
		$pdo = new \PDO("mysql:dbname=wingr;host=localhost", "wingr", "wingr");
		$sth = $pdo->prepare("SELECT COUNT(*) AS c, tag FROM impression WHERE imp_type = 'v' GROUP BY tag ORDER BY tag ASC");
		$sth->execute();
		$res = $sth->fetchAll();		
		
		foreach( $res as $row ){
			$adStats[ $row["tag"] ] = array("tag" => $row["tag"], "imp" => $row["c"]);
		}

		$pdo = new \PDO("mysql:dbname=wingr;host=localhost", "wingr", "wingr");
		$sth = $pdo->prepare("SELECT COUNT(*) AS c, tag FROM impression WHERE imp_type = 'c' GROUP BY tag ORDER BY tag ASC");
		$sth->execute();
		$res = $sth->fetchAll();
		
		foreach( $res as $row ){
			$adStats[ $row["tag"] ]["clk"] = $row["c"];
			$adStats[ $row["tag"] ]["ctr"] = round( ($adStats[ $row["tag"] ]["clk"] / $adStats[ $row["tag"] ]["imp"]) * 100, 3 );
		}		
		
		return array("files" => $files, "adStats" => $adStats);
	}	
	
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
	 * @Route("/registration-start", name="registration_start")
	 * @Template()
	 */
	public function registrationStartAction()
	{
		
		$form = $this->getRequest()->get("form", array());
		
		if( !array_key_exists("email", $form) || !array_key_exists("name", $form) ){
			return $this->redirect( "http://www.wingr.me" );
		}
		
		$user = new User();
		$user->setEmail( $form["email"] );
		$user->setName( $form["name"] );
		$user->setPassword("hello!");
		$user->setGender( $form["gender"] );
		$user->setLookingFor( $form["lookingFor"] );
		$user->setAge( $form["age"] );
		
		try{
			$this->getDoctrine()->getManager()->persist( $user );
			$this->getDoctrine()->getManager()->flush();
		}catch(\Exception $ex){
			$this->get('session')->getFlashBag()->add('notice', "Sorry! That email address is already registered.");
			return $this->redirect( $this->generateUrl("homepage") );
		}	
		
		$providerKey = $this->container->getParameter('fos_user.firewall_name');
		$token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
		$this->container->get('security.context')->setToken($token);

		$headers = 'From: ' . $this->getUser()->getEmail() . "\r\n" . 
				   'Reply-To: ' . $this->getUser()->getEmail() . "\r\n";
		
		mail("support@wingr.zendesk.com", date("r") . ": New wingr user", print_r($form, true), $headers);
		
		return $this->redirect( $this->generateUrl("registration_index") );
	}	
	
	/**
	 * @Route("/", name="homepage")
	 * @Template()
	 */
	public function indexAction()
	{
		
		if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') && $this->getUser()->getIsPaid() ){
			return $this->redirect( $this->generateUrl("user_dashboard") );
		}
		
		if( $this->getRequest()->get("theme", null) ){
			return $this->render('WingrAppBundle:Default:indexTwo.html.twig', array());
		}
		
		return array();
	}	
	
	/**
	 * @Route("/contact-us", name="contact_us")
	 * @Template()
	 */
	public function contactUsAction()
	{
		
		return array();
	}		
	
    /**
     * @Route("/register", name="registration_index")
     * @Template()
     */
    public function registerAction()
    {
    	    	
    	if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') && $this->getUser()->getIsPaid() ){
    		return $this->redirect( $this->generateUrl("user_dashboard") );
    	}    	
    	
    	if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirect( $this->generateUrl("homepage") );
    	}
    	
    	$user = $this->getUser();
    	$theme = $this->getRequest()->get("theme");    	
    	$form = $this->createForm(new RegistrationType("Wingr\\AppBundle\\Entity\\User"), $user);
    	
    	if( $this->getRequest()->isMethod("POST") ){
    		
    		$form->bind( $this->getRequest() );
    		
    		if( $form->isValid() ){
    			
    			$this->getDoctrine()->getManager()->flush();

    			$providerKey = $this->container->getParameter('fos_user.firewall_name');
    			$token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
    			$this->container->get('security.context')->setToken($token);    			 
    			
    			$user = $this->getUser();
    			
    			try { 
    				$res = \Stripe_Customer::create( array("card" => $user->getStripeToken(), 
    										   		   	   "plan" => "regular", "email" => $user->getEmail()), " sk_live_Ltqiexo4Mn6G3fKRg29PqSaP");    			
    			}catch(\Exception $ex){
    				
    				$msg = "Email: " . $user->getEmail() . "\nName: " . $user->getName() . "\n" . $ex->getMessage();
    				mail("adatta02@gmail.com, ackley.matthew@gmail.com", "Stripe error wingr.me - " . date("r"), $msg);    				
    				
    				$user->setStripeToken( null );
    				$this->getDoctrine()->getManager()->flush();
    				
    				$this->get('session')->getFlashBag()->add('error', $ex->getMessage());
    				return $this->redirect( $this->generateUrl("registration_index") );
    			}
    			
    			$user->setEnabled( true );
    			$user->setIsPaid( true );
    			$user->setStripeToken( $res["id"] );    			 			    		
    			$this->getDoctrine()->getManager()->flush();
    			
    			$msg = "Email: " . $user->getEmail() . "\nName: " . $user->getName();
    			mail("adatta02@gmail.com, ackley.matthew@gmail.com", "New wingr.me user - " . date("r"), $msg);
    			
    			return $this->redirect( $this->generateUrl("user_dashboard") );
    		}
    	}
    	
        return array("form" => $form->createView(), "theme" => $theme);
    }
}
