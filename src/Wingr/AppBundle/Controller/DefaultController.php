<?php

namespace Wingr\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Wingr\AppBundle\Type\Form\RegistrationType;
use Wingr\AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
    	$user = new User();
    	$form = $this->createForm(new RegistrationType("Wingr\\AppBundle\\Entity\\User"));
    	
        return array("form" => $form->createView());
    }
}
