<?php
/**
 * This function returns the amount of likes one post has.
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_FetchLikeCounts($id,$force){
	// Get the post metadata
	$LWLC_counts = unserialize( get_post_meta( $id, '_LWLC_counts', true ) );
	
	// Check if the metadata is not empty
	if($LWLC_counts['updatetime'] == ''){
	//There is no metadata so we have to create new ones
			// Get the array with the counts using the permlink
			//$LWLC_counts = array( 'twitter' => 10,'facebook' => 10,'google+' => 10,'linkedin' => 10,'sum' => 10,'updatetime' => mktime() );
			$LWLC_counts = LWLC_GetCounts( get_permalink($id) );
			add_post_meta( $id, '_LWLC_counts', serialize( $LWLC_counts ) );
	}else{
	// There is metadata to work with
		//Check the time, do we need to update or not
		if ( $LWLC_counts['updatetime'] < (time() - get_option('LWLC_interval')) || $force ){
		// The time has expired so we need to get new values
		  //$LWLC_counts = array( 'twitter' => 11,'facebook' => 11,'google+' => 11,'linkedin' => 11,'sum' => 10,'updatetime' => mktime() );
			$LWLC_counts = LWLC_GetCounts( get_permalink($id) );
			update_post_meta( $id, '_LWLC_counts', serialize( $LWLC_counts ) );
		}
	}
	
	// return the data
	return $LWLC_counts;
}

/**
 * Sync all the posts. This is called by the Ajax function
 *
 * @since 0.3
 * - Added option to force a sync
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_SyncData(){
	
	// Get all the posts
	$myposts = query_posts('numberposts=-1&post_type=post&post_status=any');
	
	foreach( $myposts as $post ) : setup_postdata($post);
		LWLC_FetchLikeCounts($post->ID, true);
	endforeach;
	
	update_option('LWLC_lastsync', time());
}

/**
 * Initialize all the settings.
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_SettingsInit(){
	add_option('LWLC_interval', 43200);
	add_option('LWLC_styling', 1);
	add_option('LWLC_results', array('seperate' => 1, 'combined' => 0));
	add_option('LWLC_lastsync', 0);
	add_option('LWLC_enableshare', 1);
	add_option('LWLC_sharenames', array('twitter' => '', 'linkedin' => ''));
}

/**
 * Restore all the settings to the default state
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_RemoveData(){
	delete_option('LWLC_interval');
	delete_option('LWLC_styling');
	delete_option('LWLC_results');
	delete_option('LWLC_lastsync');
	delete_option('LWLC_enableshare');
	delete_option('LWLC_sharenames');
	
	$allposts = get_posts('numberposts=-1&post_type=post&post_status=any');
	foreach( $allposts as $postinfo ){
		delete_post_meta( $postinfo->ID, '_LWLC_counts' );
	}
}

/**
 * Get all the likes from the different social netoworks
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_GetCounts($url){
 		
	$array = array();
	
	/**
	 * Gets the data from Twitter
	 *
	 * @since 0.1
	 * @author support@beansandpixels.com
	 */
	$json_string = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url=' . $url);
	$json = json_decode($json_string, true);

	$array['twitter'] = intval( $json['count'] );
	
	
	/**
	 * Gets the data from Facebook
	 *
	 * @since 0.1
	 * @author support@beansandpixels.com
	 */
	$json_string = file_get_contents('http://graph.facebook.com/?ids=' . $url);
	$json = json_decode($json_string, true);

	$array['facebook'] = intval( $json[$url]['shares'] );
	
	
	/**
	 * Gets the data from Google+
	 *
	 * @since 0.1
	 * @author support@beansandpixels.com
	 */
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	$curl_results = curl_exec ($curl);
	curl_close ($curl);

	$json = json_decode($curl_results, true);

	$array['google+'] = intval( $json[0]['result']['metadata']['globalCounts']['count'] );
	
	/**
	 * Gets the data from linkedin
	 *
	 * @since 0.2
	 * @author support@beansandpixels.com
	 */
	$json_string = file_get_contents('http://www.linkedin.com/cws/share-count?url=' . $url);
	$json = json_decode($json_string, true);

	$array['linkedin'] = intval( $json['count'] );
	
	/**
	 * - Get the combined counts
	 * - Store the time of the update
	 * 
	 * @since 0.1
	 * @author support@beansandpixels.com
	 */
	$array['combined']   = array_sum($array);
	$array['updatetime'] = time();
	
	return $array;
}
