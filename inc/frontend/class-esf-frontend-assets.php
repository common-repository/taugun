<?php

/**
 * Frontend Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'ESF_Fronend_Assets' ) ) {

    /**
     * Class.
     */
    class ESF_Fronend_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {

            add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
            add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
        }

        /**
         * Enqueue external CSS files
         */
        public static function external_css_files() {

            wp_register_style( 'esf-events-inline-style' , false ) ; // phpcs:ignore
            wp_enqueue_style( 'esf-events-inline-style' ) ;
            wp_enqueue_style( 'font-awesome' , ESF_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , ESF_VERSION ) ;
            wp_enqueue_style( 'esf-single-event' , ESF_PLUGIN_URL . '/assets/css/frontend/single-event.css' , array() , ESF_VERSION ) ;
            wp_enqueue_style( 'esf-archive-event' , ESF_PLUGIN_URL . '/assets/css/frontend/archive-event.css' , array() , ESF_VERSION ) ;

            $contents = get_option( 'esf_general_custom_css' , '' ) ;
            wp_add_inline_style( 'esf-events-inline-style' , $contents ) ;
        }

        /**
         * Enqueue external JS files
         */
        public static function external_js_files() {
            wp_enqueue_script( 'esf-archive-event' , ESF_PLUGIN_URL . '/assets/js/frontend/archive-event.js' , array( 'jquery' ) , ESF_VERSION ) ;
            wp_localize_script( 'esf-archive-event' , 'esf_archive_event_params' , array(
                'ajax_url'     => admin_url( 'admin-ajax.php' ) ,
                'events_nonce' => wp_create_nonce( 'esf-events-nonce' ) ,
                    )
            ) ;
        }

    }

    ESF_Fronend_Assets::init() ;
}
