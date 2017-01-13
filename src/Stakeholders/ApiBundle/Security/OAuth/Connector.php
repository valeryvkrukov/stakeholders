<?php 
namespace Stakeholders\ApiBundle\Security\OAuth;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Connector extends FOSUBUserProvider
{
	protected $requestStack;
	
	public function injectRequestStack(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}
	
	public function connect(UserInterface $user, UserResponseInterface $response)
	{
		dump($user);
		dump($response);
		dump($this->requestStack->getMasterRequest());
		exit('connect');
	}
}