<?php
namespace Stakeholders\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/block")
 */
class BlockController extends Controller
{
	/**
	 * @Route("/", name="sh_block_app", options={"expose"=true})
	 */
	public function appAction()
	{
		//$user = $this->get('security.token_storage')->getToken()->getUser();
		//var_dump($user);die();
		/*if (!$user) {
			$array = array('success' => false);
			$response = new Response(json_encode($array), 401);
			$response->headers->set('Content-Type', 'application/json');
			
			return $response;
		}*/
		return $this->render('StakeholdersClientBundle:Block:app.html.twig');
	}
	
	/**
	 * @Route("/header", name="sh_block_header", options={"expose"=true})
	 */
	public function headerAction()
	{
		return $this->render('StakeholdersClientBundle:Block:header.html.twig');
	}
	
	/**
	 * @Route("/sidebar", name="sh_block_sidebar", options={"expose"=true})
	 */
	public function sidebarAction()
	{
		return $this->render('StakeholdersClientBundle:Block:sidebar.html.twig');
	}
	
	/**
	 * @Route("/mobile-controls", name="sh_block_mobile_controls", options={"expose"=true})
	 */
	public function mobileControlsAction()
	{
		return $this->render('StakeholdersClientBundle:Block:mobile_controls.html.twig');
	}
	
	/**
	 * @Route("/footer", name="sh_block_footer", options={"expose"=true})
	 */
	public function footerAction()
	{
		return $this->render('StakeholdersClientBundle:Block:footer.html.twig');
	}
	
}