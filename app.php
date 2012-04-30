<?php 
	require 'tmhOAuth.php';
	require 'tmhUtilities.php';
	
	$username = "";  
		
	$tmhOAuth = new tmhOAuth(array(
	  'consumer_key'    => 'uq1haQ4HWEBiu4ZXQ7Qw',
	  'consumer_secret' => 'd5gjAeiSbuK6vZDNO22wnoPjdtITr4RVsEX3LGsnO4',
	));
	
	$here = tmhUtilities::php_self();
	session_start();
	
	
	if ( gettype($_SESSION['user']) == "object" ) { 
		$user = $_SESSION['user']; 
	} 
	
	if ( gettype($_SESSION['ids']) == "array" ) { 
		$ids = $_SESSION['ids']; 
	}
	
	function outputError($tmhOAuth) {
	  echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
	  tmhUtilities::pr($tmhOAuth);
	}
	
	// reset request?
	if ( isset($_REQUEST['wipe'])) {
	  session_destroy();
	  header("Location: {$here}");
	}
	// got an access token? 
	elseif ( isset($_SESSION['access_token']) ) { 
		
		$tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
		$tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];
	
		if ( $_SESSION['user'] == null ) : 
			$code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials')); 
			echo "The code from the access token request is $code"; 
			if ($code == 200) {
				$user = json_decode($tmhOAuth->response['response']); 
				$username = $user->screen_name; 
				$_SESSION['user'] = $user;
			} else {
			  outputError($tmhOAuth);
			}
		endif; 
		
	} 

	// being called back by Twitter, with request token ("oauth token") in hand
	elseif ( isset($_REQUEST['oauth_verifier']) ) { 
		  $tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
		  $tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];
		  
		 	// request for an access token, we've got the user's permission already 
		 	$code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
		   'oauth_verifier' => $_REQUEST['oauth_verifier']
		 	));
		 
		 	if ( $code == 200 ) { 
		 		$_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']); 
		 		unset($_SESSION['oauth']); 
		 		header("Location: {$here}"); 
		 } else {
		   outputError($tmhOAuth);
		 }
	} 
	
	// get a request token from Twitter, get back an "oauth token" on 200
	elseif ( isset( $_REQUEST['authenticate'] ) || isset( $_REQUEST['authorize']) ) { 
		$callback = isset($_REQUEST['oob']) ? 'oob' : $here;
				
		$params = array(
		  'oauth_callback'     => $callback
		);
		
		// request for a request token
		$code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params); 
		
		// request granted by Twitter 
		if ( $code == 200 ) { 
			// Twitter gives us the response in the oauth variable 
			$_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']); 
			// we make a URL out of it, supplying the credentials Twitter gave us
			$authurl = $tmhOAuth->url("oauth/authenticate", ""). "?oauth_token={$_SESSION['oauth']['oauth_token']}"; 
			header( "Location: " . $authurl ); 
		} else { 
			outputError($tmhOAuth);
		} 
	} 

/** 
 * Get extended information for the users who's IDs are passed 
 * 
 * @param $ids array A list of IDs 
 * @return $data array Similiar to what's stored in $user, but for followers of $user
*/ 
function get_followers( $ids ) { 
	global $tmhOAuth;
	
	if ( $_SESSION['followers'] != null ) { 
		return $_SESSION['followers']; 
	} else { 
		$user_ids = implode(",", $ids); 
		$code = $tmhOAuth->request( 'POST', $tmhOAuth->url('1/users/lookup.json', ''), array( 'user_id' => $user_ids ) ); 
		if ( $code == 200 ) {
			echo "The code from the followers is $code"; 
			$data = json_decode($tmhOAuth->response['response'], true);
			$_SESSION['followers'] = $data; 
			return $data;  
		} else { 
			outputError($tmhOAuth);
		} 	
	}
 
} 

/** 
 * Returns an array of user IDs; 
 * The ID's are users that are following the $user passed in 
 * 
 * @param $user object The user to get followers for 
 * @return $ids array Containg IDs of Twitter users
*/ 
function get_followers_ids( $user ) { 
	global $tmhOAuth; 
	
	if ( $_SESSION['ids'] == null ) : 
		$ids = array(); 
		$code = $tmhOAuth->request(	'GET', $tmhOAuth->url('1/followers/ids.json', ''), array( 'user_id' => $user->id ) ); 
												
		if ( $code == 200 ) {
			$data = json_decode($tmhOAuth->response['response'], true);
			$ids = array_merge( $ids, $data['ids']); 
			$_SESSION['ids'] = $ids; 
			var_dump($_SESSION['ids']); 
			return $ids; 
		} else { 
			outputError($tmhOAuth);
		} 
	endif; 
} 

/** 
 * Similiar to get followers, instead gets data for just 1 follower
 * 
 * @param $id string
*/ 
function get_data( $id ) { 
	global $tmhOAuth; 
	$code = $tmhOAuth->request( 'POST', $tmhOAuth->url('1/users/lookup.json', ''), array( 'user_id' => $id ) );
	if ( $code == 200 ) {
		$data = json_decode($tmhOAuth->response['response']);
		return $data; 
	} else { 
		outputError($tmhOAuth);
	} 	
} 

if ( !empty( $_POST ) && !is_null( $_POST ) ) { 
	extract( $_POST ); //imports $id; 
	$data = get_data($id); 
	exit(json_encode($data)); 
} 
















