<?php 
namespace Stakeholders\ClientBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use GuzzleHttp\Client;

class OAuthAuthenticator extends AbstractGuardAuthenticator
{
	protected $container;
	
	public function __construct($container)
	{
		$this->container = $container;
	}
	
	public function getCredentials(Request $request)
	{
		if (!$request->headers->get('X-Stakeholders-Client')) {
			return;
		}
		if (!$token = $request->headers->get('X-Stakeholders-Token')) {
			return;
		}
		if (!$refresh = $request->headers->get('X-Stakeholders-Refresh')) {
			return;
		}
		return array(
			'token' => $token,
			'refresh' => $refresh,
		);
	}
	
	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		$provider = $this->container->get('stakeholders_client.oauth_user_provider');
		$user = $provider->loadUserByToken($credentials);
		return $user;
	}
	
	public function checkCredentials($credentials, UserInterface $user)
	{
		return $user->getToken() === $credentials['token'];
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		return null;
	}
	
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$data = array(
			'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
		);
		return new JsonResponse($data, Response::HTTP_FORBIDDEN);
	}
	
	public function start(Request $request, AuthenticationException $authException = null)
	{
		$data = array(
			'message' => 'Authentication Required',
		);
		return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
	}
	
	public function supportsRememberMe()
	{
		return false;
	}
}