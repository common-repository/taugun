<?php

/**
 * Custom Post Types Handler.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'ESF_Post_Types_Handler' ) ) {

    /**
     * ESF_Post_Types_Handler Class.
     */
    class ESF_Post_Types_Handler {

        private static $object ;

        /**
         * ESF_Post_Types_Handler Class initialization.
         */
        public static function init() {
            add_action( 'add_meta_boxes' , array( __CLASS__ , 'add_meta_box' ) ) ;
            add_action( 'save_post' , array( __CLASS__ , 'save' ) , 10 , 3 ) ;
            add_filter( 'post_updated_messages' , array( __CLASS__ , 'post_updated_messages' ) ) ;

            add_filter( 'post_row_actions' , array( __CLASS__ , 'handle_post_row_actions' ) , 10 , 2 ) ;
            add_filter( 'disable_months_dropdown' , array( __CLASS__ , 'remove_month_dropdown' ) , 10 , 2 ) ;
            add_action( 'views_edit-' . ESF_Register_Post_Types::EVENT_POSTTYPE , array( __CLASS__ , 'remove_views' ) ) ;
            add_filter( 'bulk_actions-edit-' . ESF_Register_Post_Types::EVENT_POSTTYPE , array( __CLASS__ , 'handle_bulk_actions' ) , 10 , 1 ) ;

            add_filter( 'manage_' . ESF_Register_Post_Types::EVENT_POSTTYPE . '_posts_columns' , array( __CLASS__ , 'define_columns' ) ) ;
            add_action( 'manage_' . ESF_Register_Post_Types::EVENT_POSTTYPE . '_posts_custom_column' , array( __CLASS__ , 'render_columns' ) , 10 , 2 ) ;
        }

        /*
         * Handle Row Actions
         */

        public static function handle_post_row_actions( $actions , $post ) {

            if ( $post->post_type != ESF_Register_Post_Types::EVENT_POSTTYPE )
                return $actions ;

            unset( $actions[ 'inline hide-if-no-js' ] ) ; // Remove Quick Edit

            return $actions ;
        }

        /*
         * Remove views
         */

        public static function remove_views( $views ) {

            unset( $views[ 'mine' ] ) ;

            return $views ;
        }

        /**
         * Remove month dropdown 
         */
        public static function remove_month_dropdown( $bool , $post_type ) {
            return $post_type == ESF_Register_Post_Types::EVENT_POSTTYPE ? true : $bool ;
        }

        /*
         * Handle Bulk Actions
         */

        public static function handle_bulk_actions( $actions ) {
            global $post ;
            if ( $post->post_type != ESF_Register_Post_Types::EVENT_POSTTYPE )
                return $actions ;

            unset( $actions[ 'edit' ] ) ; // Remove Edit

            return $actions ;
        }

        /**
         * Define custom columns
         */
        public static function define_columns( $columns ) {

            if ( ! esf_check_is_array( $columns ) ) {
                $columns = array() ;
            }

            $extra_columns = array(
                'esf_start_date' => esc_html__( 'Start Date' , ESF_LOCALE ) ,
                'esf_end_date'   => esc_html__( 'End Date' , ESF_LOCALE ) ,
                'esf_categories' => esc_html__( 'Categories' , ESF_LOCALE ) ,
                'esf_tags'       => esc_html__( 'Tags' , ESF_LOCALE ) ,
                    ) ;

            $columns           = esf_array_insert_after( $columns , 'title' , $extra_columns ) ;
            $columns[ 'date' ] = esc_html__( 'Date' , ESF_LOCALE ) ;

            return $columns ;
        }

        /*
         * Remove views
         */

        public static function prepare_row_data( $postid ) {

            if ( empty( self::$object ) || self::$object->get_id() != $postid ) {
                self::$object = esf_get_event( $postid ) ;
            }

            return self::$object ;
        }

        /**
         * Render each column
         */
        public static function render_columns( $column , $postid ) {

            self::prepare_row_data( $postid ) ;
            $function = 'render_' . $column . '_cloumn' ;

            if ( method_exists( __CLASS__ , $function ) ) {
                self::$function() ;
            }
        }

        /**
         * Render Start date column
         */
        public static function render_esf_start_date_cloumn() {
            echo ESF_Date_Time::get_date_object_format_datetime( self::$object->get_start_date() ) ;
        }

        /**
         * Render End date column
         */
        public static function render_esf_end_date_cloumn() {
            echo ESF_Date_Time::get_date_object_format_datetime( self::$object->get_end_date() ) ;
        }

        /**
         * Render Categories column
         */
        public static function render_esf_categories_cloumn() {

            esf_get_the_terms_html( self::$object->get_category() , false , true ) ;
        }

        /**
         * Render Tags column
         */
        public static function render_esf_tags_cloumn() {
            esf_get_the_terms_html( self::$object->get_tag() , false , true ) ;
        }

        /**
         * Customizing the messages
         */
        public static function post_updated_messages( $messages ) {
            global $post_ID , $post ;

            $preview_url = get_preview_post_link( $post ) ;
            $permalink   = get_permalink( $post_ID ) ;

            if ( ! $permalink ) {
                $permalink = '' ;
            }
            // View event link.
            $view_event_link_html = sprintf(
                    ' <a href="%1$s">%2$s</a>' , esc_url( $permalink ) , esc_html__( 'View event' , ESF_LOCALE )
                    ) ;

            // View event link.
            $view_event_link_html = sprintf(
                    ' <a href="%1$s">%2$s</a>' , esc_url( $permalink ) , esc_html__( 'View event' , ESF_LOCALE )
                    ) ;

            // Preview event link.
            $preview_event_link_html = sprintf(
                    ' <a target="_blank" href="%1$s">%2$s</a>' , esc_url( $preview_url ) , esc_html__( 'Preview event' , ESF_LOCALE )
                    ) ;

            // Scheduled event preview link.
            $scheduled_event_link_html = sprintf(
                    ' <a target="_blank" href="%1$s">%2$s</a>' , esc_url( $permalink ) , esc_html__( 'Preview event' , ESF_LOCALE )
                    ) ;

            $scheduled_date = date_i18n( __( 'M j, Y @ H:i' ) , strtotime( $post->post_date ) ) ;

            $messages[ ESF_Register_Post_Types::EVENT_POSTTYPE ] = array(
                0  => '' , // Unused. Messages start at index 1.
                1  => esc_html__( 'Event updated.' , ESF_LOCALE ) . $view_event_link_html ,
                2  => esc_html__( 'Custom field updated.' , ESF_LOCALE ) ,
                3  => esc_html__( 'Custom field deleted.' , ESF_LOCALE ) ,
                4  => esc_html__( 'Event updated.' , ESF_LOCALE ) ,
                6  => esc_html__( 'Event published.' , ESF_LOCALE ) . $view_event_link_html ,
                7  => esc_html__( 'Event saved.' , ESF_LOCALE ) ,
                8  => esc_html__( 'Event submitted.' , ESF_LOCALE ) . $preview_event_link_html ,
                9  => sprintf( esc_html__( 'Event scheduled for: %s.' , ESF_LOCALE ) , '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_event_link_html ,
                10 => esc_html__( 'Event draft updated.' , ESF_LOCALE ) . $preview_event_link_html ,
                    ) ;

            return $messages ;
        }

        /*
         * Add Custom meta boxes.
         */

        public static function add_meta_box() {
            add_meta_box( 'esf-settings' , esc_html__( 'Settings' , ESF_LOCALE ) , array( __CLASS__ , 'events_setting_metabox' ) , ESF_Register_Post_Types::EVENT_POSTTYPE , 'normal' , 'high' ) ;

            //Custom Meta box for Event post type
            do_action( 'esf_event_meta_boxes' , ESF_Register_Post_Types::EVENT_POSTTYPE ) ;
        }

        /*
         * Display Events Settings Meta box
         */

        public static function events_setting_metabox( $post ) {
            if ( ! isset( $post->ID ) )
                return ;

            $event = esf_get_event( $post->ID ) ;

            include_once ESF_PLUGIN_PATH . '/inc/admin/menu/views/edit-post/events.php' ;
        }

        /*
         * Save post
         */

        public static function save( $post_id , $post ) {

            // If this is an autosave, our form has not been submitted, so we don't want to do anything.
            if ( empty( $post_id ) || empty( $post ) )
                return ;

            // Check the nonce
            if ( ! isset( $_POST[ 'esf_events_nonce' ] ) || empty( $_POST[ 'esf_events_nonce' ] ) || ! wp_verify_nonce( esf_sanitize_text_field( $_POST[ 'esf_events_nonce' ] ) , 'esf_save_event_settings' ) )
                return ;

            // retrun if post type is not event
            if ( ($post->post_type != ESF_Register_Post_Types::EVENT_POSTTYPE ) )
                return ;

            // Dont' save meta boxes for revisions or autosaves
            if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post_id ) ) || is_int( wp_is_post_autosave( $post_id ) ) )
                return ;

            // Check user has permission to edit
            if ( ! current_user_can( 'edit_post' , $post_id ) )
                return ;

            //Prevent to update post multi time.
            remove_action( 'save_post' , array( 'ESF_Post_Types_Handler' , 'save' ) ) ;

            $start_date       = '' ;
            $end_date         = '' ;
            $event_start_date = '' ;
            $event_end_date   = '' ;
            $all_day          = isset( $_POST[ 'esf_all_day' ] ) ? 'yes' : 'no' ;

            if ( isset( $_POST[ 'esf_start_date' ] ) && ! empty( $_POST[ 'esf_start_date' ] ) ) {
                $start_time     = ($all_day == 'yes') ? '00:00:00' : esf_sanitize_text_field( $_POST[ 'esf_start_time' ] ) ;
                $start_datetime = esf_sanitize_text_field( $_POST[ 'esf_start_date' ] . ' ' . $start_time ) ;

                $start_date       = ESF_Date_Time::get_mysql_date_time_format( $start_datetime , esf_sanitize_text_field( $_POST[ 'esf_timezone' ] ) , true ) ;
                $event_start_date = ESF_Date_Time::get_mysql_date_time_format( $start_datetime , esf_sanitize_text_field( $_POST[ 'esf_timezone' ] ) ) ;
            }

            if ( isset( $_POST[ 'esf_end_date' ] ) && ! empty( $_POST[ 'esf_end_date' ] ) ) {
                $end_time       = ($all_day == 'yes') ? '23:59:59' : esf_sanitize_text_field( $_POST[ 'esf_end_time' ] ) ;
                $end_datetime   = esf_sanitize_text_field( $_POST[ 'esf_end_date' ] . ' ' . $end_time ) ;
                $end_date       = ESF_Date_Time::get_mysql_date_time_format( $end_datetime , esf_sanitize_text_field( $_POST[ 'esf_timezone' ] ) , true ) ;
                $event_end_date = ESF_Date_Time::get_mysql_date_time_format( $end_datetime , esf_sanitize_text_field( $_POST[ 'esf_timezone' ] ) ) ;
            }

            $meta_args = array(
                'esf_start_date'       => $start_date ,
                'esf_end_date'         => $end_date ,
                'esf_event_start_date' => $event_start_date ,
                'esf_event_end_date'   => $event_end_date ,
                'esf_price'            => isset( $_POST[ 'esf_price' ] ) ? esf_sanitize_text_field( $_POST[ 'esf_price' ] ) : '' ,
                'esf_website'          => isset( $_POST[ 'esf_website' ] ) ? esf_sanitize_text_field( $_POST[ 'esf_website' ] ) : '' ,
                'esf_all_day'          => $all_day ,
                'esf_timezone'         => isset( $_POST[ 'esf_timezone' ] ) ? esf_sanitize_text_field( $_POST[ 'esf_timezone' ] ) : '' ,
                'esf_location_id'      => isset( $_POST[ 'esf_location' ][ 0 ] ) ? esf_sanitize_text_field( $_POST[ 'esf_location' ][ 0 ] ) : '' ,
                'esf_organizer_id'     => isset( $_POST[ 'esf_organizer' ][ 0 ] ) ? esf_sanitize_text_field( $_POST[ 'esf_organizer' ][ 0 ] ) : '' ,
                    ) ;

            // Update Event
            esf_update_event( $post_id , $meta_args ) ;

            do_action( 'esf_after_save_event_settings' , $post_id ) ;
        }

    }

    ESF_Post_Types_Handler::init() ;
}