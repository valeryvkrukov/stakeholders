<?php
namespace Stakeholders\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class InfluencerController extends FOSRestController
{
	/**
	 * @View()
	 */
	public function getInfluencerCampaignsAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$campaigns = $em->getRepository('StakeholdersApiBundle:Campaign')->findBy(array(
			'influencer' => $id,
		));
		return $campaigns;
	}
	
	/**
	 * @View()
	 */
	public function getInfluencerCampaignDetailsAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$campaign = $em->getRepository('StakeholdersApiBundle:Campaign')->find($id);
		return $campaign;
	}
}