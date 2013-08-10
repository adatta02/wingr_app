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
     *     groups={"Registration", "Update", "ReferFriend"}
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_on_card", type="string", length=100, nullable=true)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your name cannot contain a number",
     *     groups={"Registration", "Update", "ReferFriend"}
     * )
     */
    private $nameOnCard;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="billing_address", type="string", length=255, nullable=true)
     */
    private $billingAddress;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="billing_city", type="string", length=255, nullable=true)
     */
    private $billingCity;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="billing_zipcode", type="string", length=10, nullable=true)
     */
    private $billingZipcode;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="is_paid", type="boolean", nullable=false)
     */
    private $isPaid = false;    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="gender", type="integer", length=1, nullable=true)
     */
    private $gender;    

    /**
     * @var integer
     *
     * @ORM\Column(name="looking_for", type="integer", length=1, nullable=true)
     */
    private $lookingFor;

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
}