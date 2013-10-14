<?php

namespace Wingr\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="wingr_user")
 * @ORM\Entity(repositoryClass="Wingr\AppBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks 
 * @UniqueEntity(fields={"email"}, groups={"Default", "Registration"}, message="Sorry! That email address is already registered.")
 */
class User extends BaseUser
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
        
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number",
     *     groups={"Registration", "Default"}
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_token", type="string", length=100, nullable=true)
     */    
    private $stripeToken;
    
    /**
     * @var string
     *
     * @ORM\Column(name="is_paid", type="boolean", nullable=false)
     */
    private $isPaid = false;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=12, nullable=true)
     */
    private $gender;    

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", length=4, nullable=true)
     */
    private $age;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="looking_for", type="string", length=12, nullable=true)
     */
    private $lookingFor;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=12, nullable=true)
     */
    private $source;    
    
    /**
     * @var array
     *
     * @ORM\Column(name="interested_in", type="json_array", nullable=true)
     */
    private $interestedIn;    
    
    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @Doctrine\ORM\Mapping\Column(type="datetime", name="created_at")
     */
    private $createdAt;
    
    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="update")
     * @Doctrine\ORM\Mapping\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
		$this->setUsername($this->getEmail());
    }

    public function setEmail( $email ){
    	parent::setEmail( $email );
    	$this->setUsername( $email );
    	return $this;    	
    }
    
    /**
     * Set stripeToken
     *
     * @param string $stripeToken
     * @return User
     */
    public function setStripeToken($stripeToken)
    {
        $this->stripeToken = $stripeToken;
    
        return $this;
    }

    /**
     * Get stripeToken
     *
     * @return string 
     */
    public function getStripeToken()
    {
        return $this->stripeToken;
    }

    /**
     * Set isPaid
     *
     * @param boolean $isPaid
     * @return User
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;
    
        return $this;
    }

    /**
     * Get isPaid
     *
     * @return boolean 
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set lookingFor
     *
     * @param integer $lookingFor
     * @return User
     */
    public function setLookingFor($lookingFor)
    {
        $this->lookingFor = $lookingFor;
    
        return $this;
    }

    /**
     * Get lookingFor
     *
     * @return integer 
     */
    public function getLookingFor()
    {
        return $this->lookingFor;
    }

    /**
     * Set interestedIn
     *
     * @param array $interestedIn
     * @return User
     */
    public function setInterestedIn($interestedIn)
    {
        $this->interestedIn = $interestedIn;
    
        return $this;
    }

    /**
     * Get interestedIn
     *
     * @return array 
     */
    public function getInterestedIn()
    {
        return $this->interestedIn;
    }
    
    public function getAge(){
    	return $this->age;
    }
    
    public function setAge($v){
    	$this->age = $v;
    	return $this;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return User
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }
}