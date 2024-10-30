<?php
/*
Plugin Name: Lightweight Likes Counter
Description: This plugin gets the raw counts of the amount of likes a posts has on Twitter, Facebook and Google+ whithout javascript. Like buttons do use javascript.
Version: 0.3.1
Author: Saif Bechan
Author URI: http://saifbechan.com
License: GPLv2
*/

/*
 Copyright 2011  Saif Bechan  (email : support@beansandpixels.com)
 
 This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'LWLC_PLUGIN_URL' , plugin_dir_url( __FILE__ ));
define( 'LWLC_BASENAME'   , plugin_basename( __FILE__ ) );
define( 'LWLC_BASEFOLDER' , plugin_basename( dirname( __FILE__ ) ) );
define( 'LWLC_FILENAME'   , str_replace( LWLC_BASEFOLDER.'/', '', plugin_basename(__FILE__) ) );

require_once('inc/LWLC_admin.php');
require_once('inc/LWLC_functions.php');
require_once('inc/LWLC_ajax.php');

register_activation_hook( __FILE__, 'LWLC_SettingsInit');
register_deactivation_hook( __FILE__, 'LWLC_RemoveData');


/**
 * Load all the data the admin panel needs and initialize the settings.
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
if ( is_admin() ){
	add_action('admin_head', 'LWLC_AjaxScript');
	add_action('wp_ajax_my_action', 'LWLC_AjaxCallback');
	add_action('admin_menu', 'LWLC_PluginMenu');
	add_action('admin_init', 'LWLC_PluginInit');
}else{
	if(get_option('LWLC_styling')){
		wp_register_style('LWLC_style.css', LWLC_PLUGIN_URL . 'css/LWLC_style.css');
		wp_enqueue_style('LWLC_style.css');
	}
}

/**
 * Create the options page
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_PluginMenu() {
	add_options_page('Lightweight Likes Counter Options', 'LW Likes Counter', 'manage_options', 'lightweight-likes-counter', 'LWLC_Options');
}

/**
 * Register all the settings needed
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_PluginInit(){
	register_setting( 'LWLC_Options', 'LWLC_interval');
	register_setting( 'LWLC_Options', 'LWLC_lastsync');
	register_setting( 'LWLC_Options', 'LWLC_styling');
	register_setting( 'LWLC_Options', 'LWLC_results');
	register_setting( 'LWLC_Options', 'LWLC_enableshare');
	register_setting( 'LWLC_Options', 'LWLC_sharenames');
}


/**
 * This is the primary function that is loaded in the webite
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_FetchData(){
	$LWLC_counts = LWLC_FetchLikeCounts(get_the_ID(), false);
	require('inc/LWLC_template.php');
}

/**
 * Add javascript to the admin panel
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_AjaxScript() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($){
	jQuery('.LWLC_WarningLink a').click(function(){
		jQuery(this).hide();
		jQuery('.LWLC_WarningLink .LWLC_AjaxAction').show();
		return false;
	});
	
	jQuery('.LWLC_AjaxAction').click(function(){
		jQuery('.LWLC_AjaxAction').attr("disabled", true);
		var data = {
			action:'my_action',
			LWLC_todo:jQuery(this).attr('id')
		};

		jQuery.ajax({
			url:ajaxurl,
			type:'post',
			data:data,
			dataType:'json',
			success:function(response) {
			  alert(response['message']);
			  jQuery('.LWLC_AjaxAction').attr("disabled", false);
			  if(response['reload']){
			    location.reload(true);
			 }
		  },
		});
	});
});
</script>
<?php
}


/**
 * Small script to share links with the networking sites
 *
 * @since 0.3
 * @author support@beansandpixels.com
 */
function LWLC_EnqueueLikeScripts() {
	
$LWLC_sharenames = get_option('LWLC_sharenames');

?>
<script type="text/javascript">
jQuery(document).ready(function($){
	
	<?php
	/**
	 * Variables used 
	 *
	 * @since 0.3
	 * @author support@beansandpixels.com
	 */
	?>
	var LWLC_urls = [];
	var LWLC_LoadedUrls = [];
	
	var LWLC_ClickedID  = '';
	var LWLC_LinksClass = '';
	var LWLC_CountClass = '';
	var LWLC_ShowLinks  = false;
		
	<?php
	/**
	 * Functions for hiding and showing the different buttons
	 *
	 * @since 0.3
	 * @author support@beansandpixels.com
	 */
	?>
	jQuery('.LWLC_ShareCount a').click(function(e){
		if( LWLC_LoadedUrls.length == LWLC_urls.length ){
			LWLC_FireScript();
		}
		
		if(LWLC_ShowLinks){
			jQuery(LWLC_LinksClass).hide();
		}
		
		LWLC_ClickedID  = jQuery(this).parents('.LWLC_ShareCount').attr('id');
		LWLC_LinksClass = '#' + LWLC_ClickedID + '.LWLC_ShareLinks';
		LWLC_CountClass = '#' + LWLC_ClickedID + '.LWLC_ShareCount';
		
		jQuery(LWLC_LinksClass).show();
		
		LWLC_ShowLinks = true;
		
		e.stopPropagation();
	});
	
	jQuery('html').click(function() {
		jQuery(LWLC_LinksClass).hide();
		LWLC_ShowLinks = false;
	});
	
	jQuery(LWLC_LinksClass).click(function(e){
		e.stopPropagation();
	});
	
	<?php
	/**
	 * The function that loads all the scripts
	 *
	 * @since 0.3.1
	 * Introduced te map function
	 *
	 * @since 0.3
	 * @author support@beansandpixels.com
	 */
	?>
	function LWLC_FireScript(){
		LWLC_urls = [
			'//platform.twitter.com/widgets.js',
			'http://static.ak.fbcdn.net/connect.php/js/FB.Share',
			'https://apis.google.com/js/plusone.js',
			'http://platform.linkedin.com/in.js'
		];
		
		jQuery.map( LWLC_urls, function( LWLC_urls ){
			jQuery.getScript( LWLC_urls, function(){
				LWLC_LoadedUrls.push( LWLC_urls );
				if( LWLC_LoadedUrls.length == LWLC_urls.length ){
					<?php //TODO insert action when all files are loaded ?>
				}
			});
		});
	}
});

</script>
<?php
}
if(get_option('LWLC_enableshare')){
	add_action('wp_footer', 'LWLC_EnqueueLikeScripts');
}

/**
 * Add option link to the plugin page
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_FilterPluginMeta($links, $file) {
	if ( $file == LWLC_BASENAME ) {
		array_unshift(
			$links,
			sprintf( '<a href="options-general.php?page=%s">%s</a>', LWLC_FILENAME, __('Settings') )
		);
	}
	return $links;
}
global $wp_version;
if ( version_compare( $wp_version, '2.8alpha', '>' ) )
	add_filter( 'plugin_row_meta', 'LWLC_FilterPluginMeta', 10, 2 );
add_filter( 'plugin_action_links', 'LWLC_FilterPluginMeta', 10, 2 );
