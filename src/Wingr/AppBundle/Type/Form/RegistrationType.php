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
                
        $builder->remove("plainPassword")
                ->remove("username")
                ->remove("email")
                                
                ->add('nameOnCard', 'text', array("label" => "Name on card", "attr" => array("placeholder" => "Name on card"), "required" => true))                               
        ;

        
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // $resolver->setDefaults(array('validation_groups' => array('Registration')));
    }
    
    public function getName()
    {
        return 'wingr_register';
    }
}