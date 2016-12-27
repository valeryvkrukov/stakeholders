<?php
namespace Stakeholders\ApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("users")
 * @ORM\Entity
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
	
}