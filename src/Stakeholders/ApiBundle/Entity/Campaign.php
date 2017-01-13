<?php 
namespace Stakeholders\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("campaigns")
 * @ORM\Entity
 */
class Campaign
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
	 * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=false)
	 */
	protected $customer;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Stakeholders\ApiBundle\Entity\User")
	 * @ORM\JoinColumn(name="influencer_id", referencedColumnName="id", nullable=true)
	 */
	protected $influencer;
	
	/**
	 * @ORM\Column(name="title", type="string", nullable=false)
	 */
	protected $title;
	
	/**
	 * @ORM\Column(name="brief", type="text", nullable=false)
	 */
	protected $brief;
	
	/**
	 * @ORM\Column(name="payment_status", type="string", nullable=false)
	 */
	protected $paymentStatus = 'unpaid';
	
	/**
	 * @ORM\Column(name="status", type="string", nullable=false)
	 */
	protected $status = 'open';
	
	/**
	 *
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getCustomer() {
		return $this->customer;
	}
	
	/**
	 *
	 * @param unknown_type $customer        	
	 */
	public function setCustomer($customer) {
		$this->customer = $customer;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getInfluencer() {
		return $this->influencer;
	}
	
	/**
	 *
	 * @param unknown_type $influencer        	
	 */
	public function setInfluencer($influencer) {
		$this->influencer = $influencer;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 *
	 * @param unknown_type $title        	
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getBrief() {
		return $this->brief;
	}
	
	/**
	 *
	 * @param unknown_type $brief        	
	 */
	public function setBrief($brief) {
		$this->brief = $brief;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getPaymentStatus() {
		return $this->paymentStatus;
	}
	
	/**
	 *
	 * @param unknown_type $status        	
	 */
	public function setPaymentStatus($paymentStatus) {
		$this->paymentStatus = $paymentStatus;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 *
	 * @param unknown_type $status        	
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	
}