<?php 
namespace Stakeholders\ApiBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseFOSUBProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class StakeholdersFOSUBUserProvider extends BaseFOSUBProvider
{
	public function connect(UserInterface $user, UserResponseInterface $response)
	{
		$property = $this->getProperty($response);
		$username = $response->getUsername();
		$existingUser = $this->userManager->findUserBy(array($property => $username));
		if (null !== $existingUser) {
			//
			$this->userManager->updateUser($existingUser);
		}
		$this->userManager->updateUser($user);
	}
	
	public function loadUserByOAuthUserResponse(UserResponseInterface $response)
	{
		$userEmail = $response->getEmail();
		$user = $this->userManager->findUserByEmail($userEmail);
		if (null === $user) {
			$username = $response->getRealName();
			$user = new User();
			$user->setUsername($username);
			//
			return $user;
		}
		$serviceName = $response->getResourceOwner()->getName();
		$setter = 'set' . ucfirst($serviceName) . 'AccessToken';
		$user->$setter($response->getAccessToken());
		return $user;
	}
}