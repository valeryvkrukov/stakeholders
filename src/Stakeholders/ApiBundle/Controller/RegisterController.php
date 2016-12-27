<?php
namespace Stakeholders\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

class RegisterController extends Controller
{
	/**
	 * @Route("/v1/user/register", name="sh_user_register")
	 * @Method({"POST"})
	 */
	public function registerUserAction(Request $request)
	{
		if (!$request->headers->get('X-Stakeholders-Client')) {
			throw new AccessDeniedException();
		}
		$username = $request->request->get('username', null);
		$email = $request->request->get('email', null);
		$password = $request->request->get('password', null);
		$firstName = $request->request->get('fname', null);
		$lastName = $request->request->get('lname', null);
		if ($username && $email && filter_var($email, FILTER_VALIDATE_EMAIL) && $password) {
			$userManager = $this->get('fos_user.user_manager');
			$user = $userManager->createUser();
			$user->setEnabled(true);
			$user->setUsername($username);
			$user->setEmail($email);
			$fullname = array();
			if ($firstName !== null) {
				$fullname[] = $firstName;
			}
			if ($lastName !== null) {
				$fullname[] = $lastName;
			}
			if (sizeof($fullname) > 0) {
				$user->setFullname(implode(' ', $fullname));
			}
			$user->setPlainPassword($password);
			$userManager->updateUser($user);
			return $user;
		} else {
			throw new InvalidArgumentException();
		}
	}
}