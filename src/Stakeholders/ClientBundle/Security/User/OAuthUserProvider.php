<?php
namespace Stakeholders\ClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Stakeholders\ClientBundle\Security\User\OAuthUser;
use GuzzleHttp\Client;

class OAuthUserProvider implements UserProviderInterface
{
	protected $container;
	
	public function __construct($container)
	{
		$this->container = $container;
	}
	
	public function loadUserByUsername($username)
	{
		//
	}
	
	public function loadUserByToken($token)
	{
		$data = $this->getOAuthUser($token);
		$user = new OAuthUser($data->username, $data->email, $data->roles, $token['token']);
		return $user;
	}
	
	public function refreshUser(UserInterface $user)
	{
		//$token = $user->getToken();
		//$data = $this->getOAuthUser(array('token' => $token));
		//$_user = new OAuthUser($data->username, $data->email, $data->roles, $token);
		return $user;
	}
	
	public function supportsClass($class)
	{
		return OAuthUser::class === $class;
	}
	
	protected function getOAuthUser($token)
	{
		$url = $this->container->get('router')->generate('get_me');
		$client = new Client(array('base_uri' => $this->container->getParameter('api_url')));
		try {
			$res = $client->get($url, array(
				'headers' => array(
					'Authorization' => 'Bearer '.$token['token'],
				),
			));
			if ($res->getStatusCode() === 200) {
				return json_decode($res->getBody()->getContents());
			}
		} catch(\Exception $e) {
			
		}
		return false;
	}
}