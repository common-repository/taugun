<?php

/**
 * Initialize the Plugin.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'ESF_Install' ) ) {

    /**
     * Class.
     */
    class ESF_Install {
        /*
         * Plugin Slug
         */

        protected static $plugin_slug = 'esf' ;

        /**
         *  Class initialization.
         */
        public static function init() {
            add_filter( 'plugin_action_links_' . ESF_PLUGIN_SLUG , array( __CLASS__ , 'settings_link' ) ) ;
        }

        /**
         * Install Booking System
         */
        public static function install() {

            ESF_Pages::create_pages() ; // create pages

            self::set_default_values() ; // default values
            self::update_version() ;
        }

        /**
         * Update ESF version to current.
         */
        private static function update_version() {
            update_option( 'esf_free_version' , ESF_VERSION ) ;
        }

        /**
         *  Settings link. 
         */
        public static function settings_link( $links ) {
            $setting_page_link = '<a href="edit.php?post_type=esf-events&page=settings">' . esc_html__( "Settings" , ESF_LOCALE ) . '</a>' ;

            array_unshift( $links , $setting_page_link ) ;

            return $links ;
        }

        /**
         *  Set settings default values  
         */
        public static function set_default_values() {
            if ( ! class_exists( 'ESF_Settings' ) )
                include_once(ESF_PLUGIN_PATH . '/inc/admin/menu/class-esf-settings.php') ;

            //default for settings
            $settings = ESF_Settings::get_settings_pages() ;

            foreach ( $settings as $setting ) {
                $sections = $setting->get_sections() ;
                if ( ! esf_check_is_array( $sections ) )
                    continue ;

                foreach ( $sections as $section_key => $section ) {
                    $settings_array = $setting->get_settings( $section_key ) ;
                    foreach ( $settings_array as $value ) {
                        if ( isset( $value[ 'default' ] ) && isset( $value[ 'id' ] ) ) {
                            if ( get_option( $value[ 'id' ] ) === false )
                                add_option( $value[ 'id' ] , $value[ 'default' ] ) ;
                        }
                    }
                }
            }
        }

    }

    ESF_Install::init() ;
}