<?php
/**
 * The ajax function that syncs all the data and restores
 * all the settings to default.
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_AjaxCallback() {
	global $wpdb;
	
	$response = array('message' => '', 'reload' => 0);
	
	if( $_POST['LWLC_todo'] == 'LWLC_SyncData' ){
		LWLC_SyncData();
		$response['message'] = 'Your data has been Synced !';
		$response['reload'] = 1;
	}
	
	if( $_POST['LWLC_todo'] == 'LWLC_RestoreData' ){
		LWLC_RemoveData();
		LWLC_SettingsInit();
		$response['message'] = 'Your data has been restored !';
		$response['reload'] = 1;
	}
	
	header('Content-type: application/json');
  echo json_encode($response);
	die();
}

