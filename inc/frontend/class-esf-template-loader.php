<?php

/**
 * Template Loader
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'ESF_Template_Loader' ) ) {

    /**
     *  Class.
     */
    class ESF_Template_Loader {

        /**
         * Class Initialization.
         */
        public static function init() {
            add_filter( 'template_include' , array( __CLASS__ , 'template_loader' ) ) ;
        }

        /**
         * Load a template.
         */
        public static function template_loader( $template ) {

            $default_file = self::get_template_default_file() ;

            if ( $default_file )
                $template = esf_locate_template( $default_file ) ;

            return $template ;
        }

        /**
         * Get a template default file
         */
        public static function get_template_default_file() {

            $default_file = '' ;

            if ( is_singular( ESF_Register_Post_Types::EVENT_POSTTYPE ) ) {
                $default_file = 'single-event' ;
            } elseif ( is_tax( ESF_Register_Post_Types::CATEGORY_TAXONOMY ) || is_tax( ESF_Register_Post_Types::TAG_TAXONOMY ) ) {
                $default_file = 'archive-event' ;
            } elseif ( is_post_type_archive( ESF_Register_Post_Types::EVENT_POSTTYPE ) ) {
                $default_file = 'archive-event' ;
            }

            return $default_file ;
        }

    }

    ESF_Template_Loader::init() ;
}
