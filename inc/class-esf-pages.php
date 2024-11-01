<?php

/**
 * Pages
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'ESF_Pages' ) ) {


    /**
     * Class.
     */
    class ESF_Pages {
        /*
         * Plugin Slug
         */

        protected static $plugin_slug = 'esf' ;

        /*
         * Option Slug
         */
        protected static $option_slug = 'esf_account_management' ;

        /*
         * Class Initialization
         */

        public static function init() {
            add_filter( 'display_post_states' , array( __CLASS__ , 'post_states' ) , 10 , 2 ) ;
        }

        /*
         * Denotes the post states as such in the pages list table.
         */

        public static function post_states( $post_states , $post ) {

            if ( esf_get_page_id( 'events' ) == $post->ID ) {
                $post_states[ self::$plugin_slug . '_events_page' ] = esc_html__( 'Events Page' , ESF_LOCALE ) ;
            }

            return $post_states ;
        }

        /**
         * Create pages
         */
        public static function create_pages() {

            $pages = apply_filters(
                    self::$plugin_slug . '_create_pages' , array(
                'events' => array(
                    'name'    => esc_html_x( 'events' , 'Page slug' , ESF_LOCALE ) ,
                    'title'   => esc_html_x( 'Events' , 'Page title' , ESF_LOCALE ) ,
                    'content' => '' ,
                    'option'  => self::$option_slug . '_events_page_id'
                )
                    )
                    ) ;

            foreach ( $pages as $page_args ) {
                self::create( $page_args ) ;
            }
        }

        /*
         * Creat page
         */

        public static function create( $page_args = array() ) {

            $defalut_page_args = array(
                'name'    => '' ,
                'title'   => '' ,
                'content' => '' ,
                'option'  => '' ,
                    ) ;

            $page_args = wp_parse_args( $page_args , $defalut_page_args ) ;

            extract( $page_args ) ;

            $option_value = get_option( $option ) ;

            if ( ! empty( $option ) && ($page_object = get_post( $option_value )) ) {
                if ( $page_object->post_type == 'page' ) {
                    if ( ! in_array( $page_object->post_status , array( 'pending' , 'trash' , 'future' , 'auto-draft' ) ) ) {
                        return $page_object->ID ;
                    }
                }
            }

            $page_data = array(
                'post_status'    => 'publish' ,
                'post_type'      => 'page' ,
                'post_author'    => 1 ,
                'post_name'      => esc_sql( $name ) ,
                'post_title'     => $title ,
                'post_content'   => $content ,
                'comment_status' => 'closed' ,
                    ) ;

            $page_id = wp_insert_post( $page_data ) ;

            if ( $option )
                update_option( $option , $page_id ) ;

            return $page_id ;
        }

    }

    ESF_Pages::init() ;
}
