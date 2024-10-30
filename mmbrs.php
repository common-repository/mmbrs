<?php
/*
Plugin Name: mmbrs
Plugin URI: http://compassionate.co/mmbrs
Description: Show/Hide content based on roles, capabilities, user meta, and logged in status
Author: Ben Wills
Author URI: http://compassionate.co/
Text Domain: mmbrs
Version: 1.0
License: GPL2
Tags: access, capability, capabilities, content access, member, members, membership, memberships, restrict access, role, roles, shortcode, shortcodes, user, users, user meta, usermeta
Date: 2014/10/01
 */

/*
 * Copyright 2014 Ben Wills (ben@compassionate.co)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/* ================================================== */

/**
 * Shortcode to show/hide content based on user roles.
 *
 * Ex: [mmbrs_logged_in]content[/mmbrs_logged_in]
 *     Will only show the content to logged in users
 *
 * Ex: [mmbrs_logged_in is='not']content[/mmbrs_logged_in]
 *     Will only show the content to logged out users
 *          
 * @param  [array] $atts
 * @param  [string] $content
 * @return [string]
 */
add_shortcode( 'mmbrs_logged_in', 'shortcode_mmbrs_logged_in' );
function shortcode_mmbrs_logged_in( $atts, $content = NULL ) {

	// No content, do nothing
	if ( NULL == $content ) {
		return '';
	}

	// -------------------------

	// Extract and prefix the shortcode's attributes with 'attr_'
	extract ( shortcode_atts( array(
		'is' => NULL,
		'showinfeed' => FALSE,
	), $atts ), EXTR_PREFIX_ALL, 'attr' );

	// Give nothing to the feed, unless user sets showinfeed='yes'
	if ( is_feed() ) {
		if ( 'yes' == strtolower ( $attr_showinfeed ) ) {
			return do_shortcode( $content );
		}
		else {
			return '';
		}
	}

	// -------------------------

	if ( isset ( $attr_is ) ) {
		$attr_is = strtolower ( $attr_is );
	}

	if ( is_user_logged_in() ) {
		if ( 'not' != $attr_is ) {
			return do_shortcode( $content );
		}
	}

	if ( ! is_user_logged_in() ) {
		if ( 'not' == $attr_is ) {
			return do_shortcode( $content );
		}
	}

	return ''; // Catch the rest
}


/**
 * Shortcode to show/hide content based on user roles.
 *
 * Ex: [mmbrs_roles equals='administrator,subscriber']content[/mmbrs_roles]
 *     Will only show the content to logged in users who have the roles
 *     of administrator or subscriber
 *          
 * @param  [array] $atts
 * @param  [string] $content
 * @return [string]
 */
add_shortcode( 'mmbrs_roles', 'shortcode_mmbrs_roles' );
function shortcode_mmbrs_roles( $atts, $content = NULL ) {

	// If user is not logged in, do nothing
	if ( ! is_user_logged_in() ) {
		return '';
	}

	// No content, do nothing
	if ( NULL == $content ) {
		return '';
	}

	// -------------------------

	// Extract and prefix the shortcode's attributes with 'attr_'
	extract ( shortcode_atts( array(
		'is' 			=> NULL,
		'equals'		=> NULL,
		'showinfeed' 	=> FALSE,
	), $atts ), EXTR_PREFIX_ALL, 'attr' );

	// If no roles are defined, do nothing
	if ( ! isset ( $attr_equals ) ) {
		return '';
	}
	if ( NULL == $attr_equals ) {
		return '';
	}

	// Give nothing to the feed, unless user sets showinfeed='yes'
	if ( is_feed() ) {
		if ( 'yes' == strtolower ( $attr_showinfeed ) ) {
			return do_shortcode( $content );
		}
		else {
			return '';
		}
	}

	if ( isset ( $attr_is ) ) {
		$attr_is = strtolower ( $attr_is );
	}

	if ( isset ( $attr_equals ) ) {
		$attr_equals = explode   ( ',',    $attr_equals );
		$attr_equals = array_map ( 'trim', $attr_equals );
	}

	$user       = wp_get_current_user();
	$user_roles = $user->roles;

	// -------------------------

	if ( ! empty ( $user_roles ) OR ( is_array ( $user_roles ) ) ) {

		if ( array_intersect ( $attr_equals, $user_roles ) ) {
			if ( 'not' != $attr_is ) {
				return do_shortcode( $content );
			}
		}

		if ( ! array_intersect ( $attr_equals, $user_roles ) ) {
			if ( 'not' == $attr_is ) {
				return do_shortcode( $content );
			}
		}
	}
	return ''; // Catch the rest
}


/**
 * Shortcode to show/hide content based on capabilities.
 *
 * Ex: [mmbrs_capabilities is='NOT' can='delete_others_posts']content[/mmbrs_capabilities]
 *      Will only show content to logged in users who do not have
 *      the capabilities to 'delete_others_posts'
 * 
 * @param  [array] $atts
 * @param  [string] $content
 * @return [string]
 * @since  1.0
 */
add_shortcode('mmbrs_capabilities', 'shortcode_mmbrs_capabilities');
function shortcode_mmbrs_capabilities ($atts, $content = NULL) {
	
	// If the user is not logged in, do nothing
	if ( ! is_user_logged_in() ) {
		return '';
	}

	// If there is no shortcode content, do nothing
	if ( NULL == $content ) {
		return '';
	}

	// -------------------------

	// Extract and prefix the shortcode's attributes with 'attr_'
	extract ( shortcode_atts( array(
		'is' 			=> NULL,
		'can'			=> NULL,
		'showinfeed' 	=> FALSE,
	), $atts ), EXTR_PREFIX_ALL, 'attr' );

	// Give nothing to the feed, unless user sets showinfeed='yes'
	if ( is_feed() ) {
		if ( 'yes' == strtolower ( $attr_showinfeed ) ) {
			return do_shortcode( $content );
		}
		else {
			return '';
		}
	}

	// If no capabilities are defined, do nothing
	if ( ! isset ( $attr_can ) ) {
		return '';
	}
	if ( NULL == $attr_can ) {
		return '';
	}

	if ( isset ( $attr_is ) ) {
		$attr_is = strtolower ( $attr_is );
	}
			
	if ( isset ( $attr_can ) ) {
		$attr_can = explode   ( ',',    $attr_can );
		$attr_can = array_map ( 'trim', $attr_can );
	}

	$user          = wp_get_current_user();
	$user_caps_raw = $user->allcaps;
	$user_caps     = array();
	foreach ( $user_caps_raw as $key => $val ) {
		$user_caps[] = $key;
	}
	$user_caps = array_unique ( $user_caps );

	// -------------------------

	if ( ! empty ( $user_caps ) ) {

		if ( array_intersect ( $attr_can, $user_caps ) ) {
			if ( 'not' != $attr_is ) {
				return do_shortcode( $content );
			}
		}

		if ( ! array_intersect ( $attr_can, $user_caps ) ) {
			if ( 'not' == $attr_is ) {
				return do_shortcode( $content );
			}
		}
	}
	return ''; // Catch the rest
}


/**
 * Shortcode to show/hide content based on user meta.
 *
 * Ex: [mmbrs_user_meta key='first_name'][/mmbrs_user_meta]
 *     Will only show the 'first_name' meta key to logged in users
 *     who have the 'first_name' meta
 *
 * Ex: [mmbrs_user_meta is='not' key='first_name' equals='Ben']content[/mmbrs_user_meta]
 *     Will only show content to logged in users who do NOT have
 *     a 'first_name' meta value of 'Ben'.
 *          
 * @param  [array] $atts
 * @param  [string] $content
 * @return [string]
 */
add_shortcode( 'mmbrs_user_meta', 'shortcode_mmbrs_user_meta' );
function shortcode_mmbrs_user_meta( $atts, $content = NULL ) {

	// If user is not logged in, do nothing
	if ( ! is_user_logged_in() ) {
		return '';
	}

	// -------------------------

	// Extract and prefix the shortcode's attributes with 'attr_'
	extract ( shortcode_atts( array(
		'key'			=> NULL,
		'is' 			=> NULL,
		'equals' 		=> NULL,
		'showinfeed' 	=> FALSE,
	), $atts ), EXTR_PREFIX_ALL, 'attr' );

	// Give nothing to the feed, unless user sets showinfeed='yes'
	if ( is_feed() ) {
		if ( 'yes' == strtolower ( $attr_showinfeed ) ) {
			return do_shortcode( $content );
		}
		else {
			return '';
		}
	}

	// If no user meta key is defined, do nothing
	if ( ! isset ( $attr_key ) ) {
		return '';
	}
	if ( NULL == $attr_key ) {
		return '';
	}

	if ( isset ( $attr_is ) ) {
		$attr_is = strtolower ( $attr_is );
	}

	$user = wp_get_current_user();
		
	// -------------------------

	if ( NULL == $content ) {
		if ( $user->get( $attr_key ) ) {
			return $user->get( $attr_key );
		}
	}
	else { // $content is not NULL

		if ( ! isset ( $attr_equals ) ) {
			if ( $user->get( $attr_key ) ) {
				return do_shortcode( $content );
			}
		}

		if ( isset ( $attr_equals ) ) {
			if ( $attr_equals == $user->get( $attr_key ) ) {
				if ( 'not' != $attr_is ) {
					return do_shortcode( $content );
				}
			}
		}

		if ( isset ( $attr_equals ) ) {
			if ( $attr_equals != $user->get( $attr_key ) ) {
				if ( 'not' == $attr_is ) {
					return do_shortcode( $content );
				}
			}
		}
	}
	return ''; // Catch the rest
}

