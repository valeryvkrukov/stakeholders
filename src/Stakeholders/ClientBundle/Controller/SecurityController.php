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
					return new JsonResponse($result);
				}
			} catch(\Exception $e) {
				return new JsonResponse(array('error' => $e->getMessage()));
			}
		}
		return $this->render('StakeholdersClientBundle:Security:login.html.twig');
	}
	
	/**
	 * @Route("/social-auth/{network}", name="sh_block_social_auth", options={"expose"=true})
	 */
	public function socialAuthAction(Request $request, $network)
	{
		$client = new Client(array('base_uri' => $this->getParameter('api_url')));
		try {
			//if ($network == 'fb') {
				$res = $client->post($this->generateUrl('sh_social_login'), array(
					'form_params' => array(
						'username' => $request->request->get('id'),
						'profile_image' => $request->request->get('id'),
						'email' => $request->request->get('email'),
						'first_name' => $request->request->get('first_name'),
						'last_name' => $request->request->get('last_name'),
						'role' => $request->request->get('role'),
						'network' => $network,
					)
				));
			//}
			if ($res->getStatusCode() === 200) {
				$result = json_decode($res->getBody()->getContents());
				try {
					$client2 = new Client(array('base_uri' => $this->getParameter('api_url')));
					$res2 = $client2->post($this->generateUrl('fos_oauth_server_token'), array(
						'json' => array(
							'grant_type' => 'password',
							'client_id' => $this->getParameter('api_client_id'),
							'client_secret' => $this->getParameter('api_client_secret'),
							'username' => $result->username,
							'password' => $result->password,
						),
					));
					if ($res2->getStatusCode() === 200) {
						$result2 = json_decode($res2->getBody()->getContents());
						$data = array('userData' => $result, 'tokens' => $result2);
						return new JsonResponse($data);
					}
				} catch(\Exception $e) {
					return new JsonResponse(array('error' => $e->getMessage()));
				}
			} else {
				return new JsonResponse(array('error' => $res->getStatusCode()));
			}
		} catch(\Exception $e) {
			return new JsonResponse(array('error' => $e->getMessage()));
		}
		return new JsonResponse(array('error' => 'Network '.$network.' is not supported...'));
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