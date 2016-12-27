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
	 * @Route("/register", name="sh_block_register", options={"expose"=true})
	 */
	public function registerAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			$client = new Client(array('base_uri' => $this->getParameter('api_url')));
			
		}
		return $this->render('StakeholdersClientBundle:Register:form.html.twig');
	}
	
	/**
	 * @Route("/confirmation", name="sh_block_confirmation", options={"expose"=true})
	 */
	public function confirmationAction()
	{
		return null;
	}
}