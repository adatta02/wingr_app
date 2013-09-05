<?php 

namespace Wingr\AppBundle\Type\Form;

use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options){

		$builder->add("subject", "text", array("label" => "Subject", "constraints" => array(new NotBlank()) ) )
				->add("body", "textarea", array("label" => "Body", "constraints" => array(new NotBlank()) )  )
		;

	}

	public function getName(){
		return 'request';
	}

}