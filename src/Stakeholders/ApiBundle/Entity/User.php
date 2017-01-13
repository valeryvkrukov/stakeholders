<?php
namespace Stakeholders\ApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table("users")
 * @ORM\Entity(repositoryClass="Stakeholders\ApiBundle\Repository\UserRepository")
 * @Vich\Uploadable
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser
{
	/**
	 * @var integer
	 * 
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Exclude()
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="first_name", type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $firstName;

	/**
	 * @ORM\Column(name="last_name", type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $lastName;
	
	/**
	 * @Vich\UploadableField(mapping="photoFile", fileNameProperty="photo")
	 */
	protected $photoFile;
	
	/**
	 * @ORM\Column(name="photo_name",type="string", nullable=true)
	 * @JMS\Expose()
	 */
	protected $photo;
	
	/**
	 * @ORM\Column(name="brief", type="text", nullable=true)
	 * @JMS\Expose()
	 */
	protected $brief;
	
	/**
	 * @ORM\Column(name="is_approved", type="boolean", nullable=false)
	 * @JMS\Expose()
	 */
	protected $isApproved = false;
	
	/**
	 * @ORM\OneToOne(targetEntity="Stakeholders\ApiBundle\Entity\Account", mappedBy="user")
	 */
	protected $account;
	
	/**
	 * @ORM\Column(name="network", type="string", nullable=true)
	 */
	protected $network;
	
	/**
	 * @ORM\ManyToMany(targetEntity="User")
	 * @ORM\JoinTable(name="agency_influencers",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="agency_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="influencer_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	protected $influencers;
	
	/**
	 * @ORM\ManyToMany(targetEntity="User")
	 * @ORM\JoinTable(name="influencer_customers",
	 *   joinColumns={
	 *     @ORM\JoinColumn(name="influencer_id", referencedColumnName="id")
	 *   },
	 *   inverseJoinColumns={
	 *     @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
	 *   }
	 * )
	 */
	protected $customers;

	public function __construct()
	{
		parent::__construct();
		$this->influencers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->customers = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
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
	 * Get firstName
	 * 
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}
	
	/**
	 * Set firstName
	 * 
	 * @param firstName $firstName
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
		
		return $this;
	}
	
	/**
	 * Get lastName
	 * 
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}
	
	/**
	 * Set lastName
	 * 
	 * @param string $lastName
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
		
		return $this;
	}
	
	/**
	 * Get photoFile
	 *
	 * @return File|null
	 */
	public function getPhotoFile(File $photo = null) {
		return $this->photoFile;
	}
	
	/**
	 * Set photoFile
	 *
	 * File|\Symfony\Component\HttpFoundation\File\UploadedFile $photoFile
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function setPhotoFile(File $photo = null) {
		$this->photoFile = $photo;
		
		return $this;
	}
	
	/**
	 * Get photo
	 *
	 * @return string
	 */
	public function getPhoto() {
		return $this->photo;
	}
	
	/**
	 * Set photo
	 *
	 * @param string $photo
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function setPhoto($photo) {
		$this->photo = $photo;
	
		return $this;
	}
	
	/**
	 * Get brief
	 * 
	 * @return boolean
	 */
	public function getBrief() {
		return $this->brief;
	}
	
	/**
	 * Set brief
	 * 
	 * @param boolean $brief
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function setBrief($brief) {
		$this->brief = $brief;
		
		return $this;
	}
	
	/**
	 * Get isApproved
	 * 
	 * @return boolean
	 */
	public function getIsApproved() {
		return $this->isApproved;
	}
	
	/**
	 * Set isApproved
	 * 
	 * @param boolean $isApproved
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function setIsApproved($isApproved) {
		$this->isApproved = $isApproved;
		
		return $this;
	}
	
	/**
	 * Get account
	 *
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function getAccount() {
		return $this->account;
	}
	
	/**
	 * Set account
	 *
	 * @param Stakeholders\ApiBundle\Entity\Account $account
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function setAccount($account) {
		$this->account = $account;
		
		return $this;
	}
	
    /**
     * Get network
     *
     * @return string
     */
    public function getNetwork() {
    	return $this->network;
    }
    
    /**
     * Set network
     *
     * @param string $network
     * @return \Stakeholders\ApiBundle\Entity\User
     */
    public function setNetwork($network) {
    	$this->network = $network;
    
    	return $this;
    }
	
	/**
	 * Get influencers
	 * 
	 * @return Doctrine\Common\Collections\ArrayCollection<Stakeholders\ApiBundle\Entity\User>
	 */
	public function getInfluencers() {
		return $this->influencers;
	}
	
	/**
	 * Add influencer
	 * 
	 * @param Stakeholders\ApiBundle\Entity\User $influencer
	 * @return Stakeholders\ApiBundle\Entity\User      	
	 */
	public function addInfluencer($influencer) {
		$this->influencers->add($influencer);
		
		return $this;
	}
	
	/**
	 * Remove influencer
	 *
	 * @param Stakeholders\ApiBundle\Entity\User $influencer
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function removeInfluencer($influencer) {
		$this->influencers->removeElement($influencer);
	
		return $this;
	}
	
	/**
	 * Get customers
	 * 
	 * @return Doctrine\Common\Collections\ArrayCollection<Stakeholders\ApiBundle\Entity\User>
	 */
	public function getCustomers() {
		return $this->customers;
	}
	
	/**
	 * Add customer
	 * 
	 * @param Stakeholders\ApiBundle\Entity\User $customer     
	 * @return Stakeholders\ApiBundle\Entity\User  	
	 */
	public function addCustomer($customer) {
		$this->customers->add($customer);
		
		return $this;
	}
	
	/**
	 * Remove customer
	 *
	 * @param Stakeholders\ApiBundle\Entity\User $customer
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function removeCustomer($customer) {
		$this->customers->removeElement($customer);
	
		return $this;
	}
	
}