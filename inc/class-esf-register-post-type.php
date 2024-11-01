<?php

/**
 * Custom Post Type.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'ESF_Register_Post_Types' ) ) {

    /**
     * ESF_Register_Post_Types Class.
     */
    class ESF_Register_Post_Types {
        /*
         * Event Slug
         */

        public static $event_slug ;

        /*
         * Category Slug
         */
        public static $category_slug ;

        /*
         * Tag Slug
         */
        public static $tag_slug ;

        /*
         * Event Post Type
         */

        const EVENT_POSTTYPE = 'esf-events' ;

        /*
         * Location Post Type
         */
        const LOCATION_POSTTYPE = 'esf-locations' ;

        /*
         * Organizer Post Type
         */
        const ORGANIZER_POSTTYPE = 'esf-organizers' ;

        /*
         * Category Taxonomy
         */
        const CATEGORY_TAXONOMY = 'esf_events_cat' ;

        /*
         * Tag Taxonomy
         */
        const TAG_TAXONOMY = 'esf_events_tag' ;

        /**
         * ESF_Register_Post_Types Class initialization.
         */
        public static function init() {

            add_action( 'init' , array( __CLASS__ , 'register_custom_taxonomies' ) ) ;
            add_action( 'init' , array( __CLASS__ , 'register_custom_post_types' ) ) ;

            //flush rewrite rules
            add_action( 'esf_general_settings_after_save' , array( __CLASS__ , 'flush_rewrite_rules' ) ) ;
        }

        /*
         * Get Tag Slug
         */

        public static function get_event_slug() {

            return apply_filters( 'esf_custom_event_slug' , get_option( 'esf_general_custom_event_slug' , 'event' ) ) ;
        }

        /*
         * Get Category Slug
         */

        public static function get_category_slug() {

            return apply_filters( 'esf_custom_category_slug' , get_option( 'esf_general_custom_category_slug' , 'category' ) ) ;
        }

        /*
         * Get Tag Slug
         */

        public static function get_tag_slug() {

            return apply_filters( 'esf_custom_tag_slug' , get_option( 'esf_general_custom_tag_slug' , 'tag' ) ) ;
        }

        /*
         * Register Custom Taxonomies
         */

        public static function register_custom_taxonomies() {
            if ( ! is_blog_installed() ) {
                return ;
            }

            $custom_taxonomies = array(
                self::CATEGORY_TAXONOMY => array( 'ESF_Register_Post_Types' , 'category_taxonomy_args' ) ,
                self::TAG_TAXONOMY      => array( 'ESF_Register_Post_Types' , 'tag_taxonomy_args' ) ,
                    ) ;

            $custom_taxonomies = apply_filters( 'esf_add_custom_taxonomies' , $custom_taxonomies ) ;

            // return if no post type to register
            if ( ! esf_check_is_array( $custom_taxonomies ) )
                return ;

            foreach ( $custom_taxonomies as $taxonomy => $args_function ) {

                $args = array() ;
                if ( $args_function )
                    $args = call_user_func_array( $args_function , $args ) ;

                //Register custom Taxonomy
                register_taxonomy( $taxonomy , $args[ 'object_type' ] , $args[ 'args' ] ) ;
            }
        }

        /*
         * Register Custom Post types
         */

        public static function register_custom_post_types() {
            if ( ! is_blog_installed() ) {
                return ;
            }

            $custom_post_types = array(
                self::EVENT_POSTTYPE     => array( 'ESF_Register_Post_Types' , 'events_post_type_args' ) ,
                self::LOCATION_POSTTYPE  => array( 'ESF_Register_Post_Types' , 'location_post_type_args' ) ,
                self::ORGANIZER_POSTTYPE => array( 'ESF_Register_Post_Types' , 'organizer_post_type_args' ) ,
                    ) ;

            $custom_post_types = apply_filters( 'esf_add_custom_post_types' , $custom_post_types ) ;

            // return if no post type to register
            if ( ! esf_check_is_array( $custom_post_types ) )
                return ;

            foreach ( $custom_post_types as $post_type => $args_function ) {

                $args = array() ;
                if ( $args_function )
                    $args = call_user_func_array( $args_function , $args ) ;

                //Register custom post type
                register_post_type( $post_type , $args ) ;
            }
        }

        /*
         * Prepare Events Category Taxonomy arguments
         */

        public static function category_taxonomy_args() {

            $object_type = array( ESF_Register_Post_Types::EVENT_POSTTYPE ) ;

            $args = array(
                'hierarchical' => true ,
                'label'        => esc_html__( 'Categories' , ESF_LOCALE ) ,
                'labels'       => array(
                    'name'              => esc_html__( 'Event categories' , ESF_LOCALE ) ,
                    'singular_name'     => esc_html__( 'Category' , ESF_LOCALE ) ,
                    'menu_name'         => esc_html_x( 'Categories' , 'Admin menu name' , ESF_LOCALE ) ,
                    'search_items'      => esc_html__( 'Search categories' , ESF_LOCALE ) ,
                    'all_items'         => esc_html__( 'All categories' , ESF_LOCALE ) ,
                    'parent_item'       => esc_html__( 'Parent category' , ESF_LOCALE ) ,
                    'parent_item_colon' => esc_html__( 'Parent category:' , ESF_LOCALE ) ,
                    'edit_item'         => esc_html__( 'Edit category' , ESF_LOCALE ) ,
                    'update_item'       => esc_html__( 'Update category' , ESF_LOCALE ) ,
                    'add_new_item'      => esc_html__( 'Add new category' , ESF_LOCALE ) ,
                    'new_item_name'     => esc_html__( 'New category name' , ESF_LOCALE ) ,
                    'not_found'         => esc_html__( 'No categories found' , ESF_LOCALE ) ,
                ) ,
                'show_ui'      => true ,
                'query_var'    => true ,
                'capabilities' => array(
                    'manage_terms' => 'manage_categories' ,
                    'edit_terms'   => 'manage_categories' ,
                    'delete_terms' => 'manage_categories' ,
                    'assign_terms' => 'edit_posts'
                ) ,
                'rewrite'      => array(
                    'slug'         => self::get_category_slug() ,
                    'with_front'   => false ,
                    'hierarchical' => true ,
                ) ,
                    ) ;

            return apply_filters( 'esf_events_category_taxonomy_args' , array( 'object_type' => $object_type , 'args' => $args ) ) ;
        }

        /*
         * Prepare Events Tag Taxonomy arguments
         */

        public static function tag_taxonomy_args() {

            $object_type = array( ESF_Register_Post_Types::EVENT_POSTTYPE ) ;

            $args = array(
                'hierarchical' => false ,
                'label'        => esc_html__( 'Tags' , ESF_LOCALE ) ,
                'labels'       => array(
                    'name'              => esc_html__( 'Event Tags' , ESF_LOCALE ) ,
                    'singular_name'     => esc_html__( 'Tag' , ESF_LOCALE ) ,
                    'menu_name'         => esc_html_x( 'Tags' , 'Admin menu name' , ESF_LOCALE ) ,
                    'search_items'      => esc_html__( 'Search tags' , ESF_LOCALE ) ,
                    'all_items'         => esc_html__( 'All Tags' , ESF_LOCALE ) ,
                    'parent_item'       => esc_html__( 'Parent Tag' , ESF_LOCALE ) ,
                    'parent_item_colon' => esc_html__( 'Parent Tag:' , ESF_LOCALE ) ,
                    'edit_item'         => esc_html__( 'Edit Tag' , ESF_LOCALE ) ,
                    'update_item'       => esc_html__( 'Update Tag' , ESF_LOCALE ) ,
                    'add_new_item'      => esc_html__( 'Add new Tag' , ESF_LOCALE ) ,
                    'new_item_name'     => esc_html__( 'New tag name' , ESF_LOCALE ) ,
                    'not_found'         => esc_html__( 'No tags found' , ESF_LOCALE ) ,
                ) ,
                'show_ui'      => true ,
                'query_var'    => true ,
                'capabilities' => array(
                    'manage_terms' => 'manage_categories' ,
                    'edit_terms'   => 'manage_categories' ,
                    'delete_terms' => 'manage_categories' ,
                    'assign_terms' => 'edit_posts'
                ) ,
                'rewrite'      => array(
                    'slug'         => self::get_tag_slug() ,
                    'with_front'   => false ,
                    'hierarchical' => true ,
                ) ,
                    ) ;

            return apply_filters( 'esf_events_tag_taxonomy_args' , array( 'object_type' => $object_type , 'args' => $args ) ) ;
        }

        /*
         * Prepare Events Post type arguments
         */

        public static function events_post_type_args() {
            $events_page_id = esf_get_page_id() ;

            $has_archive = $events_page_id && get_post( $events_page_id ) ? urldecode( get_page_uri( $events_page_id ) ) : 'events' ;

            return apply_filters( 'esf_events_post_type_args' , array(
                'labels'              => array(
                    'name'               => esc_html__( 'Events' , ESF_LOCALE ) ,
                    'singular_name'      => esc_html__( 'Event' , ESF_LOCALE ) ,
                    'all_items'          => esc_html__( 'Events' , ESF_LOCALE ) ,
                    'menu_name'          => esc_html_x( 'Taugun' , 'Admin menu name' , ESF_LOCALE ) ,
                    'add_new'            => esc_html__( 'Add Event' , ESF_LOCALE ) ,
                    'add_new_item'       => esc_html__( 'Add New Event' , ESF_LOCALE ) ,
                    'edit'               => esc_html__( 'Edit' , ESF_LOCALE ) ,
                    'edit_item'          => esc_html__( 'Edit Event' , ESF_LOCALE ) ,
                    'new_item'           => esc_html__( 'New Event' , ESF_LOCALE ) ,
                    'view'               => esc_html__( 'View Event' , ESF_LOCALE ) ,
                    'view_item'          => esc_html__( 'View Event' , ESF_LOCALE ) ,
                    'view_items'         => esc_html__( 'View Events' , ESF_LOCALE ) ,
                    'search_items'       => esc_html__( 'Search Events' , ESF_LOCALE ) ,
                    'not_found'          => esc_html__( 'No Data found' , ESF_LOCALE ) ,
                    'not_found_in_trash' => esc_html__( 'No Data found in trash' , ESF_LOCALE ) ,
                ) ,
                'description'         => esc_html__( 'Here you can able to see list of Event' , ESF_LOCALE ) ,
                'public'              => true ,
                'show_ui'             => true ,
                'capability_type'     => 'post' ,
                'publicly_queryable'  => true ,
                'exclude_from_search' => false ,
                'hierarchical'        => false , // Hierarchical causes memory issues - WP loads all records!
                'show_in_nav_menus'   => false ,
                'show_in_menu'        => true ,
                'menu_icon'           => ESF_PLUGIN_URL . '/assets/images/dash-icon.png' ,
                'supports'            => array( 'title' , 'editor' , 'thumbnail' , 'author' ) ,
                'query_var'           => true ,
                'map_meta_cap'        => true ,
                'taxonomies'          => array( ESF_Register_Post_Types::CATEGORY_TAXONOMY , ESF_Register_Post_Types::TAG_TAXONOMY ) ,
                'rewrite'             => array(
                    'slug'       => self::get_event_slug() ,
                    'with_front' => false ,
                    'feeds'      => true ,
                ) ,
                'has_archive'         => $has_archive ,
                    )
                    ) ;
        }

        /*
         * Prepare Locations Post type arguments
         */

        public static function location_post_type_args() {

            return apply_filters( 'esf_locations_post_type_args' , array(
                'label'           => esc_html__( 'Locations' , ESF_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

        /*
         * Prepare Organizer Post type arguments
         */

        public static function organizer_post_type_args() {

            return apply_filters( 'esf_organizers_post_type_args' , array(
                'label'           => esc_html__( 'Organizers' , ESF_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

        /*
         * Flush the rewrite rules
         */

        public static function flush_rewrite_rules() {

            //after save register post type and taxonomies
            self::register_custom_post_types() ;
            self::register_custom_taxonomies() ;

            flush_rewrite_rules() ;
        }

    }

    ESF_Register_Post_Types::init() ;
}