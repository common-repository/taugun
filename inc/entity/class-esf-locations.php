<?php

/*
 * Locations
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'ESF_Location' ) ) {

    /**
     * ESF_Location Class.
     */
    class ESF_Location extends ESF_Post {

        /**
         * Post Type
         */
        protected $post_type = ESF_Register_Post_Types::LOCATION_POSTTYPE ;

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
         * Image
         */
        protected $esf_image ;

        /**
         * Address Line 1
         */
        protected $esf_address_line1 ;

        /**
         * Address Line 2
         */
        protected $esf_address_line2 ;

        /**
         * City
         */
        protected $esf_city ;

        /**
         * State
         */
        protected $esf_state ;

        /**
         * Post Code
         */
        protected $esf_post_code ;

        /**
         * Country
         */
        protected $esf_country ;

        /**
         * Date
         */
        protected $esf_date ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'esf_image'         => '' ,
            'esf_address_line1' => '' ,
            'esf_address_line2' => '' ,
            'esf_city'          => '' ,
            'esf_state'         => '' ,
            'esf_country'       => '' ,
            'esf_post_code'     => '' ,
            'esf_date'          => ''
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->name        = $this->post->post_title ;
            $this->description = $this->post->post_content ;
        }

        /**
         * Formatted address
         */
        public function get_formatted_address() {

            $formatted_address = '<address>' . $this->get_address_line1() . ',<br>' ;

            if ( $this->get_address_line2() )
                $formatted_address .= $this->get_address_line2() . ',<br>' ;

            $formatted_address .= $this->get_city() . ', ' . $this->get_state() . ',<br>' ;
            $formatted_address .= $this->get_country() . ', ' . $this->get_post_code() . '</address>' ;

            return $formatted_address ;
        }

        /**
         * Get  Formatted Image 
         */
        public function get_formatted_image() {

            if ( $this->get_image() )
                return $this->get_image() ;

            return ESF_PLUGIN_URL . '/assets/images/location-placeholder.png' ;
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
         * Set Image
         */
        public function set_image( $value ) {

            return $this->esf_image = $value ;
        }

        /**
         * Set Address Line 1
         */
        public function set_address_line1( $value ) {

            return $this->esf_address_line1 = $value ;
        }

        /**
         * Set Address Line 2
         */
        public function set_address_line2( $value ) {

            return $this->esf_address_line2 = $value ;
        }

        /**
         * Set City
         */
        public function set_city( $value ) {

            return $this->esf_city = $value ;
        }

        /**
         * Set State
         */
        public function set_state( $value ) {

            return $this->esf_state = $value ;
        }

        /**
         * Set Country
         */
        public function set_country( $value ) {

            return $this->esf_country = $value ;
        }

        /**
         * Set Post Code
         */
        public function set_post_code( $value ) {

            return $this->esf_post_code = $value ;
        }

        /**
         * Set date
         */
        public function set_date( $value ) {

            return $this->esf_date = $value ;
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
         * Get Image
         */
        public function get_image() {

            return $this->esf_image ;
        }

        /**
         * Get Address Line 1
         */
        public function get_address_line1() {

            return $this->esf_address_line1 ;
        }

        /**
         * Get Address Line 2
         */
        public function get_address_line2() {

            return $this->esf_address_line2 ;
        }

        /**
         * Get City
         */
        public function get_city() {

            return $this->esf_city ;
        }

        /**
         * Get State
         */
        public function get_state() {

            return $this->esf_state ;
        }

        /**
         * Get Country
         */
        public function get_country() {

            return $this->esf_country ;
        }

        /**
         * Get Post Code
         */
        public function get_post_code() {

            return $this->esf_post_code ;
        }

        /**
         * Get date
         */
        public function get_date() {

            return $this->esf_date ;
        }

    }

}
    