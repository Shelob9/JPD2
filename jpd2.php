<?php
/*
Plugin Name: JPD2
Plugin URI:
Description: Makes caching WordPress queries via the Transients API easy.
Version: 0.0.1
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
 * @param string $type What type of query to run WP_Query (default), WP_User_Query, or WP_Meta_Query. Optional.
 * @param string $name Name to assign to the transient. Can be used to get the transient directly via get_transient(). Required.
 * @param string|int $expire Set the maxiumum life of the transient. Optional.
 *
 * @return mixed|null|WP_Meta_Query|WP_Query|WP_User_Query
 *
 * @since 0.0.1
 */
if ( !function_exists( 'jpd2_better_query') ) :
    function jpd2_better_query( $args, $type= 'wp_query', $name, $expire= null ) {
        require_once( 'class-jpd2.php' );
        $jpd2 = new jpd2_better_query();
        $query = $jpd2->cake_or_death( $args, $type= 'wp_query', $name, $expire= null );
        return $query;
    }
endif;

/**
 * Resets the transients when a post is updated.
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