<?php
/**
 * Manages transient caching for WordPress queries.
 *
 * @package   @JPD2
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2014 Josh Pollock
 */

if ( !class_exists( 'jpd2_better_query' ) ) :
class jpd2_better_query {

    function __construct() {

    }

    /**
     * Returns the cached query, or runs it and caches it.
     *
     * @param array $args Arguments for the query. Required.
     * @param string $type What type of query to run WP_Query (default), WP_User_Query, or WP_Meta_Query. Optional.
     * @param string $name Name to assign to the transient. Can be used to get the transient directly via get_transient(). Required.
     * @param string|int $expire Set the maximum life of the transient. Optional.
	 * @param string $pod The name of a pod to query if using the Pods class. Optional.
     *
     * @return mixed|null|WP_Meta_Query|WP_Query|WP_User_Query
     *
     * @since 0.0.1
     */
	function cache_or_query( $args, $type='wp_query', $name, $expire= null, $pod=null ) {
		//if transient {$name} exists return it and move on with life
		if ( false === ( $query = get_transient( $name ) ) ) {
			//if not do query, cache results, live long and prosper.
			//if we have args, build the query
			if ( isset( $args ) ) {
				//Do the right query
				if ( $type === 'wp_query' || $type === 'query' ) {
					$query = $this->do_wp_query( $args );
				}
				elseif ( $type === 'wp_user_query' || $type === 'user_query' ) {
					$query = $this->do_user_query( $args );
				}
				elseif ( $type === 'wp_meta_query' || $type === 'meta_query' ) {
					$query = $this->do_meta_query( $args );
				}
				elseif( $type ='pods' && !is_null( $pod ) ) {
					$query= $this->do_pods( $args, $pod );
				}
				else {
					$query = NULL;
				}
				//use default expire time if not set
				if ( is_null( $expire ) ) {
					$expire = $this->expire();
				}
				//cache query for next time
				if ( !is_null( $query ) ) {
					set_transient( $name, $query, $expire );
				}
				//add transient's name to list of transients to flush on new post
				$this->names_list( $name );
				//no args->no query
			}
			else {
				$query = NULL;
			}
		}
		return $query;
	}

    //@TODO Why separate methods foreach?

    /**
     * @param $args
     *
     * @return WP_Query
     *
     * @since 0.0.1
     */
    function do_wp_query( $args ) {
        $query = new WP_Query( $args );
        return $query;
    }

    /**
     * @param $args
     *
     * @return WP_User_Query
     *
     * @since 0.0.1
     */
    function do_user_query( $args ) {
        $query = new WP_User_Query( $args );
        return $query;
    }

    /**
     * @param $args
     *
     * @return WP_Meta_Query
     *
     * @since 0.0.1
     */
    function do_meta_query( $args ) {
        $query = new WP_Meta_Query( $args );
        return $query;
    }

	function do_pods( $args, $pod ) {
		$query  = pods( $pod, $args, $strict= TRUE );
		return $query;
	}

    /**
     * Deletes all transients set by this plugin when a post is updated.
     *
     * @since 0.0.1
     */
    function reset() {
            global $post;
            $post_types = get_post_types();
            //@TODO Drop some of them
            $post_types = apply_filters( 'JPD2_reset_post_types', $post_types );

            if( in_array( $post->post_type, $post_types )  ) {
                //get the transients to delete
                $transients = get_option( 'jpd2_cached_names' );
                if ( is_array( $transients) ) {
                    //delete them
                    foreach ( $transients as $transient ) {
                        delete_transient( $transient );
                    }
                    //reset the list of transients to be cleared next time
                    $names = array();
                    update_option( 'jpd2_cached_names', $names );
                }
            }

    }

    /**
     * Returns the default expire time
     *
     * @return int|string
     *
     * @since 0.0.1
     */
    function expire() {
        $expire = JPD2_EXP;
        /**
         * Highest priority override for the default expiration time
         *
         * @since 0.0.1
         */
        $expire = do_action( 'JPD2_expire' );
        return $expire;
    }

    /**
     * Used to get the names of all transients created by this plugin.
     *
     * @param array $name
     *
     * @since 0.0.1
     */
    function names_list( $name ) {
        $list = get_option( 'jpd2_cached_names' );
        if ( is_array($list)) {
            //if this name isn't already there, add it
            if ( !in_array( $name, $list ) ) {
                $list[] = $name;
                update_option( 'jpd2_cached_names', $list );
            }
        }
    }
}
endif;