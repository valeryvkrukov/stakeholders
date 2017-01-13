<?php
namespace Stakeholders\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

use Stakeholders\ApiBundle\Entity\Account;
use Stakeholders\ApiBundle\Entity\Field;

class RegisterController extends Controller
{
	/**
	 * @Route("/register", name="sh_user_register")
	 * @Method({"POST"})
	 */
	public function registerUserAction(Request $request)
	{
		if (!$request->headers->get('X-Stakeholders-Client')) {
			throw new AccessDeniedException();
		}
		$data = json_decode($request->getContent());
		try {
			$username = $data->username;
			$email = $data->email;
			$password = $data->password;
			$firstName = $data->firstName;
			$lastName = $data->lastName;
		} catch(\Exception $e) {
			throw new InvalidArgumentException();
		}
		if ($username && $email && filter_var($email, FILTER_VALIDATE_EMAIL) && $password) {
			/*$check = $this->getDoctrine()->getManager()->getRepository('StakeholdersApiBundle:User')->findBy(array(
				'username' => $username
			));
			if ($check) {
				
			}*/
			//return new JsonResponse($data);
			$userManager = $this->get('fos_user.user_manager');
			$user = $userManager->createUser();
			$user->setEnabled(true);
			$user->setUsername($username);
			$user->setEmail($email);
			$user->setFirstName($firstName);
			$user->setLastName($lastName);
			$user->setBrief((isset($data->brief)?$data->brief:''));
			$user->setPlainPassword($password);
			$role = $data->role == 'customer'?'ROLE_CUSTOMER':'ROLE_INFLUENCER';
			$user->addRole($role);
			$userManager->updateUser($user);
			if ($role === 'ROLE_CUSTOMER') {
				$this->createCustomerUser($user);
			} else {
				$this->createInfluencerUser($user, $data);
			}
			$userManager->updateUser($user);
			return new JsonResponse(array(
				'username' => $user->getUsername(),
				'email' => $user->getEmail(),
				'first_name' => $user->getFirstName(),
				'last_name' => $user->getLastName(),
				'network' => $user->getNetwork(),
				'password' => $password,
				'brief' => $user->getBrief(),
				'role' => $data->role
			));
		} else {
			throw new InvalidArgumentException();
		}
	}
	

	/**
	 * @Route("/social-login", name="sh_social_login")
	 * @Method({"POST"})
	 */
	public function socialLoginAction(Request $request)
	{
		$username = $request->request->get('username');
		$network = $request->request->get('network');
		if ($username && $network) {
			$em = $this->getDoctrine()->getManager();
			$user = $em->getRepository('StakeholdersApiBundle:User')->findOneBy(array(
				'username' => $username,
				'network' => $network,
			));
			$userManager = $this->get('fos_user.user_manager');
			$password = substr(($this->get('fos_user.util.token_generator'))->generateToken(), 0, 16);
			if (!$user) {
				$user = $userManager->createUser();
				$user->setEnabled(true);
				$user->setUsername($username);
				$user->setEmail($request->request->get('email'));
				$user->setFirstName($request->request->get('first_name'));
				$user->setLastName($request->request->get('last_name'));
				$user->setNetwork($network);
				$role = $request->request->get('role') == 'customer'?'ROLE_CUSTOMER':'ROLE_INFLUENCER';
				$user->addRole($role);
				$user->setPlainPassword($password);
				$userManager->updateUser($user);
			} else {
				$user->setPlainPassword($password);
				$userManager->updateUser($user);
			}
			return new JsonResponse(array(
				'username' => $user->getUsername(),
				'email' => $user->getEmail(),
				'first_name' => $user->getFirstName(),
				'last_name' => $user->getLastName(),
				'network' => $user->getNetwork(),
				'password' => $password,
				'role' => $request->request->get('role')
			));
		} else {
			return new JsonResponse($username.' :: '.$network);
		}
	}
	
	/**
	 * @Route("/social-login", name="sh_upload_profile_image", options={"expose"=true})
	 * @Method({"POST"})
	 */
	public function uploadProfileImageAction(Request $request)
	{
		
	}
	
	protected function createCustomerUser($user, $api = null)
	{
		$account = new Account();
		$account->setUser($user);
		if (is_array($api)) {
			//
		} else {
			$account->setApiProvider('internal');
		}
		$em = $this->getDoctrine()->getManager();
		$em->persist($account);
		$em->flush();
	}
	
	protected function createInfluencerUser($user, $info)
	{
		$em = $this->getDoctrine()->getManager();
		$account = new Account();
		$account->setUser($user);
		$account->setApiProvider('internal');
		$em->persist($account);
		$fields = array();
		if (isset($info->account->website)) {
			$field = new Field();
			$field->setName('website');
			$field->setValueType('text');
			$field->setValue($info->account->website);
			$field->setAccount($account);
			$em->persist($field);
			$fields[] = $field;
		}
		if (isset($info->account->contactNumber)) {
			$field = new Field();
			$field->setName('contact_number');
			$field->setValueType('text');
			$field->setValue($info->account->contactNumber);
			$field->setAccount($account);
			$em->persist($field);
			$fields[] = $field;
		}
		if (isset($info->account->fields->frequency)) {
			$field = new Field();
			$field->setName('frequency');
			$field->setValueType('text');
			$field->setValue($info->account->fields->frequency);
			$field->setAccount($account);
			$em->persist($field);
			$fields[] = $field;
		}
		if (isset($info->account->fields->audience) && is_array($info->account->fields->audience)) {
			$field = new Field();
			$field->setName('audience');
			$field->setValueType('array');
			$field->setValue(json_encode($info->account->fields->audience));
			$field->setAccount($account);
			$em->persist($field);
			$fields[] = $field;
		}
		if (isset($info->account->fields->socials) && is_object($info->account->fields->socials)) {
			foreach ($info->account->fields->socials as $network => $data) {
				$field = new Field();
				$field->setName($network);
				$field->setValueType('account');
				$field->setValue(json_encode($data));
				$field->setAccount($account);
				$em->persist($field);
				$fields[] = $field;
			}
		}
		if (isset($info->account->fields->prices)) {
			$field = new Field();
			$field->setName('prices');
			$field->setValueType('array');
			$field->setValue(json_encode($info->account->fields->prices));
			$field->setAccount($account);
			$em->persist($field);
			$fields[] = $field;
		}
		foreach ($fields as $field) {
			$account->addField($field);
			$em->persist($account);
		}
		$em->flush();
	}
}