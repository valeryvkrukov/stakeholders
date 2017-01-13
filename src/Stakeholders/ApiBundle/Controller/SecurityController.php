<?php
namespace Stakeholders\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
	/**
	 * @Route("/oauth/v2/auth_login", name="sh_oauth_login")
	 */
	public function loginAction()
	{
		$request = $this->getRequest();
		$session = $request->getSession();
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		if ($session->has('_security.target_path')) {
			if (false !== strpos($session->get('_security.target_path'), $this->generateUrl('fos_oauth_server_authorize'))) {
				$session->set('_fos_oauth_server.ensure_logout', true);
			}
		}
		return $this->render('StakeholdersApiBundle:Security:login.html.twig', array(
			'last_username' => $session->get(SecurityContext::LAST_USERNAME),
			'error' => $error,
		));
	}

}