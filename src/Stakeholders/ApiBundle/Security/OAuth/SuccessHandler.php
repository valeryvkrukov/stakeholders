<?php 
namespace Stakeholders\ApiBundle\Security\OAuth;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class SuccessHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
	protected $router;
	
	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		$referer = $request->headers->get('referer');
		var_dump($referer);die();
	}
	
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$request->getSession()->set('login_error', $error);
		return new RedirectResponse($this->router->generate('login_route'));
	}
}