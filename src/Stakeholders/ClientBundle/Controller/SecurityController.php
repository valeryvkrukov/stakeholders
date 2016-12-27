<?php
namespace Stakeholders\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;

class SecurityController extends Controller
{
	/**
	 * @Route("/login", name="sh_block_login", options={"expose"=true})
	 */
	public function loginAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			$client = new Client(array('base_uri' => $this->getParameter('api_url')));
			try {
				$res = $client->post($this->generateUrl('fos_oauth_server_token'), array(
					'json' => array(
						'grant_type' => 'password',
						'client_id' => $this->getParameter('api_client_id'),
						'client_secret' => $this->getParameter('api_client_secret'),
						'username' => $request->request->get('username'),
						'password' => $request->request->get('password'),
					),
				));
				if ($res->getStatusCode() === 200) {
					$result = json_decode($res->getBody()->getContents());
					/*$session = $request->getSession();
					if ($session->has('_security.target_path')) {
						if (false !== strpos($session->get('_security.target_path'), $this->generateUrl('fos_oauth_server_authorize'))) {
							$session->set('_fos_oauth_server.ensure_logout', true);
						}
					}*/
					return new JsonResponse($result);
				}
			} catch(\Exception $e) {
				return new JsonResponse(array('error' => $e->getMessage()));
			}
		}
		return $this->render('StakeholdersClientBundle:Security:login.html.twig');
	}
	
	/**
	 * @Route("/logout", name="sh_block_logout", options={"expose"=true})
	 */
	public function logoutAction(Request $request)
	{
		try {
			$request->getSession()->invalidate();
		} catch (\Exception $e) {
			return new JsonResponse(array('error' => 'Sign out error'));
		}
		return new JsonResponse(array('status' => 'Signed Out'));
	}
}