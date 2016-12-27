<?php
namespace Stakeholders\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/page")
 */
class PageController extends Controller
{
	/**
	 * @Route("/dashboard", name="sh_page_dashboard", options={"expose"=true})
	 */
	public function dashboardAction()
	{
		return $this->render('StakeholdersClientBundle:Page:dashboard.html.twig');
	}
	
	/**
	 * @Route("/user", name="sh_page_user_profile", options={"expose"=true})
	 */
	public function userProfileAction()
	{
		return $this->render('StakeholdersClientBundle:Page:user-profile.html.twig');
	}
	
	/**
	 * @Route("/confirmation", name="sh_page_confirmation", options={"expose"=true})
	 */
	public function confirmationAction()
	{
		return $this->render('StakeholdersClientBundle:Page:confirmation.html.twig');
	}
	
	/**
	 * @Route("/404", name="sh_page_404", options={"expose"=true})
	 */
	public function pageNotFoundAction()
	{
		return $this->render('StakeholdersClientBundle:Page:404.html.twig');
	}
	
	/**
	 * @Route("/500", name="sh_page_500", options={"expose"=true})
	 */
	public function serverErrorAction()
	{
		return $this->render('StakeholdersClientBundle:Page:500.html.twig');
	}
	
}