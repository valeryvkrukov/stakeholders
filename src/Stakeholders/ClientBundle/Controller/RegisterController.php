<?php
namespace Stakeholders\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
	/**
	 * @Route("/register/{type}", name="sh_block_register", options={"expose"=true})
	 */
	public function registerAction(Request $request, $type)
	{
		if ($request->isMethod('POST')) {
			$data = json_decode($request->getContent());
			$fieldsGetter = $data->user_type == 'influencer'?'getInfluencerFields':'getCustomerFields';
			try {
				$client = new Client(array('base_uri' => $this->getParameter('api_url')));
				$res = $client->post($this->generateUrl('sh_user_register'), array(
					'json' => $this->$fieldsGetter($data),
					'headers' => array(
						'X-Stakeholders-Client' => 'main-client'
					)
				));
				if ($res->getStatusCode() === 200) {
					return new JsonResponse(array(
						'status' => 'ok',
						'user' => json_decode($res->getBody()->getContents()),
					));
				}
			} catch(\Exception $e) {
				return new JsonResponse(array(
					'status' => 'fail',
					'message' => $e->getMessage(),
				));
			}
		}
		if ($type == 'customer') {
			return $this->render('StakeholdersClientBundle:Register:customer-form.html.twig');
		} elseif ($type == 'influencer') {
			return $this->render('StakeholdersClientBundle:Register:influencer-form.html.twig');
		} else {
			return $this->render('StakeholdersClientBundle:Register:confirmation.html.twig');
		}
	}
	
	/**
	 * @Route("/confirmation", name="sh_block_confirmation", options={"expose"=true})
	 */
	public function confirmationAction()
	{
		return $this->render('StakeholdersClientBundle:Register:confirmation.html.twig');
	}
	
	protected function getInfluencerFields($data)
	{
		$fields = array(
			'username' => $data->username,
			'firstName' => $data->first_name,
			'lastName' => $data->last_name,
			'email' => $data->email,
			'brief' => isset($data->bio)?$data->bio:'',
			'password' => $data->password,
			'account' => array(
				'contactNumber' => $data->contact_number,
				'website' => isset($data->website)?$data->website:'',
				'fields' => array(
					'audience' => $data->audience,
					'prices' => isset($data->prices)?$data->prices:array(),
					'frequency' => isset($data->frequency)?$data->frequency:'',
					'socials' => isset($data->socials)?$data->socials:array(),
				)
			),
			'role' => 'influencer',
		);
		return $fields;
	}
	
	protected function getCustomerFields()
	{
		
	}
}