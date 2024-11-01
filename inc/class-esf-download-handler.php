<?php

/**
 *  Handles download
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'ESF_Download_Handler' ) ) {

    /**
     * Class
     */
    class ESF_Download_Handler {

        /**
         * Class Initialization.
         */
        public static function init() {
            add_action( 'wp_loaded' , array( __CLASS__ , 'download_ical' ) ) ;
        }

        /*
         * Process Download ICAL
         */

        public static function download_ical() {

            if ( ! isset( $_GET[ 'action' ] ) || sanitize_key( wp_unslash( $_GET[ 'action' ] ) ) != 'esf_download_ics_file' )
                return ;

            if ( ! isset( $_GET[ 'event_id' ] ) )
                return ;

            try {
                $event_id  = absint( $_GET[ 'event_id' ] ) ;
                $event_obj = esf_get_event( $event_id ) ;

                self::download( $event_obj ) ;
            } catch ( Exception $ex ) {

                wp_die( $ex->getMessage() ) ; // WPCS: XSS ok.
            }
        }

        public static function download( $event ) {
            $filename = sanitize_file_name( strtolower( $event->get_name() ) . '.ics' ) ;
            self::headers( $filename ) ;

            //Get start date Object
            $start_date_object = ESF_Date_Time::get_date_time_object( $event->get_start_date() , $event->get_timezone() ) ;
            //Get end date Object
            $end_date_object   = ESF_Date_Time::get_date_time_object( $event->get_end_date() , $event->get_timezone() ) ;

            if ( $event->get_all_day() == 'yes' ) {
                $end_date_object->modify( '+' . DAY_IN_SECONDS . ' seconds' ) ;
                $startdate = $start_date_object->format( 'Ymd' ) ;
                $enddate   = $end_date_object->format( 'Ymd' ) ;
            } else {
                $startdate = $start_date_object->format( 'Ymd' ) . 'T' . $start_date_object->format( 'Hi00' ) ;
                $enddate   = $end_date_object->format( 'Ymd' ) . 'T' . $end_date_object->format( 'Hi00' ) ;
            }

            //replace paragraph tag to space for google
            $event_details = str_replace( '</p>' , '</p> ' , $event->get_description() ) ;
            //Strip the all HTML Tags
            $event_details = strip_tags( $event_details ) ;

            echo "BEGIN:VCALENDAR\n" ;
            echo "VERSION:2.0\n" ;
            echo "METHOD:REQUEST\n" ;
            echo "BEGIN:VEVENT\n" ;
            echo "UID:'" . uniqid() . "'\n" ;
            echo "DTSTAMP:" . date( 'Ymd' ) . 'T' . date( 'His' ) . "\n" ;
            echo "DTSTART:" . $startdate . "\n" ;
            echo "DTEND:" . $enddate . "\n" ;
            echo "SUMMARY:" . $event->get_name() . "\n" ;
            echo "DESCRIPTION:" . $event_details . "\n" ;
            echo "LOCATION:" . trim( strip_tags( $event->get_location()->get_formatted_address() ) ) . "\n" ;
            echo "END:VEVENT\n" ;
            echo "END:VCALENDAR\n" ;
            exit ;
        }

        /*
         * Headers
         */

        public static function headers( $file_name ) {

            header( 'Content-Type: text/Calendar' ) ;
            header( 'Content-Disposition: attachment; filename="' . $file_name . '"' ) ;
        }

    }

    ESF_Download_Handler::init() ;
}
