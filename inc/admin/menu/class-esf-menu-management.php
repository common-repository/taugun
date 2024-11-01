<?php

/*
 * Menu Management
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'ESF_Menu_Management' ) ) {

    include_once('class-esf-settings.php') ;

    /**
     * ESF_Menu_Management Class.
     */
    class ESF_Menu_Management {

        /**
         * Plugin slug.
         */
        protected static $plugin_slug = 'esf' ;

        /**
         * Parent slug.
         */
        protected static $parent_slug = 'edit.php?post_type=' . ESF_Register_Post_Types::EVENT_POSTTYPE ;

        /**
         * Class initialization.
         */
        public static function init() {
            add_action( 'admin_menu' , array( __CLASS__ , 'add_menu_pages' ) ) ;
        }

        /**
         * Add menu pages
         */
        public static function add_menu_pages() {

            //Location Submenu
            add_submenu_page( 
                    self::$parent_slug , 
                    esc_html__( 'Locations' , ESF_LOCALE ) , 
                    esc_html__( 'Locations' , ESF_LOCALE ) , 
                    'manage_options' , 
                    'locations' , 
                    array( __CLASS__ , 'locations_page' )
            ) ;

            //Organizer Submenu
            add_submenu_page( 
                    self::$parent_slug , 
                    esc_html__( 'Organizers' , ESF_LOCALE ) , 
                    esc_html__( 'Organizers' , ESF_LOCALE ) , 
                    'manage_options' , 
                    'organizers' , 
                    array( __CLASS__ , 'organizers_page' )
            ) ;

            //Settings Submenu
            $settings_page = add_submenu_page( 
                    self::$parent_slug , 
                    esc_html__( 'Settings' , ESF_LOCALE ) , 
                    esc_html__( 'Settings' , ESF_LOCALE ) , 
                    'manage_options' , 
                    'settings' , 
                    array( __CLASS__ , 'settings_page' )
                    ) ;

            add_action( 'load-' . $settings_page , array( __CLASS__ , 'settings_page_init' ) ) ;
        }

        /**
         * Settings page init
         */
        public static function settings_page_init() {
            global $current_tab , $current_section , $current_sub_section ;

            // Include settings pages.
            $settings = ESF_Settings::get_settings_pages() ;

            $tabs = esf_get_allowed_setting_tabs() ;

            // Get current tab/section.
            $current_tab = ( empty( $_GET[ 'tab' ] ) || ! array_key_exists( $_GET[ 'tab' ] , $tabs )) ? key( $tabs ) : sanitize_title( wp_unslash( $_GET[ 'tab' ] ) ) ;

            $section = isset( $settings[ $current_tab ] ) ? $settings[ $current_tab ]->get_sections() : array() ;

            $current_section     = empty( $_REQUEST[ 'section' ] ) ? key( $section ) : sanitize_title( wp_unslash( $_REQUEST[ 'section' ] ) ) ;
            $current_section     = empty( $current_section ) ? $current_tab : $current_section ;
            $current_sub_section = empty( $_REQUEST[ 'subsection' ] ) ? '' : sanitize_title( wp_unslash( $_REQUEST[ 'subsection' ] ) ) ;

            do_action( sanitize_key( self::$plugin_slug . '_settings_save_' . $current_tab ) , $current_section ) ;
            do_action( sanitize_key( self::$plugin_slug . '_settings_reset_' . $current_tab ) , $current_section ) ;
        }

        /**
         * Locations page output
         */
        public static function locations_page() {
            if ( ! class_exists( 'ESF_Locations_Page' ) )
                include ESF_PLUGIN_PATH . '/inc/admin/menu/pages/class-esf-locations.php' ;

            ESF_Locations_Page::save() ;
            ESF_Settings::show_messages() ;
            ESF_Locations_Page::output() ;
        }

        /**
         * Organizers page output
         */
        public static function organizers_page() {
            if ( ! class_exists( 'ESF_Organizers_Page' ) )
                include ESF_PLUGIN_PATH . '/inc/admin/menu/pages/class-esf-organizers.php' ;

            ESF_Organizers_Page::save() ;
            ESF_Settings::show_messages() ;
            ESF_Organizers_Page::output() ;
        }

        /**
         * Settings page output
         */
        public static function settings_page() {
            ESF_Settings::output() ;
        }

    }

    ESF_Menu_Management::init() ;
}