<?php
/*
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
			$data = json_decode($tmhOAuth->response['response'], true);
			$_SESSION['followers'] = $data; 
			return $data;  
		} else { 
			outputError($tmhOAuth);
		} 	
	}
 
} 


