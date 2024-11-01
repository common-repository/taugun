<?php

/**
 * Organizers Page
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

/**
 * ESF_Organizers_Page.
 */
class ESF_Organizers_Page {
    /*
     * Plugin Slug
     */

    private static $plugin_slug = 'esf' ;

    /**
     * Output the Organizers
     */
    public static function output() {

        $section = isset( $_GET[ 'section' ] ) ? sanitize_key( wp_unslash( ( $_GET[ 'section' ] ) ) ) : '' ;

        switch ( $section ) {
            case 'new' :
                self::display_new_page() ;
                break ;
            case 'edit' :
                self::display_edit_page() ;
                break ;
            default:
                self::display_table() ;
                break ;
        }
    }

    /*
     * display a new organizer creation page.
     */

    public static function display_new_page() {

        $name            = '' ;
        $description     = '' ;
        $email           = '' ;
        $phone           = '' ;
        $website         = '' ;
        $additional_info = '' ;
        $image           = '' ;

        if ( isset( $_POST[ 'organizer' ] ) && esf_check_is_array( $_POST[ 'organizer' ] ) ) {
            $data = esf_sanitize_text_field( $_POST[ 'organizer' ] ) ;

            if ( isset( $data[ 'esf_name' ] ) ) {
                $name = esf_sanitize_text_field( $data[ 'esf_name' ] ) ;
            }

            if ( isset( $data[ 'esf_description' ] ) ) {
                $description = esf_sanitize_text_area( $data[ 'esf_description' ] ) ;
            }

            if ( isset( $data[ 'esf_email' ] ) && ! filter_var( $data[ 'esf_email' ] , FILTER_VALIDATE_EMAIL ) ) {
                $email = esf_sanitize_text_field( $data[ 'esf_email' ] ) ;
            }

            if ( isset( $data[ 'esf_phone' ] ) && preg_match( '/^[0-9]{10}+$/' , $data[ 'esf_phone' ] ) ) {
                $phone = esf_sanitize_text_field( $data[ 'esf_phone' ] ) ;
            }

            if ( isset( $data[ 'esf_website' ] ) && ! filter_var( $data[ 'esf_website' ] , FILTER_VALIDATE_URL ) === FALSE ) {
                $website = esf_sanitize_text_field( $data[ 'esf_website' ] ) ;
            }

            if ( isset( $data[ 'esf_additional_info' ] ) ) {
                $additional_info = esf_sanitize_text_area( $data[ 'esf_additional_info' ] ) ;
            }

            if ( isset( $data[ 'esf_image' ] ) && ! filter_var( $data[ 'esf_image' ] , FILTER_VALIDATE_URL ) === FALSE ) {
                $image = $data[ 'esf_image' ] ;
            }
        }

        include_once( ESF_PLUGIN_PATH . '/inc/admin/menu/views/organizer-new.php' ) ;
    }

    /**
     * Output the edit organizers page
     */
    public static function display_edit_page() {

        if ( ! isset( $_GET[ 'id' ] ) )
            return ;

        $organizer_id     = absint( $_GET[ 'id' ] ) ;
        $organizer_object = new ESF_Organizer( $organizer_id ) ;

        include_once( ESF_PLUGIN_PATH . '/inc/admin/menu/views/organizer-edit.php' ) ;
    }

    /**
     * Output the Organizers table
     */
    public static function display_table() {
        if ( ! class_exists( 'ESF_Organizers_Post_Table' ) ) {
            require_once( ESF_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-esf-organizers-table.php' ) ;
        }

        $post_table = new ESF_Organizers_Post_Table() ;
        $post_table->prepare_items() ;

        $new_section_url = add_query_arg( array( 'page' => 'organizers' , 'section' => 'new' ) , esf_get_event_page_url() ) ;
        echo '<form method="POST" class="esf_form" action="">' ;
        echo '<div class="' . self::$plugin_slug . '_table_wrap">' ;
        echo '<h2 class="wp-heading-inline">' . esc_html__( 'Organizers' , ESF_LOCALE ) . '</h2>' ;
        echo '<a class="page-title-action ' . self::$plugin_slug . '_add_btn" href="' . esc_url( $new_section_url ) . '">' . esc_html__( 'Add Organizer' , ESF_LOCALE ) . '</a>' ;

        if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
            /* translators: %s: search keywords */
            printf( ' <span class="subtitle">' . esc_html__( 'Search results for &#8220;%s&#8221;' , ESF_LOCALE ) . '</span>' , $_REQUEST[ 's' ] ) ;
        }

        $post_table->views() ;
        $post_table->search_box( esc_html__( 'Search Organizers' , ESF_LOCALE ) , self::$plugin_slug . '_search' ) ;
        $post_table->display() ;
        echo '</div></form>' ;
    }

    /**
     * Save settings.
     */
    public static function save() {

        if ( isset( $_POST[ 'add_organizer' ] ) && ! empty( $_POST[ 'add_organizer' ] ) ) {
            self::add_organizer() ;
        } elseif ( isset( $_POST[ 'edit_organizer' ] ) && ! empty( $_POST[ 'edit_organizer' ] ) ) {
            self::update_organizer() ;
        }
    }

    /*
     * Create a new organizer
     */

    public static function add_organizer() {

        check_admin_referer( self::$plugin_slug . '_add_organizer' , '_' . self::$plugin_slug . '_nonce' ) ;

        try {

            // Check user permission
            if ( ! current_user_can( 'publish_posts' ) ) {
                throw new Exception( esc_html__( "You don't have permission to do this action" , ESF_LOCALE ) ) ;
            }

            // Check location post values are there in POST.
            if ( ! isset( $_POST[ 'organizer' ] ) || ! esf_check_is_array( $_POST[ 'organizer' ] ) ) {
                throw new Exception( esc_html__( 'Invalid POST Values' , ESF_LOCALE ) ) ;
            }

            $meta_data = esf_sanitize_text_field( $_POST[ 'organizer' ] ) ;

            if ( $meta_data[ 'esf_name' ] == '' ) {
                throw new Exception( esc_html__( 'Name cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_email' ] ) && filter_var( $data[ 'esf_email' ] , FILTER_VALIDATE_EMAIL ) ) {
                throw new Exception( esc_html__( 'Please enter valid a Email' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_phone' ] ) && ! preg_match( '/^[0-9]{10}+$/' , $data[ 'esf_phone' ] ) ) {
                throw new Exception( esc_html__( 'Please enter valid a Phone Number' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_website' ] ) && filter_var( $data[ 'esf_website' ] , FILTER_VALIDATE_URL ) === FALSE ) {
                throw new Exception( esc_html__( 'Please enter valid a URL' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_image' ] ) && filter_var( $data[ 'esf_image' ] , FILTER_VALIDATE_URL ) === FALSE ) {
                throw new Exception( esc_html__( 'Please enter valid a URL' , ESF_LOCALE ) ) ;
            }

            $post_args = array(
                'post_title'   => esf_sanitize_text_field( $meta_data[ 'esf_name' ] ) ,
                'post_content' => esf_sanitize_text_area( $meta_data[ 'esf_description' ] )
                    ) ;

            $meta_args = array(
                'esf_email'           => esf_sanitize_text_field( $meta_data[ 'esf_email' ] ) ,
                'esf_website'         => esf_sanitize_text_field( $meta_data[ 'esf_website' ] ) ,
                'esf_phone'           => esf_sanitize_text_field( $meta_data[ 'esf_phone' ] ) ,
                'esf_image'           => esc_url_raw( $meta_data[ 'esf_image' ] ) ,
                'esf_additional_info' => esf_sanitize_text_area( $meta_data[ 'esf_additional_info' ] ) ,
                'esf_date'            => current_time( 'mysql' , true )
                    ) ;

            $organizer_id = esf_create_new_organizer( $meta_args , $post_args ) ;

            ESF_Settings::add_message( esc_html__( 'Organizer created successfully.' , ESF_LOCALE ) ) ;

            unset( $_POST[ 'organizer' ] ) ;
        } catch ( Exception $ex ) {
            ESF_Settings::add_error( $ex->getMessage() ) ;
        }
    }

    /*
     * update organizer
     */

    public static function update_organizer() {
        check_admin_referer( self::$plugin_slug . '_edit_organizer' , '_' . self::$plugin_slug . '_nonce' ) ;

        try {

            // Check user permission
            if ( ! current_user_can( 'publish_posts' ) ) {
                throw new Exception( esc_html__( "You don't have permission to do this action" , ESF_LOCALE ) ) ;
            }

            // Check location post values are there in POST.
            if ( ! isset( $_POST[ 'organizer' ] ) || ! esf_check_is_array( $_POST[ 'organizer' ] ) ) {
                throw new Exception( esc_html__( 'Invalid POST Values' , ESF_LOCALE ) ) ;
            }

            $meta_data = esf_sanitize_text_field( $_POST[ 'organizer' ] ) ;

            if ( empty( $meta_data[ 'id' ] ) || $meta_data[ 'id' ] != absint( $_GET[ 'id' ] ) ) {
                throw new Exception( esc_html__( 'Location ID cannot be modified' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_name' ] == '' ) {
                throw new Exception( esc_html__( 'Name cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_email' ] ) && filter_var( $data[ 'esf_email' ] , FILTER_VALIDATE_EMAIL ) ) {
                throw new Exception( esc_html__( 'Please enter valid a Email' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_phone' ] ) && ! preg_match( '/^[0-9]{10}+$/' , $data[ 'esf_phone' ] ) ) {
                throw new Exception( esc_html__( 'Please enter valid a Phone Number' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_website' ] ) && filter_var( $data[ 'esf_website' ] , FILTER_VALIDATE_URL ) === FALSE ) {
                throw new Exception( esc_html__( 'Please enter valid a URL' , ESF_LOCALE ) ) ;
            }

            if ( isset( $data[ 'esf_image' ] ) && filter_var( $data[ 'esf_image' ] , FILTER_VALIDATE_URL ) === FALSE ) {
                throw new Exception( esc_html__( 'Please enter valid a URL' , ESF_LOCALE ) ) ;
            }

            $post_args = array(
                'post_title'   => esf_sanitize_text_field( $meta_data[ 'esf_name' ] ) ,
                'post_content' => esf_sanitize_text_area( $meta_data[ 'esf_description' ] )
                    ) ;

            $meta_args = array(
                'esf_email'           => esf_sanitize_text_field( $meta_data[ 'esf_email' ] ) ,
                'esf_website'         => esf_sanitize_text_field( $meta_data[ 'esf_website' ] ) ,
                'esf_phone'           => esf_sanitize_text_field( $meta_data[ 'esf_phone' ] ) ,
                'esf_image'           => esc_url_raw( $meta_data[ 'esf_image' ] ) ,
                'esf_additional_info' => esf_sanitize_text_area( $meta_data[ 'esf_additional_info' ] ) ,
                'esf_date'            => current_time( 'mysql' , true )
                    ) ;

            //update organizer
            esf_update_organizer( absint( $meta_data[ 'id' ] ) , $meta_data , $post_args ) ;

            unset( $_POST[ 'organizer' ] ) ;

            ESF_Settings::add_message( esc_html__( 'Organizer has been updated successfully.' , ESF_LOCALE ) ) ;
        } catch ( Exception $ex ) {
            ESF_Settings::add_error( $ex->getMessage() ) ;
        }
    }

}
