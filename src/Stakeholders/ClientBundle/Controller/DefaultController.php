<?php

namespace Stakeholders\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use GuzzleHttp\Client;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="sh_app_root", options={"expose"=true})
     */
    public function indexAction()
    {
        return $this->render('StakeholdersClientBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/me", name="sh_me", options={"expose"=true})
     */
    public function meAction(Request $request)
    {
    	//if ($request->headers->has('X-Stakeholders-Token')) {
    		$client = new Client(array('base_uri' => $this->getParameter('api_url')));
    		try {
    			$res = $client->get($this->generateUrl('get_user'), array(
    				'headers' => array(
    					'Authorization' => 'Bearer '.$request->headers->get('X-Stakeholders-Token')
    				)
    			));
    			if ($res->getStatusCode() === 200) {
    				$user = json_decode($res->getBody()->getContents());
    			}
    		} catch(\Exception $e) {
    			return new JsonResponse(array('error' => $e->getMessage()));
    		}
    		return new JsonResponse($user);
    	/*} else {
    		throw new AccessDeniedException();
    	}*/
    }
}
