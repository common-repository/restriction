<?php
/*
Plugin Name: Restriction
Plugin URI:  https://wordpress.org/restriction
Description: only logged in user can view your private content  
Version: 1.0.9
Author: tooltipsorg
Author URI: https://wordpress.org/restriction/
Text Domain: restriction
License: GPLv3 or later
*/
/* 
 This program comes with ABSOLUTELY NO WARRANTY;
https://www.gnu.org/licenses/gpl-3.0.html
https://www.gnu.org/licenses/quick-guide-gplv3.html
*/
if (!defined('ABSPATH'))
{
	exit;
}

define('RESTRICTION_ADMIN_PATH', plugin_dir_path(__FILE__).'admin'.'/');
define('RESTRICTION_PLUGIN_URL', plugin_dir_url( __FILE__ ));

$restrictioncurrentversion = get_option ( 'restrictioncurrentversion' );
$restrictioncurrentversion = str_replace ( '.', '', $restrictioncurrentversion );
if (empty ( $restrictioncurrentversion ))
{

}
else
{
	define('RESTRI_VERSION', '1.0.7');
	update_option ( 'restrictioncurrentversion', RESTRI_VERSION);
}

ob_start();
require_once("rules/shortcoderestriction.php");
add_action('admin_menu', 'restc_restriction_option_menu');

function restc_restriction_option_menu()
{

	add_menu_page(__('Restriction', 'wp-restriction'), __('Restriction', 'wp-restriction'), 'manage_options', 'wprestriction', 'restc_wprestrictionsettings');
	add_submenu_page('wprestriction', __('Restriction','wp-restriction'), __('Restriction','wp-restriction'), 'manage_options', 'wprestriction', 'restc_wprestrictionsettings');
}

$restrictiondisableallfeature = get_option('restrictiondisableallfeature');
if ('yes' == $restrictiondisableallfeature)
{
	return;
}


function restc_wprestrictionsettings()
{
	global $wpdb;

	$m_restrictionmoregisterpageurl = get_option('restrictionmoregisterpageurl');

	if (isset($_POST['resctrictionsubmitnew']))
	{
		check_admin_referer( 'restc_restriction_global_settings_nonce' );
		if (isset($_POST['restrictionmoregisterpageurl']))
		{
			$m_restrictionmoregisterpageurl = sanitize_textarea_field($_POST['restrictionmoregisterpageurl']);
		}

		update_option('restrictionmoregisterpageurl',$m_restrictionmoregisterpageurl);
		if (isset($_POST['restrictionopenedpageurl']))
		{
			$restrictionopenedpageurl = trim($_POST['restrictionopenedpageurl']);
			if (strlen($restrictionopenedpageurl) == 0)
			{
				delete_option('saved_open_page_url');
			}
			else
			{
				$restrictionopenedpageurl = sanitize_textarea_field($restrictionopenedpageurl);
				update_option('saved_open_page_url',$restrictionopenedpageurl);
			}
		}

		if (isset($_POST['restrictiondisableallfeature']))
		{
			$restrictiondisableallfeature = sanitize_text_field($_POST['restrictiondisableallfeature']);
			update_option('restrictiondisableallfeature',$restrictiondisableallfeature);
		}
		else
		{
			delete_option('restrictiondisableallfeature');
		}
			
		$restrictiondisableallfeature = get_option('restrictiondisableallfeature');
			
		if (isset ( $_POST ['restrictionenablepagelevelprotect'] ))
		{
			$m_restrictionenablepagelevelprotect = sanitize_textarea_field($_POST ['restrictionenablepagelevelprotect']);
			update_option ( 'restrictionenablepagelevelprotect', $m_restrictionenablepagelevelprotect );
		}
		else
		{
			delete_option ( 'restrictionenablepagelevelprotect' );
		}
		$restrictionenablepagelevelprotect = get_option ( 'restrictionenablepagelevelprotect' );
			
		$restrictionMessageString =  __( 'Your changes has been saved.', 'wp-restriction' );
		restc_restriction_members_only_message($restrictionMessageString);		
	}
	echo "<br />";

	$saved_register_page_url = get_option('restrictionmoregisterpageurl');
	?>

<div style='margin:10px 5px;'>
<div style='float:left;margin-right:10px;'>
<img src='<?php echo RESTRICTION_PLUGIN_URL;  ?>images/new.png' style='width:30px;height:30px;'>
</div> 
<div style='padding-top:5px; font-size:22px;'> <i></>Restriction Global Setting:</i></div>
</div>
<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body"  style="width:98%;">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:98%;">
								<div class="postbox">
									<div class="inside" style='padding-left:10px;'>
										<form id="restrictionform" name="restrictionform" action="" method="POST">
											<?php
											wp_nonce_field('restc_restriction_global_settings_nonce');
											?>										
										<table id="restrictiontable" width="100%">
										<tr>
										<td width="100%" style="padding: 0px 20px;">
										<p>
										<?php echo  __( '<h3>Register Page URL:</h3>', 'wp-restriction' ); ?>
										</p>
										<input type="text" id="restrictionmoregisterpageurl" name="restrictionmoregisterpageurl"  style="width:500px;" size="70" value="<?php  echo $saved_register_page_url; ?>">
										<p>
										<font color="Gray"><i><?php echo  __( '# When non-member users view your wordpress pages, they will be redirected to this URL, you can input the URL of your register page, or URL of login page in here.', 'wp-restriction' ); ?></i></font>
										</p>
										<p>
										<font color="Gray"><i><?php echo  __( '# Also you can input URL of other wordpress pages, for example, landing page or product page', 'wp-restriction' ); ?></i></font>
										</p>
										<p>
										<font color="Gray"><i><?php echo  __( '# By default, your wordpress site will restricts to non-members users, but you can open specific pages to users.', 'wp-restriction' ); ?></i></font>
										</p>
										</td>
										</tr>
										
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>
										<?php echo  __( '<h3>Opened Page URLs:</h3>', 'wp-restriction' ); ?>
										</p>
										<?php 
										$urlsarray = get_option('saved_open_page_url'); 
										?>
										<textarea name="restrictionopenedpageurl" id="restrictionopenedpageurl" cols="70" rows="10" style="width:500px;"><?php echo $urlsarray; ?></textarea>
										<p><font color="Gray"><i><?php echo  __( 'Enter one URL per line please.', 'wp-restriction' ); ?></i></p>
										<p><font color="Gray"><i><?php echo  __( 'These pages will opened for guest and guest will not be directed to register page.', 'wp-restriction' ); ?></i></p>					
										</td>
										</tr>

										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>
										<?php 
											echo  __( '<h3>Temporarily Turn Off All Featrures:</h3>', 'wp-restriction' );
										?>
										</p>
										<p>
										<?php
										$restrictiondisableallfeature = get_option('restrictiondisableallfeature');
										if (!(empty($restrictiondisableallfeature)))
										{
											echo '<input type="checkbox" id="restrictiondisableallfeature" name="restrictiondisableallfeature"  style="" value="yes"  checked="checked"> Temporarily Turn Off All Featrures of Restriction Plugin ';
 
										}
										else 
										{
											echo '<input type="checkbox" id="restrictiondisableallfeature" name="restrictiondisableallfeature"  style="" value="yes" > Temporarily Turn Off All Featrures of Restriction Plugin ';
										}
										?>
										</p>
										<p><font color="Gray"><i>
										<?php 
										echo  __( '# If you enabled this option, all features of restriction plugin will be disabled, you site will open to all users', 'wp-restriction') ;
										?></i></p>
										</td>
										</tr>
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>										
										<?php
										echo __ ( '<h3>Enable Page Level Protect:</h3>', 'wp-restriction' );
										?>
										</p>
										<p>
																			<?php
										$restrictionenablepagelevelprotect = get_option ( 'restrictionenablepagelevelprotect' );
										if (! (empty ( $restrictionenablepagelevelprotect ))) {
										} else {
											$restrictionenablepagelevelprotect = '';
										}
										?>
																			<?php
										if (! (empty ( $restrictionenablepagelevelprotect ))) {
											echo '<input type="checkbox" id="restrictionenablepagelevelprotect" name="restrictionenablepagelevelprotect"  style="" value="yes"  checked="checked"> Enable Page Level Protect Settings ';
										} else {
											echo '<input type="checkbox" id="restrictionenablepagelevelprotect" name="restrictionenablepagelevelprotect"  style="" value="yes" > Enable Page Level Protect Settings ';
										}
										?>
										<p>
													<font color="Gray"><i>
																			<?php
										echo __ ( '# If you enabled this option,  in ', 'wp-restriction' );
										echo "<a style='color:#4e8c9e;' href='" . get_option ( 'siteurl' ) . "/wp-admin/post-new.php' target='_blank'>page / post  editor</a>";
										echo __ ( ', you will find "Members only for this page?" meta box at the right top of the wordpress standard editor.', 'wp-restriction in ' );
										?>
										</i>
												
										</p>
										<p>
											<font color="Gray"><i><?php echo  __( '# If you checked "Allow everyone to access the page" checkbox in meta box, the post will be opened to all guest users', 'wp-restriction' ); ?></i>
										
										</p>
										<p>
										<font color="Gray"><i>
										<?php
										echo __ ( '# By this way, you do not need enter page URLs to ', 'wp-restriction' );
										echo "<a  style='color:#4e8c9e;' href='" . get_option ( 'siteurl' ) . "/wp-admin/admin.php?page=wprestriction' target='_blank'>Opened Pages Panel</a>";
										echo __ ( ' always.', 'wp-restriction' );
										?></i>
												</p>
											</td>
										</tr>
										</table>
										<br />
										<input type="submit" id="resctrictionsubmitnew" name="resctrictionsubmitnew" value=" Submit " style="margin:1px 20px;">
										</form>
										
										<br />
									</div>
								</div>
							</div>
						</div>
					</div>
		    	</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<br />
		<?php
}				

	
function restc_restriction_members_only_message($p_message)
{

	echo "<div id='message' class='updated fade' style='line-height: 30px;margin-left: 0px;'>";

	echo $p_message;

	echo "</div>";

}

function restc_restriction_for_members()
{
	global $user_ID , $post;
	if (is_front_page()) return;
	
	if (function_exists('bp_is_register_page') && function_exists('bp_is_activation_page') )
	{
		if ( bp_is_register_page() || bp_is_activation_page() )
		{
			return;
		}
	}
	
	$current_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$current_url = pure_url($current_url);
	
	$saved_register_page_url = get_option('restrictionmoregisterpageurl');
	$saved_register_page_url = pure_url($saved_register_page_url);
	
	$current_page_id = get_the_ID ();
	$restrictionenablepagelevelprotect = get_option ( 'restrictionenablepagelevelprotect' );
	if (! (empty ( $restrictionenablepagelevelprotect ))) 
	{
		$get_post_meta_value_for_this_page = get_post_meta ( $current_page_id, 'restc_restriction_access_to_this_page', true );
		if (strtolower ( $get_post_meta_value_for_this_page ) == 'yes') 
		{
			return;
		}
	}	
	
	if (stripos($saved_register_page_url, $current_url) === false)
	{

	}
	else 
	{
		return;
	}
	
	$saved_open_page_option = get_option('saved_open_page_url');
	$saved_open_page_url = explode("\n", trim($saved_open_page_option));

	if ((is_array($saved_open_page_url)) && (count($saved_open_page_url) > 0))
	{
		$root_domain = get_option('siteurl');
		foreach ($saved_open_page_url as $saved_open_page_url_single)
		{
			$saved_open_page_url_single = trim($saved_open_page_url_single); 

			if (reserved_url($saved_open_page_url_single) == true)
			{
				continue;
			}
			$saved_open_page_url_single = pure_url($saved_open_page_url_single);

			if (empty($saved_open_page_url_single))
			{
				
			}
			else
			{
				if (stripos($current_url,$saved_open_page_url_single) === false)
				{
				
				}
				else
				{
				
					return;
				}				
			}

		}
	}

	if ( is_user_logged_in() == false )
	{
		if (empty($saved_register_page_url))
		{
			$current_url = $_SERVER['REQUEST_URI'];
			$redirect_url = wp_login_url( );
			header( 'Location: ' . $redirect_url );
			die();			
		}
		else 
		{
			$saved_register_page_url = 'http://'.$saved_register_page_url;
			header( 'Location: ' . $saved_register_page_url );
			die();
		}
	}
}

function pure_url($current_url)
{
	if (empty($current_url)) return false;

	$current_url_array = parse_url($current_url);

	$current_url = str_ireplace('http://','',$current_url);
	$current_url = str_ireplace('https://','',$current_url);
	$current_url = str_ireplace('ws://','',$current_url);
		
	$current_url = str_ireplace('www.','',$current_url);
	$current_url = trim($current_url);
	return $current_url;
}

function reserved_url($url)
{
	$home_page = get_option('siteurl');
	$home_page = pure_url($home_page);
	$url = pure_url($url);
	if ($home_page == $url)
	{
		return true;
	}
	else
	{
		return false;
	}
} 
 

add_action('wp_head','restc_restriction_for_members');


$restrictionenablepagelevelprotect = get_option ( 'restrictionenablepagelevelprotect' );
if (! (empty ( $restrictionenablepagelevelprotect ))) 
{
	add_action ( 'add_meta_boxes', 'add_restc_restriction_control_meta_box' );
	add_action ( 'save_post', 'save_wp_members_only_control_meta_box', 10, 3 );
}

function restc_restriction_control_meta_box() {
	$current_page_id = get_the_ID ();
	$get_post_meta_value_for_this_page = get_post_meta ( $current_page_id, 'restc_restriction_access_to_this_page', true );
	global $wpdb;

	?>
<table cellspacing="2" cellpadding="5" style="width: 100%;"
	class="form-table">
	<tbody>
		<tr class="form-field">
			<td><input name="restc_restriction_access_to_this_page" type="checkbox"
				value="yes"
				<?php  if(esc_attr( $get_post_meta_value_for_this_page ) == 'yes' ) {echo 'checked="checked"';} ?>><label><?php _e('Allow everyone to access the page', 'admin-tools') ?></label>
			</td>
		</tr>
	</tbody>
</table>
<?php
}

function add_restc_restriction_control_meta_box() {
	add_meta_box ( "restc_restriction_control_meta_box_id", __ ( 'Members only for this page?', 'wp-restriction' ), 'restc_restriction_control_meta_box', null, "side", "high", null );
}

function save_wp_members_only_control_meta_box($post_id, $post, $update) {
	$current_page_id = get_the_ID ();
	$meta_box_checkbox_value = '';
	
	if (isset ( $_POST ['restc_restriction_access_to_this_page'] )) 
	{
		$meta_box_checkbox_value = sanitize_textarea_field($_POST ['restc_restriction_access_to_this_page']);
		update_post_meta ( $current_page_id, 'restc_restriction_access_to_this_page', $meta_box_checkbox_value );
	} 
	else 
	{
		update_post_meta ( $current_page_id, 'restc_restriction_access_to_this_page', '' );
	}
}




