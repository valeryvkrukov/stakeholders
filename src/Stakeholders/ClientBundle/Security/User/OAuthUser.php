<?php
namespace Stakeholders\ClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class OAuthUser implements UserInterface, EquatableInterface
{
	protected $username;
	protected $email;
	protected $roles;
	protected $token;
	
	public function __construct($username, $email, $roles, $token)
	{
		$this->username = $username;
		$this->email = $email;
		$this->roles = $roles;
		$this->token = $token;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
	 */
	public function getRoles() 
	{
		return $this->roles;
	}

	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getPassword()
	 */
	public function getPassword() 
	{
		return null;
	}

	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getSalt()
	 */
	public function getSalt() 
	{
		return null;
	}

	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getUsername()
	 */
	public function getUsername() 
	{
		return $this->username;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
	 */
	public function eraseCredentials() 
	{
		
	}

	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\User\EquatableInterface::isEqualTo()
	 */
	public function isEqualTo(UserInterface $user) 
	{
		if (!$user instanceof OAuthUser) {
			return false;
		}
		
		if ($this->username !== $user->getUsername()) {
			return false;
		}
		
		if ($this->email !== $user->getEmail()) {
			return false;
		}
		
		return true;
	}

}