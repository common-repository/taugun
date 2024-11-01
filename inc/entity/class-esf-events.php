<?php

/*
 * Events
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'ESF_Event' ) ) {

    /**
     * ESF_Event Class.
     */
    class ESF_Event extends ESF_Post {

        /**
         * Post Type
         */
        protected $post_type = ESF_Register_Post_Types::EVENT_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'publish' ;

        /**
         * Name
         */
        protected $name ;

        /**
         * Description
         */
        protected $description ;

        /**
         * Featured Image
         */
        protected $featured_image ;

        /**
         * Category
         */
        protected $category ;

        /**
         * Tag
         */
        protected $tag ;

        /**
         * Start Date
         */
        protected $esf_start_date ;

        /**
         * End Date
         */
        protected $esf_end_date ;

        /**
         * Event Start Date
         */
        protected $esf_event_start_date ;

        /**
         * Event End Date
         */
        protected $esf_event_end_date ;

        /**
         * All Day
         */
        protected $esf_all_day ;

        /**
         * Price
         */
        protected $esf_price ;

        /**
         * Website
         */
        protected $esf_website ;

        /**
         * Time Zone
         */
        protected $esf_timezone ;

        /**
         * Location ID
         */
        protected $esf_location_id ;

        /**
         * Organizer ID
         */
        protected $esf_organizer_id ;

        /**
         * Organizer
         */
        protected $organizer ;

        /**
         * Location
         */
        protected $location ;

        /**
         * WordPress start date
         */
        protected $wp_start_date ;

        /**
         * WordPress End date
         */
        protected $wp_end_date ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'esf_price'            => '' ,
            'esf_start_date'       => '' ,
            'esf_end_date'         => '' ,
            'esf_event_start_date' => '' ,
            'esf_event_end_date'   => '' ,
            'esf_all_day'          => '' ,
            'esf_location_id'      => '' ,
            'esf_organizer_id'     => '' ,
            'esf_website'          => '' ,
            'esf_timezone'         => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->name        = $this->post->post_title ;
            $this->description = $this->post->post_content ;
        }

        /**
         * Get Location 
         */
        public function get_location() {

            if ( $this->location )
                return $this->location ;

            $this->location = new ESF_Location( $this->get_location_id() ) ;

            return $this->location ;
        }

        /**
         * Get Organizer 
         */
        public function get_organizer() {

            if ( $this->organizer )
                return $this->organizer ;

            $this->organizer = new ESF_Organizer( $this->get_organizer_id() ) ;

            return $this->organizer ;
        }

        /**
         * Get Permalink URL
         */
        public function get_permalink() {
            return get_permalink( $this->get_id() ) ;
        }

        /**
         * Display date time
         */
        public function display_datetime( $type ) {
            switch ( $type ) {
                case 'start-end-date':
                    $date_time    = $this->format_datetime( 'M d' ) . ' - ' . $this->format_datetime( 'M d' , false ) ;
                    break ;
                case 'start-datetime':
                    $start_object = $this->format_datetime( 'M d' , true , true ) ;
                    $date_time    = $start_object->format( 'M d' ) ;
                    if ( $this->get_all_day() != 'yes' )
                        $date_time    .= ' @' . $start_object->format( 'h:i a' ) ;

                    if ( get_option( 'esf_general_hide_utc' , 'yes' ) == 'yes' )
                        $date_time  .= ' (UTC ' . $start_object->format( 'P' ) . ')' ;
                    break ;
                case 'end-datetime':
                    $end_object = $this->format_datetime( 'M d' , false , true ) ;
                    $date_time  = $end_object->format( 'M d' ) ;
                    if ( $this->get_all_day() != 'yes' )
                        $date_time  .= ' @' . $end_object->format( 'h:i a' ) ;

                    if ( get_option( 'esf_general_hide_utc' , 'yes' ) == 'yes' )
                        $date_time    .= ' (UTC ' . $end_object->format( 'P' ) . ')' ;
                    break ;
                case 'start-end-datetime':
                    $start_object = $this->format_datetime( 'M d' , true , true ) ;
                    $date_time    = $start_object->format( 'M d' ) ;
                    if ( $this->get_all_day() != 'yes' )
                        $date_time    .= ' @' . $start_object->format( 'h:i a' ) ;

                    $date_time .= ' - ' ;

                    $end_object = $this->format_datetime( 'M d' , false , true ) ;
                    $date_time  .= $end_object->format( 'M d' ) ;
                    if ( $this->get_all_day() != 'yes' )
                        $date_time  .= ' @' . $end_object->format( 'h:i a' ) ;

                    if ( get_option( 'esf_general_hide_utc' , 'yes' ) == 'yes' )
                        $date_time .= ' (UTC ' . $end_object->format( 'P' ) . ')' ;
                    break ;
                case 'start-date':
                    $date_time = $this->format_datetime( 'M d' ) ;
                    break ;
                case 'end-date':
                    $date_time = $this->format_datetime( 'M d' , false ) ;
                    break ;
            }


            return $date_time ;
        }

        /**
         * Format Date Time
         */
        public function format_datetime( $format , $start_date = true , $object = false ) {
            $date_object = ($start_date) ? $this->get_wp_start_date() : $this->get_wp_end_date() ;

            if ( $object )
                return $date_object ;

            return $date_object->format( $format ) ;
        }

        /**
         * Maybe WordPress timezone
         */
        public function maybe_wp_timezone() {
            if ( $this->get_timezone() )
                return $this->get_timezone() ;

            return ESF_Date_Time::get_wp_timezone() ;
        }

        /**
         * Get WordPress Start Date 
         */
        public function get_wp_start_date() {

            if ( $this->wp_start_date )
                return $this->wp_start_date ;

            $this->wp_start_date = ESF_Date_Time::get_date_time_object( $this->get_start_date() , $this->maybe_wp_timezone() ) ;

            return $this->wp_start_date ;
        }

        /**
         * Get WordPress End Date
         */
        public function get_wp_end_date() {

            if ( $this->wp_end_date )
                return $this->wp_end_date ;

            $this->wp_end_date = ESF_Date_Time::get_date_time_object( $this->get_end_date() , $this->maybe_wp_timezone() ) ;

            return $this->wp_end_date ;
        }

        /**
         * Get  Formatted Featured Image 
         */
        public function get_formatted_featured_image() {

            if ( $this->get_featured_image() )
                return $this->get_featured_image() ;

            return ESF_PLUGIN_URL . '/assets/images/single-event-placeholder.png' ;
        }

        /**
         * Get Featured Image 
         */
        public function get_featured_image() {

            if ( $this->featured_image )
                return $this->featured_image ;

            $this->featured_image = get_the_post_thumbnail_url( $this->get_id() ) ;

            return $this->featured_image ;
        }

        /**
         * Get Category
         */
        public function get_category() {

            if ( $this->category )
                return $this->category ;

            $this->category = esf_get_the_terms_post( $this->get_id() , ESF_Register_Post_Types::CATEGORY_TAXONOMY ) ;

            return $this->category ;
        }

        /**
         * Get Tag
         */
        public function get_tag() {

            if ( $this->tag )
                return $this->tag ;

            $this->tag = esf_get_the_terms_post( $this->get_id() , ESF_Register_Post_Types::TAG_TAXONOMY ) ;

            return $this->tag ;
        }

        /**
         * Get Formatted Date Time
         */
        public function get_formatted_datetime( $datetime = true , $start_date = true , $wp_zone = true ) {
            $date = ($start_date) ? $this->esf_start_date : $this->esf_end_date ;

            //if empty return 
            if ( empty( $date ) )
                return '' ;

            $time_zone = $this->get_timezone() ;

            if ( $wp_zone || ! $time_zone ) {
                $time_zone = false ;
            }

            $date_object = ESF_Date_Time::get_date_time_object( $date , $time_zone ) ;

            switch ( $datetime ) {
                case 'time':

                    return $date_object->format( 'H:i' ) ;
                    break ;

                case 'date':

                    return $date_object->format( 'Y-m-d' ) ;
                    break ;
            }

            return $date_object->format( 'Y-m-d H:i:s' ) ;
        }

        /**
         * Set Name
         */
        public function set_name( $value ) {

            return $this->name = $value ;
        }

        /**
         * Set Description
         */
        public function set_description( $value ) {

            return $this->description = $value ;
        }

        /**
         * Set Start Date
         */
        public function set_start_date( $value ) {

            return $this->esf_start_date = $value ;
        }

        /**
         * Set End Date
         */
        public function set_end_date( $value ) {

            return $this->esf_end_date = $value ;
        }

        /**
         * Set Event Start Date
         */
        public function set_event_start_date( $value ) {

            return $this->esf_event_start_date = $value ;
        }

        /**
         * Set Event End Date
         */
        public function set_event_end_date( $value ) {

            return $this->esf_event_end_date = $value ;
        }

        /**
         * Set Price
         */
        public function set_price( $value ) {

            return $this->esf_price = $value ;
        }

        /**
         * Set Website
         */
        public function set_website( $value ) {

            return $this->esf_website = $value ;
        }

        /**
         * Set Day
         */
        public function set_all_day( $value ) {

            return $this->esf_all_day = $value ;
        }

        /**
         * Set TimeZone
         */
        public function set_timezone( $value ) {

            return $this->esf_timezone = $value ;
        }

        /**
         * Set Location ID
         */
        public function set_location_id( $value ) {

            return $this->esf_location_id = $value ;
        }

        /**
         * Set Organizer ID
         */
        public function set_organizer_id( $value ) {

            return $this->esf_organizer_id = $value ;
        }

        /**
         * Get name
         */
        public function get_name() {

            return $this->name ;
        }

        /**
         * Get Description
         */
        public function get_description() {

            return $this->description ;
        }

        /**
         * Get Start date
         */
        public function get_start_date() {

            return $this->esf_start_date ;
        }

        /**
         * Get End Date
         */
        public function get_end_date() {

            return $this->esf_end_date ;
        }

        /**
         * Get Event Start Date
         */
        public function get_event_start_date() {

            return $this->esf_event_start_date ;
        }

        /**
         * Get Event End Date
         */
        public function get_event_end_date() {

            return $this->esf_event_end_date ;
        }

        /**
         * Get Price
         */
        public function get_price() {

            return $this->esf_price ;
        }

        /**
         * Get Website
         */
        public function get_website() {

            return $this->esf_website ;
        }

        /**
         * Get Day
         */
        public function get_all_day() {
            return $this->esf_all_day ;
        }

        /**
         * Get TimeZone
         */
        public function get_timezone() {

            return $this->esf_timezone ;
        }

        /**
         * Get Location ID
         */
        public function get_location_id() {

            return $this->esf_location_id ;
        }

        /**
         * Get Organizer ID
         */
        public function get_organizer_id() {

            return $this->esf_organizer_id ;
        }

    }

}
    