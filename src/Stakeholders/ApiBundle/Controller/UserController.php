<?php
namespace Stakeholders\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;

use GuzzleHttp\Client;

use Facebook\Facebook;

class UserController extends FOSRestController
{
	/**
	 * @View()
	 */
	public function getMeAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		$data = array(
			'id' => $user->getId(),
			'username' => $user->getUsername(),
			'profile_image' => $user->getPhoto(),
			'email' => $user->getEmail(),
			'first_name' => $user->getFirstName(),
			'last_name' => $user->getLastName(),
			'network' => $user->getNetwork(),
			'role' => $this->getUserRole($user->getRoles()),
			'account' => $user->getAccount()
		);
		return $data;
	}
	
	/**
	 * @View()
	 */
	public function getProfileAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('StakeholdersApiBundle:User')->find($id);
		if ($user) {
			return $this->getUserFields($user);
		}
	}
	
	public function fbOauthAction(Request $request)
	{
		
		
		/*$session = $request->getSession();
		if (isset($_GET['code'])) {
			file_put_contents('/tmp/token', $_GET['code']);
			$session->set('fb_auth_code', $_GET['code']);
			/*$client = new Client(array('base_uri' => 'https://graph.facebook.com'));
			try {
				$res = $client->get('/oauth/access_token', array(
					'query' => array(
						'grant_type' => 'authorization_code',
						'client_id' => $this->getParameter('fb_api_key'),
						'client_secret' => $this->getParameter('fb_api_secret'),
						'redirect_uri' => 'http://sh.4m-team.pro/v1/oauths/fb'
					)
				));
				$session = $request->getSession();
				return array(
						'code' => $session->get('fb_auth_code')
				);//$res->getBody()->getContents();
			} catch(\Exception $e) {
				return array('error' => $e->getMessage());
			}*
		}*/
	}
	
	/**
	 * @View()
	 */
	public function getFeedAction(Request $request, $network)
	{
		switch($network) {
			case 'google-plus':
				/*$baseUri = 'https://accounts.google.com';
				$oauthUri = '/o/oauth2/auth';
				$feedUri = '/plus/v1/people/me/activities/public';
				$params = array(
					'form_params' => array(
						'response_type' => 'code',
						'client_id' => $this->getParameter('google_api_key'),
						'redirect_uri' => 'http://sh.4m-team.pro/',//$this->getParameter('google_api_secret'),
						'scope' => 'https://www.googleapis.com/auth/plus.me'
					)
				);*/
				break;
			case 'facebook':
			default:
				/*$fb = new Facebook(array(
						'app_id' => $this->getParameter('fb_api_key'),
						'app_secret' => $this->getParameter('fb_api_secret'),
						'default_graph_version' => 'v2.8',
				));
				$helper = $fb->getRedirectLoginHelper();
				 
				$permissions = ['email']; // Optional permissions
				$loginUrl = $helper->getLoginUrl($this->generateUrl('sh_oauth_fb'), $permissions);
				$client = new Client();//array('base_uri' => $this->generateUrl('sh_app_root')));
				try {
					$res = $client->get($loginUrl);
					$session = $request->getSession();
					return $res->getBody()->getContents();
				} catch(\Exception $e) {
					return array('error' => $e->getMessage());
				}
				/*$client = new Client(array('base_uri' => 'https://graph.facebook.com'));
				try {
					$res = $client->get('/me', array(
						'query' => array(
							'access_token' => '1223551571026721|pAkYeA8nkIIkik_1dZDXsR4UHDg', //authorization_code						)
					)));
					$session = $request->getSession();
					return $res->getBody()->getContents();
				} catch(\Exception $e) {
					return array('error' => $e->getMessage());
				}
				/*$fb = new Facebook(array(
					'app_id' => $this->getParameter('fb_api_key'),
					'app_secret' => $this->getParameter('fb_api_secret'),
					'default_graph_version' => 'v2.8',
				));
				$helper = $fb->getRedirectLoginHelper();
				try {
					$accessToken = $helper->getAccessToken();
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
					return 'Graph returned an error: ' . $e->getMessage();
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
					return 'Facebook SDK returned an error: ' . $e->getMessage();
				}
				if (!isset($accessToken)) {
					if ($helper->getError()) {
						//header('HTTP/1.0 401 Unauthorized');
						return array(
							"Error: " . $helper->getError(),
							"Error Code: " . $helper->getErrorCode(),
							"Error Reason: " . $helper->getErrorReason(),
							"Error Description: " . $helper->getErrorDescription(),
						);
					} else {
						//header('HTTP/1.0 400 Bad Request');
						return 'Bad request';
					}
					exit;
				}
				/*$baseUri = 'https://graph.facebook.com';
				$oauthUri = '/oauth/access_token';
				$feedUri = '/v2.8/me/feed';
				$params = array(
					'query' => array(
						'grant_type' => 'public_profile',
						'client_id' => $this->getParameter('fb_api_key'),
						'client_secret' => $this->getParameter('fb_api_secret'),
						'redirect_uri' => 'http://sh.4m-team.pro/'
					)
				);
				$clientToken = '4537e406b6275e22f1d8bf49636f895b';*/
		}
		/*$client = new Client(array('base_uri' => $baseUri));
		try {
			$res = $client->get($oauthUri, $params);
			if ($res->getStatusCode() === 200) {
				$token = str_replace('access_token=', '', $res->getBody()->getContents());
				return $token;
				$res2 = $client->get($feedUri);
				return $res2->getBody()->getContents();
				
			} else {
				return array('error' => 'Token error');
			}
		} catch(\Exception $e) {
			return array('error' => $e->getMessage());
		}*/
		
	}
	
	/**
	 * 
	 * @View()
	 */
	public function getUsersListAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
			$users = $this->getDoctrine()
				->getManager()
				->getRepository('StakeholdersApiBundle:User')->findAll();
			if ($users) {
				$list = array();
				foreach ($users as $user) {
					$list[] = $this->getUserFields($user, true);
				}
				return $this->handleView($this->view($list, 200));
			}
		} else {
			return array();
		}
	}
	
	protected function getUserRole($roles)
	{
		if (in_array('ROLE_SUPER_ADMIN', $roles)) {
			return 'admin';
		}
		if (in_array('ROLE_AGENCY', $roles)) {
			return 'agency';
		}
		if (in_array('ROLE_INFLUENCER', $roles)) {
			return 'influencer';
		}
		if (in_array('ROLE_CUSTOMER', $roles)) {
			return 'customer';
		}
	}
	
	protected function getUserFields($user, $id = false)
	{
		$data = array(
			'username' => $user->getUsername(),
			'email' => $user->getEmail(),
			'profile_image' => $user->getPhoto(),
			'first_name' => $user->getFirstName(),
			'last_name' => $user->getLastName(),
			'network' => $user->getNetwork(),
			'brief' => $user->getBrief(),
			'role' => $this->getUserRole($user->getRoles())
		);
		$account = $user->getAccount();
		$fields = $account->getFields();
		$data['account'] = $account;
		if ($data['role'] !== 'admin') {
			$data['fields'] = array();
			foreach ($fields as $field) {
				$type = $field->getValueType();
				$f = array(
					'name' => $field->getName(),
					'value_type' => $type,
				);
				$val = $field->getValue();
				if ($type == 'json') {
					foreach (json_decode($val) as $i=>$item) {
						$f['value']['f_'.$i] = $item;
					}
				} elseif ($type == 'account') {
					$val = json_decode($val);
					$data['socials'][$f['name']] = array(
						'username' => $val->username,
						'link' => $val->link,
					);
				} else {
					$f['value'] = $val;
				}
				$data['fields'][] = $f;
			}
		}
		if ($data['role'] == 'influencer') {
			$data['customers'] = $user->getCustomers();
		}
		if ($id !== false) {
			$data['id'] = $user->getId();
		}
		return $data;
	}
}