<?php
/**
 * Creating the admin options page
 *
 * @since 0.1
 * @author support@beansandpixels.com
 */
function LWLC_Options(){ 

$LWLC_interval  = get_option('LWLC_interval');
$LWLC_styling   = get_option('LWLC_styling');
$LWLC_results   = get_option('LWLC_results');
$LWLC_lastsync  = get_option('LWLC_lastsync');
$LWLC_enableshare  = get_option('LWLC_enableshare');

?>
<div class="wrap">
  <h2>Lightweight Likes Counter Options</h2>
  <h3>Sync Settings</h3>
  <p>Please fill in the number of hours and minutes you want to wait between each update.</p>
  <form method="post" action="options.php">
  <?php settings_fields('LWLC_Options'); ?>
  <table class="form-table">
  <tr valign="top">
    <th scope="row"><label>New sync after:</label></th>
    <td>
    <?php
      $items = array(
        "900" => "15 minutes", 
        "1800" => "30 minutes", 
        "3600" => "60 minutes", 
        "21600" => "6 hours", 
        "43200" => "12 hours", 
        "86400" => "24 hours");
      echo "<select id='LWLC_interval' name='LWLC_interval'>";
      foreach($items as $item => $value) {
        $selected = ($LWLC_interval==$item) ? 'selected="selected"' : '';
        echo "<option value='$item' $selected>$value</option>";
      }
      echo "</select>";
		?>
    </td>
  </tr>
  </table>
  
  <hr />
  
  <h3>Enable Share buttons</h3>
  <p><em>Optional: </em>Give people the ability to share your links.</p>
  <table class="form-table">
  <tr valign="top">
  <tr valign="top">
    <th scope="row"><label>Enable like buttons:</label></th>
    <td>
      <?php echo "<input "; if($LWLC_enableshare){ echo " checked='checked' "; } echo " name='LWLC_enableshare' type='checkbox' />"; ?>
   	</td>
  </table>
  
  <hr />
  
  <h3>Style Settings</h3>
  <p><em>Optional: </em>You can add some default styling to the buttons. Disable this to do your own styling.</p>
  <table class="form-table">
  <tr valign="top">
    <th scope="row"><label>Default styling</label></th>
    <td>
      <?php echo "<input "; if($LWLC_styling){ echo " checked='checked' "; } echo " name='LWLC_styling' type='checkbox' />"; ?>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2" scope="row"><em>Enable if you want to show the results on the page.</em></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label>Display seperate counts</label></th>
    <td>
      <?php echo "<input "; if($LWLC_results['seperate']){ echo " checked='checked' "; } echo " name='LWLC_results[seperate]' type='checkbox' />"; ?>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2" scope="row"><em>Enable if you want to total counts displayed.</em></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label>Display combined counts</label></th>
    <td>
      <?php echo "<input "; if($LWLC_results['combined']){ echo " checked='checked' "; } echo " name='LWLC_results[combined]' type='checkbox' />"; ?>
   	</td>
  </tr>
  <tr valign="top">
    <td colspan="2" scope="row"><strong>NOTE: Seperate and combined can be displayed at the same time.</strong></td>
  </tr>
  </table>
  
  <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
  </form>
  
  <hr />
  
  <h2>Sync all the data</h2>
  <p><em>Optional: </em>You can delete all the data from the database, or sync all the posts with all the network sites.</p>
  <table class="form-table">
  <tr valign="top">
  	<td><strong>Last full Sync: </strong><em><?php echo $LWLC_lastsync ? date('j F, Y', $LWLC_lastsync) : 'Never Synced'; ?></em></td> 
  </tr>
  <tr valign="top">
  	<td><p class="submit"><input id="LWLC_SyncData" type="button" class="button-primary LWLC_AjaxAction" value="<?php _e('Sync All') ?>" /></p></td>
  </tr>
  </table>
  
  <hr />
  
  <h2 style="color:red;">Restore to default</h2>
  <table class="form-table">
  <tr valign="top">
  	<td>
    	<p><strong style="color:red;">Will delete all data !!</strong><br />Reset all the settings to the default state (init)</p>
        <div class="LWLC_WarningLink">
      	  <a href="#">Show me the button</a>
      	  <p class="submit"><input style="display:none;" id="LWLC_RestoreData" type="button" class="button-primary LWLC_AjaxAction" value="<?php _e('Restore data'); ?>" /></p>
        </div>
    </td>
  </tr>
  </table>
</div>

<?php }