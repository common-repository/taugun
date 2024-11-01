<?php

/**
 * Admin Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'ESF_Admin_Assets' ) ) {

    /**
     * Class.
     */
    class ESF_Admin_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {

            add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
            add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
        }

        /**
         * Enqueue external css files
         */
        public static function external_css_files() {

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            $screen_ids   = esf_page_screen_ids() ;
            $newscreenids = get_current_screen() ;
            $screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            if ( ! in_array( $screenid , $screen_ids ) )
                return ;

            wp_enqueue_style( 'font-awesome' , ESF_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , ESF_VERSION ) ;
            wp_enqueue_style( 'esf-admin' , ESF_PLUGIN_URL . '/assets/css/backend/admin.css' , array() , ESF_VERSION ) ;
            wp_enqueue_style( 'jquery-ui' , ESF_PLUGIN_URL . '/assets/css/jquery-ui' . $suffix . '.css' , array() , ESF_VERSION ) ;
            wp_enqueue_style( 'esf-posttable' , ESF_PLUGIN_URL . '/assets/css/backend/post-table.css' , array() , ESF_VERSION ) ;
        }

        /**
         * Enqueue external js files
         */
        public static function external_js_files() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

            $screen_ids   = esf_page_screen_ids() ;
            $newscreenids = get_current_screen() ;
            $screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            $enqueue_array = array(
                'esf-admin'   => array(
                    'callable' => array( 'ESF_Admin_Assets' , 'admin' ) ,
                    'restrict' => in_array( $screenid , $screen_ids ) ,
                ) ,
                'esf-select2' => array(
                    'callable' => array( 'ESF_Admin_Assets' , 'select2' ) ,
                    'restrict' => in_array( $screenid , $screen_ids ) ,
                ) ,
                    ) ;

            $enqueue_array = apply_filters( 'esf_admin_assets_array' , $enqueue_array ) ;
            if ( ! esf_check_is_array( $enqueue_array ) )
                return ;

            foreach ( $enqueue_array as $key => $enqueue ) {
                if ( ! esf_check_is_array( $enqueue ) )
                    continue ;

                if ( $enqueue[ 'restrict' ] )
                    call_user_func_array( $enqueue[ 'callable' ] , array( $suffix ) ) ;
            }
        }

        /**
         * Enqueue Admin end required JS files
         */
        public static function admin( $suffix ) {
            //media
            wp_enqueue_media() ;

            wp_register_script( 'blockUI' , ESF_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' ) , '2.70.0' ) ;
            wp_enqueue_script( 'esf-admin' , ESF_PLUGIN_URL . '/assets/js/admin/admin.js' , array( 'jquery' , 'blockUI' ) , ESF_VERSION ) ;
        }

        /**
         * Enqueue select2 scripts and css
         */
        public static function select2( $suffix ) {
            wp_enqueue_style( 'select2' , ESF_PLUGIN_URL . '/assets/css/select2/select2' . $suffix . '.css' , array() , '4.0.5' ) ;

            wp_register_script( 'select2' , ESF_PLUGIN_URL . '/assets/js/select2/select2' . $suffix . '.js' , array( 'jquery' ) , '4.0.5' ) ;
            wp_enqueue_script( 'esf-enhanced' , ESF_PLUGIN_URL . '/assets/js/esf-enhanced.js' , array( 'jquery' , 'select2' , 'jquery-ui-datepicker' , 'iris' ) , ESF_VERSION ) ;
            wp_localize_script(
                    'esf-enhanced' , 'esf_enhanced_select_params' , array(
                'search_nonce' => wp_create_nonce( 'esf-search-nonce' ) ,
                    )
            ) ;
        }

    }

    ESF_Admin_Assets::init() ;
}
