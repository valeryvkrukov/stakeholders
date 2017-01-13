<?php
namespace Stakeholders\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("accounts")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Account
{
	/**
	 * @var integer
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\OneToOne(targetEntity="Stakeholders\ApiBundle\Entity\User", inversedBy="account")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;
	
	/**
	 * @ORM\OneToMany(targetEntity="Stakeholders\ApiBundle\Entity\Field", mappedBy="account")
	 */
	protected $fields;
	
	/**
	 * @ORM\Column(name="api_provider", type="string", nullable=false)
	 */
	protected $apiProvider;
	
	/**
	 * @ORM\Column(name="api_key", type="string", nullable=true)
	 */
	protected $apiKey;
	
	/**
	 * @ORM\Column(name="api_secret", type="string", nullable=true)
	 */
	protected $apiSecret;
	
	/**
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	protected $createdAt;
	
	public function __construct()
	{
		$this->fields = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * Get id
	 * 
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get user
	 * 
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * Set user
	 * 
	 * @param Stakeholders\ApiBundle\Entity\User $user
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function setUser($user) {
		$this->user = $user;
		
		return $this;
	}
	
	/**
	 * Get fields
	 * 
	 * @return Stakeholders\ApiBundle\Entity\Field
	 */
	public function getFields() {
		return $this->fields;
	}
	
	/**
	 * Add field
	 * 
	 * @param Stakeholders\ApiBundle\Entity\Field $field
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function addField($field) {
		$this->fields->add($field);
		
		return $this;
	}
	
	/**
	 * Remove field
	 *
	 * @param Stakeholders\ApiBundle\Entity\Field $field
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function removeField($field) {
		$this->fields->removeElement($field);
	
		return $this;
	}
	
	/**
	 * Get apiProvider
	 * 
	 * @return string
	 */
	public function getApiProvider() {
		return $this->apiProvider;
	}
	
	/**
	 * Set apiProvider
	 * 
	 * @param string $apiProvider
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function setApiProvider($apiProvider) {
		$this->apiProvider = $apiProvider;
		
		return $this;
	}
	
	/**
	 * Get apiKey
	 * 
	 * @return string
	 */
	public function getApiKey() {
		return $this->apiKey;
	}
	
	/**
	 * Set apiKey
	 * 
	 * @param string $apiKey
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function setApiKey($apiKey) {
		$this->apiKey = $apiKey;
		
		return $this;
	}
	
	/**
	 * Get apiSecret
	 * 
	 * @return string
	 */
	public function getApiSecret() {
		return $this->apiSecret;
	}
	
	/**
	 * Set apiSecret
	 * 
	 * @param string $apiSecret
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function setApiSecret($apiSecret) {
		$this->apiSecret = $apiSecret;
		
		return $this;
	}
	
	/**
	 * Get createdAt
	 * 
	 * @return \DateTime
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}
	
	/**
	 * Set createdAt
	 * 
	 * @ORM\PrePersist()
	 */
	public function setCreatedAt() {
		$this->createdAt = new \DateTime();
	}
	
}
