<?php

/**
 * Archive Page Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

/**
 * ESF_Archive_Page_Handler.
 */
class ESF_Archive_Page_Handler {

    /**
     * Prepare data
     */
    public static function prepare_data() {
        $args  = array() ;
        $month = isset( $_REQUEST[ 'month' ] ) ? wp_unslash( $_REQUEST[ 'month' ] ) : date( 'm' ) ;
        $year  = isset( $_REQUEST[ 'year' ] ) ? wp_unslash( $_REQUEST[ 'year' ] ) : date( 'Y' ) ;
        $day   = date( 'Y-m-d' ) ;

        //get first day of month
        $start_date                 = strtotime( "$year-$month-01" ) ;
        $first_day_of_current_month = date( 'N' , $start_date ) ;

        //diff between first day and first day of week
        $start_of_week = absint( get_option( 'start_of_week' , 1 ) ) ;
        $diff          = $start_of_week - $first_day_of_current_month ;

        //get start time stamp and end timestamp
        $start_timestamp = strtotime( $diff . ' days midnight' , $start_date ) ;
        $end_timestamp   = ($diff == '-6') ? strtotime( '+42 days midnight -1 mins' , $start_timestamp ) : strtotime( '+35 days midnight -1 mins' , $start_timestamp ) ;

        $events = self::get_events_data( $start_timestamp , $end_timestamp ) ;

        $args = array(
            'events'          => $events ,
            'month'           => $month ,
            'year'            => $year ,
            'day'             => $day ,
            'start_timestamp' => $start_timestamp ,
            'end_timestamp'   => $end_timestamp ,
                ) ;

        return $args ;
    }

    /*
     * Get Events data
     */

    public static function get_events_data( $start_timestamp , $end_timestamp ) {

        global $wpdb ;

        $post_ids        = self::maybe_taxonomy_post_ids() ;
        $start_timestamp = date( 'Y-m-d H:i' , $start_timestamp ) ;
        $end_timestamp   = date( 'Y-m-d H:i' , $end_timestamp ) ;

        $post_query = new ESF_Query( $wpdb->prefix . 'posts' , 'p' ) ;
        $post_query->select( 'DISTINCT `p`.ID,`pm`.meta_value as start_date, `pm1`.meta_value as end_date' )
                ->leftJoin( $wpdb->prefix . 'postmeta' , 'pm' , '`p`.`ID` = `pm`.`post_id`' )
                ->leftJoin( $wpdb->prefix . 'postmeta' , 'pm1' , '`p`.`ID` = `pm1`.`post_id`' )
                ->where( '`p`.post_type' , ESF_Register_Post_Types::EVENT_POSTTYPE )
                ->where( '`p`.post_status' , 'publish' )
                ->where( '`pm`.meta_key' , 'esf_event_start_date' )
                ->where( '`pm1`.meta_key' , 'esf_event_end_date' )
                ->whereGt( '`pm`.meta_value' , $start_timestamp )
                ->whereLt( '`pm1`.meta_value' , $end_timestamp )
                ->orderBy( '`p`.ID' ) ;

        if ( $post_ids !== false )
            $post_query->whereIn( '`p`.ID' , $post_ids ) ;

        $event_ids = $post_query->fetchArray() ;

        if ( ! esf_check_is_array( $event_ids ) )
            return array() ;

        return self::format_event_ids( $event_ids ) ;
    }

    /**
     * Get post IDs if any for category or tag
     */
    public static function maybe_taxonomy_post_ids() {
        if ( ! is_tax( ESF_Register_Post_Types::CATEGORY_TAXONOMY ) && ! is_tax( ESF_Register_Post_Types::TAG_TAXONOMY ) )
            return false ;

        $category = get_queried_object() ; // current page terms
        //current page taxonomy
        $taxonomy = (is_tax( ESF_Register_Post_Types::CATEGORY_TAXONOMY )) ? ESF_Register_Post_Types::CATEGORY_TAXONOMY : ESF_Register_Post_Types::TAG_TAXONOMY ;

        $args = array(
            'post_type'   => ESF_Register_Post_Types::EVENT_POSTTYPE ,
            'post_status' => 'publish' ,
            'numberposts' => -1 ,
            'tax_query'   => array(
                array(
                    'taxonomy'         => $taxonomy ,
                    'include_children' => false ,
                    'terms'            => $category->term_id ,
                )
            ) ,
            'fields'      => 'ids'
                ) ;

        $post_ids = get_posts( $args ) ;

        return $post_ids ;
    }

    /**
     * Format Event IDs
     */
    public static function format_event_ids( $event_ids ) {
        $events = array() ;

        foreach ( $event_ids as $event_id ) {

            $start_date = current( explode( " " , $event_id[ 'start_date' ] ) ) ;
            $end_date   = current( explode( " " , $event_id[ 'end_date' ] ) ) ;

            while ( $start_date <= $end_date ) {

                if ( isset( $events[ $start_date ] ) )
                    array_push( $events[ $start_date ] , $event_id[ 'ID' ] ) ;
                else
                    $events[ $start_date ] = array( $event_id[ 'ID' ] ) ;

                $start_date = date( 'Y-m-d' , strtotime( "+1 day" , strtotime( $start_date ) ) ) ;
            }
        }

        return $events ;
    }

}
