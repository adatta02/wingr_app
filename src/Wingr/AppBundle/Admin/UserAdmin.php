<?php

namespace Wingr\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin as Admin;
use Sonata\AdminBundle\Datagrid\ListMapper as ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper as DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper as FormMapper;
use FOS\UserBundle\Model\UserManagerInterface;
use CT\BEBundle\Entity\User;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends Admin {
    
    protected $userManager;
    
    protected function configureFormFields(FormMapper $formMapper) {
        
        $formMapper
            ->with('Account')
            ->add('id','text',array('disabled'=>true,'label'=>'User ID'))
            ->add('createdAt',null,array('disabled'=>true))
            ->add('name', 'text', array("label" => "Name", "required" => true))                        
            ->add('email', null, array('required' => true))
        ;
        
    }
    
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper->add('id',null,array('label'=>'ID'))
        ->add('email')
        ->add('username')
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper->addIdentifier('id')
        ->addIdentifier('email')
        ->addIdentifier('username');
    }
    
    public function preInsert($user) {
        $this->getUserManager()->updatePassword($user);
    }
    
    public function preUpdate($user) {
        $this->getUserManager()->updatePassword($user);
    }
    
    public function setUserManager(UserManagerInterface $userManager) {
        $this->userManager = $userManager;
    }
    
    public function getUserManager() {
        return $this->userManager;
    }
        
}