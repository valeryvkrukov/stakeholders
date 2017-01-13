<?php

namespace Stakeholders\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use GuzzleHttp\Client;

use Facebook\Facebook;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="sh_app_root", options={"expose"=true})
     */
    public function indexAction()
    {
    	$fbAppId = $this->getParameter('fb_api_key');
    	$googleAppId = $this->getParameter('google_api_key');
        return $this->render('StakeholdersClientBundle:Default:index.html.twig', array(
        	'fbAppId' => $fbAppId,
        	'googleAppId' => $googleAppId,
        ));
    }
    
    /**
     * @Route("/me", name="sh_me", options={"expose"=true})
     */
    public function meAction(Request $request)
    {
    	//if ($request->headers->has('X-Stakeholders-Token')) {
    		$client = new Client(array('base_uri' => $this->getParameter('api_url')));
    		try {
    			$res = $client->get($this->generateUrl('get_me'), array(
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
    
    /**
     * @Route("/access/oauth/fb", name="sh_oauth_fb")
     */
    public function oauthFbAction()
    {
    	if (!session_id()) {
    		session_start();
    	}
    	$fb = new Facebook([
    			'app_id' => '1223551571026721',
    			'app_secret' => '0fc7586b2a4f3de49b84eadf01056807',
    			'default_graph_version' => 'v2.8',
    	]);
    	
    	$helper = $fb->getRedirectLoginHelper();
    	
    	try {
    		$accessToken = $helper->getAccessToken();
    	} catch(Facebook\Exceptions\FacebookResponseException $e) {
    		// When Graph returns an error
    		echo 'Graph returned an error: ' . $e->getMessage();
    		exit;
    	} catch(Facebook\Exceptions\FacebookSDKException $e) {
    		// When validation fails or other local issues
    		echo 'Facebook SDK returned an error: ' . $e->getMessage();
    		exit;
    	}
    	
    	if (! isset($accessToken)) {
    		if ($helper->getError()) {
    			header('HTTP/1.0 401 Unauthorized');
    			echo "Error: " . $helper->getError() . "\n";
    			echo "Error Code: " . $helper->getErrorCode() . "\n";
    			echo "Error Reason: " . $helper->getErrorReason() . "\n";
    			echo "Error Description: " . $helper->getErrorDescription() . "\n";
    		} else {
    			header('HTTP/1.0 400 Bad Request');
    			echo 'Bad request';
    		}
    		exit;
    	}
    	// Logged in
    	//echo '<h3>Access Token</h3>';
    	//var_dump($accessToken->getValue());
    	
    	// The OAuth 2.0 client handler helps us manage access tokens
    	$oAuth2Client = $fb->getOAuth2Client();
    	
    	// Get the access token metadata from /debug_token
    	//$tokenMetadata = $oAuth2Client->debugToken($accessToken);
    	//echo '<h3>Metadata</h3>';
    	//var_dump($tokenMetadata);
    	
    	// Validation (these will throw FacebookSDKException's when they fail)
    	//$tokenMetadata->validateAppId($config['app_id']);
    	// If you know the user ID this access token belongs to, you can validate it here
    	//$tokenMetadata->validateUserId('123');
    	//$tokenMetadata->validateExpiration();
    	
    	if (! $accessToken->isLongLived()) {
    		// Exchanges a short-lived access token for a long-lived one
    		try {
    			$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    		} catch (Facebook\Exceptions\FacebookSDKException $e) {
    			echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    			exit;
    		}
    	
    		echo '<h3>Long-lived</h3>';
    		var_dump($accessToken->getValue());die();
    	}
    	
    	$_SESSION['fb_access_token'] = (string) $accessToken;
    	die();
    }
    
    /**
     * @Route("/test", name="sh_test")
     */
    public function testAction()
    {
    	if (!session_id()) {
    		session_start();
    	}
    	$fb = new Facebook([
    			'app_id' => '1223551571026721',
    			'app_secret' => '0fc7586b2a4f3de49b84eadf01056807',
    			'default_graph_version' => 'v2.8',
    	]);
    	
    	$helper = $fb->getRedirectLoginHelper();
    	
    	$permissions = ['email']; // Optional permissions
    	$loginUrl = $helper->getLoginUrl('http://sh.4m-team.pro/access/login/check-facebook', $permissions);
    	
    	$client = new Client();
    	$res = $client->get($loginUrl, array(
    			'allow_redirects' => true
    	));
    	var_dump($res->getBody()->getContents());
    	echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    	die();
    }
}
