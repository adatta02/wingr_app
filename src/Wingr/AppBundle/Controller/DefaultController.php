<?php

namespace Wingr\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
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
		
		return array("user" => $this->getUser(), "form" => $form->createView());
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
