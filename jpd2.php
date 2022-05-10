<?php
/*
Plugin Name: JPD2
Plugin URI: http://joshpress.net/blog/jpd2/
Description: Makes caching WordPress queries via the Transients API easy.
Version: 0.8.7
Author: Josh Pollock
Author URI: http://www.JoshPress.net
License: GPLv2+
License URI:   http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Define JPD2_EXP, the default expiration time for transients if not already defined
 */
if ( !defined( 'JPD2_EXP') ) {
    define( 'JPD2_EXP', DAY_IN_SECONDS );
}

/**
 * Define JPD2_TRANS_PREFIX, the default prefix for transients save by this plugin
 */
if ( !defined( 'JPD2_TRANS_PREFIX' ) ) {
    define( 'JPD2_TRANS_PREFIX', 'JPD2' );
}


/**
 * Returns the cached query, or runs it and caches it.
 *
 * @param array $args Arguments for the query. Required.
 * @param string $type What type of query to run WP_Query (default), WP_User_Query, WP_Meta_Query, or Pods. Optional.
 * @param string $name Name to assign to the transient. Can be used to get the transient directly via get_transient(). Required.
 * @param string|int $expire Set the maximum life of the transient. Optional.
 * @param string $pod The name of a pod to query if using the Pods class. Optional.
 *
 * @return mixed|null|WP_Meta_Query|WP_Query|WP_User_Query
 *
 * @since 0.0.1
 */
if ( !function_exists( 'jpd2_better_query') ) :
    function jpd2_better_query( $args, $type= 'wp_query', $name, $expire= null, $pod=null ) {
        require_once( 'class-jpd2.php' );
        $jpd2 = new jpd2_better_query();
        $query = $jpd2->cache_or_query( $args, $type, $name, $expire, $pod );
        return $query;
    }
endif;

/**
 * Resets the transients when a post is updated.
 *
 * @since 0.0.1
 */
if ( !function_exists( 'jpd2_transient_reset' ) ) {
    add_action( 'save_post', 'jpd2_transient_reset'  );
    function jpd2_transient_reset() {
        require_once( 'class-jpd2.php' );
        $jpd2 = new jpd2_better_query();
        $reset = $jpd2->reset();
        return $reset;
    }
}

/**
 * Activation hook.
 *
 * Adds option 'jpd2_cached_names'
 *
 * @since 0.0.1
 */
register_activation_hook( __FILE__, 'jpd2_activate' );
function jpd2_activate() {
    $option_name = 'jpd2_cached_names';
    $new_value = array();
    if ( get_option( $option_name ) !== false ) {
        update_option( $option_name, $new_value );
    } else {
        $deprecated = null;
        $autoload = 'no';
        add_option( $option_name, $new_value, $deprecated, $autoload );
    }
}

/**
 * Deactivation hook
 *
 * Removes 'jpd2_cached_names' option
 *
 * @TODO Consider clearing all transient this plugin created, instead of letting them die a natural death
 *
 * @since 0.0.1
 */
register_deactivation_hook( __FILE__, 'jpd2_deactivate' );
function jpd2_deactivate() {
    delete_option( 'jpd2_cached_names' );
}
