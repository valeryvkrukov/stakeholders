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
	 * @ORM\ManyToOne(targetEntity="Stakeholders\ApiBundle\Entity\User")
	 * @ORM\JoinColumn(name="influencer_id", referencedColumnName="id", nullable=true)
	 */
	protected $influencer;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Stakeholders\ApiBundle\Entity\User")
	 * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=true)
	 */
	protected $customer;
	
	/**
	 * @ORM\Column(name="api_provider", type="string", nullable=false)
	 */
	protected $apiProvider;
	
	/**
	 * @ORM\Column(name="api_key", type="string", nullable=false)
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
	
	/**
	 * Get id
	 * 
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get influencer
	 * 
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function getInfluencer() {
		return $this->influencer;
	}
	
	/**
	 * Set influencer
	 * 
	 * @param Stakeholders\ApiBundle\Entity\User $influencer
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function setInfluencer($influencer) {
		$this->influencer = $influencer;
		
		return $this;
	}
	
	/**
	 * Get customer
	 * 
	 * @return Stakeholders\ApiBundle\Entity\User
	 */
	public function getCustomer() {
		return $this->customer;
	}
	
	/**
	 * Set customer
	 * 
	 * @param Stakeholders\ApiBundle\Entity\User $customer
	 * @return Stakeholders\ApiBundle\Entity\Account
	 */
	public function setCustomer($customer) {
		$this->customer = $customer;
		
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
