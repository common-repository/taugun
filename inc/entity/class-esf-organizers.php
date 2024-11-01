<?php

/*
 * Organizers
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'ESF_Organizer' ) ) {

    /**
     * ESF_Organizer Class.
     */
    class ESF_Organizer extends ESF_Post {

        /**
         * Post Type
         */
        protected $post_type = ESF_Register_Post_Types::ORGANIZER_POSTTYPE ;

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
         * Email
         */
        protected $esf_email ;

        /**
         * Phone
         */
        protected $esf_phone ;

        /**
         * Web site
         */
        protected $esf_website ;

        /**
         * Additional Info
         */
        protected $esf_additional_info ;

        /**
         * Date
         */
        protected $esf_date ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'esf_image'           => '' ,
            'esf_email'           => '' ,
            'esf_phone'           => '' ,
            'esf_website'         => '' ,
            'esf_additional_info' => '' ,
            'esf_date'            => ''
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->name        = $this->post->post_title ;
            $this->description = $this->post->post_content ;
        }

        /**
         * Get Info
         */
        public function get_formatted_info() {
            $info = '' ;
            if ( $this->get_email() )
                $info .= $this->get_email() . ',<br>' ;
            if ( $this->get_phone() )
                $info .= $this->get_phone() . ',<br>' ;
            if ( $this->get_website() )
                $info .= $this->get_website() ;

            $info = rtrim( $info , ',<br>' ) ;

            return $info ;
        }

        /**
         * Get  Formatted Image 
         */
        public function get_formatted_image() {

            if ( $this->get_image() )
                return $this->get_image() ;

            return ESF_PLUGIN_URL . '/assets/images/organizer-placeholder.png' ;
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
         * Set Email
         */
        public function set_email( $value ) {

            return $this->esf_email = $value ;
        }

        /**
         * Set Phone
         */
        public function set_phone( $value ) {

            return $this->esf_phone = $value ;
        }

        /**
         * Set Website
         */
        public function set_website( $value ) {

            return $this->esf_website = $value ;
        }

        /**
         * Set Additional Info
         */
        public function set_additional_info( $value ) {

            return $this->esf_additional_info = $value ;
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
         * Get Email
         */
        public function get_email() {

            return $this->esf_email ;
        }

        /**
         * Get Phone
         */
        public function get_phone() {

            return $this->esf_phone ;
        }

        /**
         * Get Website
         */
        public function get_website() {

            return $this->esf_website ;
        }

        /**
         * Get Additional Info
         */
        public function get_additional_info() {

            return $this->esf_additional_info ;
        }

        /**
         * Get date
         */
        public function get_date() {

            return $this->esf_date ;
        }

    }

}
    