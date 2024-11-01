<?php

/*
 * Plugin Name: Taugun Events Calendar
 * Description: Taugun is a free Events Calendar plugin which allows you to create and manage events in your WordPress site.
 * Version: 1.6
 * Author: Flintop
 * Author URI: https://flintop.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: taugun
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'Taugun_Events' ) ) {

    /**
     * Main Taugun_Events Class.
     * */
    final class Taugun_Events {

        /**
         * Version
         * */
        private $version = '1.6' ;

        /**
         * The single instance of the class.
         * */
        protected static $_instance = null ;

        /**
         * Load Taugun_Events Class in Single Instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self() ;
            }
            return self::$_instance ;
        }

        /* Cloning has been forbidden */

        public function __clone() {
            _doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
        }

        /**
         * Unserialize the class data has been forbidden
         * */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , '1.0' ) ;
        }

        /**
         * Constructor
         * */
        public function __construct() {

            /* Include once will help to avoid fatal error by load the files when you call init hook */
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

            $this->header_already_sent_problem() ;
            $this->define_constants() ;
            $this->translate_file() ;
            $this->include_files() ;
            $this->init_hooks() ;
        }

        /**
         * Function to prevent header error that says you have already sent the header.
         */
        private function header_already_sent_problem() {
            ob_start() ;
        }

        /**
         * Initialize the translate files.
         * */
        private function translate_file() {
            load_plugin_textdomain( ESF_LOCALE , false , dirname( plugin_basename( __FILE__ ) ) . '/languages' ) ;
        }

        /**
         * Prepare the constants value array.
         * */
        private function define_constants() {

            $constant_array = array(
                'ESF_VERSION'        => $this->version ,
                'ESF_PLUGIN_FILE'    => __FILE__ ,
                'ESF_LOCALE'         => 'taugun' ,
                'ESF_FOLDER_NAME'    => 'taugun' ,
                'ESF_ADMIN_URL'      => admin_url( 'admin.php' ) ,
                'ESF_ADMIN_AJAX_URL' => admin_url( 'admin-ajax.php' ) ,
                'ESF_PLUGIN_SLUG'    => plugin_basename( __FILE__ ) ,
                'ESF_PLUGIN_PATH'    => untrailingslashit( plugin_dir_path( __FILE__ ) ) ,
                'ESF_PLUGIN_URL'     => untrailingslashit( plugins_url( '/' , __FILE__ ) ) ,
                    ) ;

            $constant_array = apply_filters( 'esf_define_constants' , $constant_array ) ;

            if ( is_array( $constant_array ) && ! empty( $constant_array ) ) {
                foreach ( $constant_array as $name => $value ) {
                    $this->define_constant( $name , $value ) ;
                }
            }
        }

        /**
         * Define the Constants value.
         * */
        private function define_constant( $name , $value ) {
            if ( ! defined( $name ) ) {
                define( $name , $value ) ;
            }
        }

        /**
         * Include required files
         * */
        private function include_files() {

            //function
            include_once('inc/esf-common-functions.php') ;

            //Abstract
            include_once('inc/abstracts/class-esf-post.php') ;

            //class
            include_once('inc/class-esf-register-post-type.php') ;

            include_once('inc/class-esf-install.php') ;
            include_once('inc/class-esf-datetime.php') ;
            include_once('inc/class-esf-query.php') ;
            include_once('inc/class-esf-pages.php') ;
            include_once('inc/privacy/class-esf-privacy.php') ;

            //Entity
            include_once('inc/entity/class-esf-events.php') ;
            include_once('inc/entity/class-esf-locations.php') ;
            include_once('inc/entity/class-esf-organizers.php') ;

            if ( is_admin() )
                $this->include_admin_files() ;

            if ( ! is_admin() || defined( 'DOING_AJAX' ) )
                $this->include_frontend_files() ;
        }

        /**
         * Include admin files
         * */
        private function include_admin_files() {
            include_once('inc/admin/menu/class-esf-post-type-handler.php') ;
            include_once('inc/admin/class-esf-admin-assets.php') ;
            include_once('inc/admin/class-esf-admin-ajax.php') ;
            include_once('inc/admin/menu/class-esf-menu-management.php') ;
        }

        /**
         * Include frontend files
         * */
        private function include_frontend_files() {
            include_once('inc/class-esf-download-handler.php') ;
            include_once('inc/frontend/class-esf-frontend-assets.php') ;
            include_once('inc/frontend/class-esf-template-loader.php') ;
            include_once('inc/frontend/class-esf-archive-page-handler.php') ;
        }

        /**
         * Define the hooks 
         * */
        private function init_hooks() {

            register_activation_hook( __FILE__ , array( 'ESF_Install' , 'install' ) ) ;

            //flush rewrite rules
            register_activation_hook( __FILE__ , array( 'ESF_Register_Post_Types' , 'flush_rewrite_rules' ) ) ;
            register_deactivation_hook( __FILE__ , array( 'ESF_Register_Post_Types' , 'flush_rewrite_rules' ) ) ;
        }

    }

}

if ( ! function_exists( 'ESF' ) ) {

    function ESF() {
        if ( class_exists( 'Taugun_Events' ) )
            return Taugun_Events::instance() ;

        return false ;
    }

}

//initialize the plugin. 
ESF() ;

