<?php

/*
 * Common functions
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

include_once('esf-layout-functions.php') ;
include_once('esf-post-functions.php') ;
include_once('esf-template-functions.php') ;
include_once('esf-formatting-functions.php') ;
include_once('esf-default-common-functions.php') ;

if ( ! function_exists( 'esf_check_is_array' ) ) {

    function esf_check_is_array( $array ) {
        if ( is_array( $array ) && ! empty( $array ) ) {
            return true ;
        } else {
            return false ;
        }
    }

}

if ( ! function_exists( 'esf_page_screen_ids' ) ) {

    function esf_page_screen_ids() {
        return apply_filters( 'esf_page_screen_ids' , array(
            ESF_Register_Post_Types::EVENT_POSTTYPE ,
            ESF_Register_Post_Types::CATEGORY_TAXONOMY ,
            ESF_Register_Post_Types::TAG_TAXONOMY ,
            'esf-events_page_locations' ,
            'esf-events_page_organizers' ,
            'esf-events_page_settings'
                ) ) ;
    }

}

if ( ! function_exists( 'esf_price' ) ) {

    function esf_price( $price , $args = array() ) {
        $format = '%1$s%2$s' ;

        switch ( get_option( 'esf_currency_position' , 'left' ) ) {
            case 'left':
                $format = '%1$s%2$s' ;
                break ;
            case 'right':
                $format = '%2$s%1$s' ;
                break ;
            case 'left_space':
                $format = '%1$s&nbsp;%2$s' ;
                break ;
            case 'right_space':
                $format = '%2$s&nbsp;%1$s' ;
                break ;
        }

        $args = apply_filters( 'esf_price_args' , wp_parse_args( $args , array(
            'currency'           => '' ,
            'decimal_separator'  => stripslashes( get_option( 'esf_currency_decimal_separator' , '.' ) ) ,
            'thousand_separator' => stripslashes( get_option( 'esf_currency_thousand_separator' , ',' ) ) ,
            'decimals'           => absint( get_option( 'esf_price_num_decimals' , '2' ) ) ,
            'price_format'       => $format ,
                ) ) ) ;

        $unformatted_price = $price ;
        $negative          = $price < 0 ;
        $price             = floatval( $negative ? $price * -1 : $price ) ;
        $price             = number_format( $price , $args[ 'decimals' ] , $args[ 'decimal_separator' ] , $args[ 'thousand_separator' ] ) ;

        $formatted_price = ( $negative ? '-' : '' ) . sprintf( $args[ 'price_format' ] , '<span class="esf-Price-currencySymbol">' . get_esf_currency_symbol( $args[ 'currency' ] ) . '</span>' , $price ) ;
        $return          = '<span class="esf-Price-amount">' . $formatted_price . '</span>' ;

        return apply_filters( 'esf_price' , $return , $price , $args , $unformatted_price ) ;
    }

}

if ( ! function_exists( 'esf_get_allowed_setting_tabs' ) ) {

    function esf_get_allowed_setting_tabs() {

        return apply_filters( 'esf_settings_tabs_array' , array() ) ;
    }

}

if ( ! function_exists( 'esf_get_page_ids' ) ) {

    /**
     * Function to prepare page ids
     * */
    function esf_get_page_ids() {
        $format_page_ids = array() ;
        $pages           = get_pages() ;

        if ( ! esf_check_is_array( $pages ) )
            return $format_page_ids ;

        foreach ( $pages as $page ) {

            if ( ! is_object( $page ) )
                continue ;

            $format_page_ids[ $page->ID ] = $page->post_title ;
        }

        return $format_page_ids ;
    }

}

if ( ! function_exists( 'esf_get_page_id' ) ) {

    /**
     * Function to get page id
     * */
    function esf_get_page_id( $page_name = 'events' , $permalink = false ) {

        if ( $permalink )
            return get_permalink( get_option( 'esf_settings_' . $page_name . '_page_id' ) ) ;

        return get_option( 'esf_account_management_' . $page_name . '_page_id' ) ;
    }

}


if ( ! function_exists( 'esf_get_event_page_url' ) ) {

    /**
     * Function to get event page URL
     * */
    function esf_get_event_page_url() {

        return admin_url( 'edit.php?post_type=' . ESF_Register_Post_Types::EVENT_POSTTYPE ) ;
    }

}

if ( ! function_exists( 'esf_get_the_terms_post' ) ) {

    function esf_get_the_terms_post( $post_id , $taxonomy ) {

        $terms = wp_get_object_terms( $post_id , $taxonomy ) ;

        if ( ! esf_check_is_array( $terms ) || is_wp_error( $terms ) )
            return array() ;

        return $terms ;
    }

}

if ( ! function_exists( 'esf_get_google_calendar_link' ) ) {

    function esf_get_google_calendar_link( $event ) {
        if ( is_null( $event ) ) {
            return false ;
        }

        if ( is_numeric( $event ) ) {
            $event = esf_get_event( $event ) ;
        }

        //Get start date Object
        $start_date_object = ESF_Date_Time::get_date_time_object( $event->get_start_date() , $event->get_timezone() ) ;
        //Get end date Object
        $end_date_object   = ESF_Date_Time::get_date_time_object( $event->get_end_date() , $event->get_timezone() ) ;

        if ( $event->get_all_day() == 'yes' ) {
            $end_date_object->modify( '+' . DAY_IN_SECONDS . ' seconds' ) ;
            $dates = $start_date_object->format( 'Ymd' ) . '/' . $end_date_object->format( 'Ymd' ) ;
        } else {
            $dates = $start_date_object->format( 'Ymd' ) . 'T' . $start_date_object->format( 'Hi00' ) . '/' . $end_date_object->format( 'Ymd' ) . 'T' . $end_date_object->format( 'Hi00' ) ;
        }

        //replace paragraph tag to space for google
        $event_details = str_replace( '</p>' , '</p> ' , $event->get_description() ) ;
        //Strip the all HTML Tags
        $event_details = strip_tags( $event_details ) ;

        if ( strlen( $event_details ) > 996 ) {

            $event_url     = get_permalink( $event->get_id() ) ;
            $event_details = substr( $event_details , 0 , 996 ) ;

            if ( strlen( $event_url ) < 900 )
                $event_details .= sprintf( esc_html__( ' (View Full %1$s Description Here: %2$s)' , ESF_LOCALE ) , $event->get_name() , $event_url ) ;
        }

        $params = array(
            'action'  => 'TEMPLATE' ,
            'text'    => urlencode( strip_tags( $event->get_name() ) ) ,
            'dates'   => $dates ,
            'details' => urlencode( $event_details ) ,
            'trp'     => 'false' ,
            'sprop'   => 'website:' . home_url() ,
                ) ;

        //Check Location exists.
        if ( $event->get_location()->exists() )
            $params[ 'location' ] = urlencode( trim( strip_tags( $event->get_location()->get_formatted_address() ) ) ) ;

        $timezone = ESF_Date_Time::maybe_get_tz_name( $event->get_timezone() ) ;

        // If we have a good timezone string we setup it; UTC doesn't work on Google
        if ( false !== $timezone ) {
            $params[ 'ctz' ] = urlencode( $timezone ) ;
        }

        $params = apply_filters( 'esf_google_calendar_parameters' , $params , $event->get_id() ) ;

        $base_url = 'https://www.google.com/calendar/event' ;
        $url      = add_query_arg( $params , $base_url ) ;

        return $url ;
    }

}

if ( ! function_exists( 'esf_array_insert_after' ) ) {

    function esf_array_insert_after( $array , $key , $insert_value ) {
        $keys  = array_keys( $array ) ;
        $index = array_search( $key , $keys ) ;
        $pos   = false === $index ? count( $array ) : $index + 1 ;

        $insert_value = is_array( $insert_value ) ? $insert_value : array( $insert_value ) ;

        return array_merge( array_slice( $array , 0 , $pos ) , $insert_value , array_slice( $array , $pos ) ) ;
    }

}
