<?php
if (!defined('ABSPATH'))
{
	exit;
}

function restc_restriction_shortcode($atts, $inputcontent = null)
{
	global $user_ID , $post;

	$link = '';
	
	if ((isset($atts)) && (is_array($atts)) && (count($atts) > 0) && ((isset($atts['link'])) ))
	{
		// before 1.0.7 $link = $atts['link'];
		//1.0.9
		$link = sanitize_url($atts['link']);
		
	}
		
	if ( is_user_logged_in() == false )
	{
		if (empty($link))
		{		
			$saved_register_page_url = get_option('restrictionmoregisterpageurl');
			if (empty($saved_register_page_url))
			{
				$current_url = $_SERVER['REQUEST_URI'];
				$redirect_url = wp_login_url( );
			}
			else
			{
				$saved_register_page_url = $saved_register_page_url;
				$redirect_url = $saved_register_page_url;
			}
		}
		else 
		{
			$redirect_url = $link;
		}
		$restrcition_not_logged_in_message  = "Sorry, this is restricted content, please <a href='$redirect_url'> log in first </a> " ;
		return $restrcition_not_logged_in_message;
	}
	else 
	{
		if ((isset($atts)) && (is_array($atts)) && (count($atts) > 0) && ((isset($atts['roles'])) ))
		{
			
			if (isset($atts['roles']))
			{
				
				$only_roles = sanitize_text_field($atts['roles']);
				$only_roles_array = explode(",", trim($only_roles));
					
					
				$current_reader = wp_get_current_user();
					
		
				if ((!(empty($only_roles_array))) && (is_array($only_roles_array)) && (count($only_roles_array)) )
				{
					

					foreach ( $only_roles_array as $tomas_roles_single_key => $tomas_roles_single_name_low)
					{
						if (in_array($tomas_roles_single_name_low, $current_reader->roles))
						{
							return $inputcontent;
						}
						else
						{
							continue;
						}
					}
					if (empty($link))
					{					
						$saved_register_page_url = get_option('restrictionmoregisterpageurl');
						if (empty($saved_register_page_url))
						{
							$current_url = $_SERVER['REQUEST_URI'];
							$redirect_url = wp_login_url( );
						}
						else
						{
							$saved_register_page_url = $saved_register_page_url;
							$redirect_url = $saved_register_page_url;
						}
					}
					else 
					{
						$redirect_url = $link;
					}
					$restrcition_not_logged_in_message  = "Sorry, this is restricted content, please <a href='$redirect_url'> log in first </a> " ;
					return $restrcition_not_logged_in_message;					
					
				}
					
			}
		}
		else 
		{
			return $inputcontent;
		}
		
		
	}
}


add_shortcode( 'restriction', 'restc_restriction_shortcode' );

