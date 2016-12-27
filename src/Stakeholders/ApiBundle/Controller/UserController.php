<?php
namespace Stakeholders\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;

class UserController extends Controller
{
	/**
	 * @View()
	 */
	public function getUserAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		return $user;//$this->handleView($this->view($user, 200));
	}
}