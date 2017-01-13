<?php 
namespace Stakeholders\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("fields")
 * @ORM\Entity
 */
class Field
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
	 * @ORM\ManyToOne(targetEntity="Stakeholders\ApiBundle\Entity\Account", inversedBy="fields")
	 * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
	 */
	protected $account;
	
	/**
	 * @ORM\Column(name="name", type="string", nullable=false)
	 */
	protected $name;
	
	/**
	 * @ORM\Column(name="value_type", type="string", nullable=false, options={"default": "string"}) 
	 */
	protected $valueType;
	
	/**
	 * @ORM\Column(name="value", type="text", nullable=true)
	 */
	protected $value;
	
	/**
	 * Get id
	 *
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
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
	 * @return Stakeholders\ApiBundle\Entity\Field
	 */
	public function setAccount($account) {
		$this->account = $account;
		
		return $this;
	}
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name
	 * @return Stakeholders\ApiBundle\Entity\Field
	 */
	public function setName($name) {
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Get valueType
	 *
	 * @return string
	 */
	public function getValueType() {
		return $this->valueType;
	}
	
	/**
	 * Set valueType
	 *
	 * @param string $valueType
	 * @return Stakeholders\ApiBundle\Entity\Field
	 */
	public function setValueType($valueType) {
		$this->valueType = $valueType;
		
		return $this;
	}
	
	/**
	 * Get value
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Set value
	 *
	 * @param mixed $value
	 * @return Stakeholders\ApiBundle\Entity\Field
	 */
	public function setValue($value) {
		$this->value = $value;
		
		return $this;
	}
	
}