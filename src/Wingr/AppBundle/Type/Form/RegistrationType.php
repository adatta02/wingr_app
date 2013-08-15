<?php

namespace Wingr\AppBundle\Type\Form;

use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $lookingForChoices = array("1" => "New friends", "2" => "Short-term dating", 
        						   "3" => "Long-term dating", "4" => "Activity Partners",
        						   "5" => "Casual sex");
        
        $monthVals = array();
        $yearVals = array_combine( range(date("Y"), date("Y") + 10), range(date("Y"), date("Y") + 10) );
        for($i = 1; $i <= 12; $i++){
        	$key = ($i < 10) ? "0" . $i : $i;
        	$monthVals[ $key ] = $i;
        }                
        
        $builder->remove("plainPassword")
                ->remove("username")
                ->remove("email")
                
                ->add('name', 'text', array('label' => 'Name', "constraints" => array( new NotBlank() )))
                
                ->add('plainPassword', 'password', array('label' => 'Password', "constraints" => array( new NotBlank() )))
                
                ->add('email', 'email', array("required" => true, 'label' => 'Email Address', 
                	  "constraints" => array( new NotBlank(), new Email() ), 'translation_domain' => 'FOSUserBundle'))

               	->add("gender", "choice", array("required" => false, "empty_value" => "Please select...", 
               				    "label" => "I am a", "choices" => array("1" => "Guy", "2" => "Girl")))
               	
				->add("lookingFor", "choice", array("required" => false, "empty_value" => "Please select...",
               				 	    "label" => "I am looking for", "choices" => array("1" => "Guys", "2" => "Girls", "3" => "Both")))               						
               						
               	->add("interestedIn", "choice", array("required" => false, "expanded" => true, "multiple" => true, 
               					      "label" => "And I'm interested in", "choices" => $lookingForChoices))
               	
                ->add('nameOnCard', 'text', array("mapped" => false, "label" => "Name on card", "required" => true))
                ->add('number', 'text', array("mapped" => false, "label" => "Card number", "required" => true))
                ->add('cvc', 'text', array("mapped" => false, "label" => "CVC # <a href='#cvv-modal'>(help)</a>", "required" => true))
                ->add('expMonth', 'choice', array("mapped" => false, "choices" => $monthVals, 
                				  "empty_value" => "Please select...", "label" => "Expiration Month", "required" => true))
				->add('expYear', 'choice', array("mapped" => false, "choices" => $yearVals,
                				  "empty_value" => "Please select...", "label" => "Expiration Year", "required" => true))
				->add('stripeToken', 'hidden', array("data" => "TEST", "required" => true))
				->add('enabled', 'hidden', array("data" => "1", "required" => true))
        ;

        
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('validation_groups' => array('Default')));
    }
    
    public function getName()
    {
        return 'wingr_register';
    }
}