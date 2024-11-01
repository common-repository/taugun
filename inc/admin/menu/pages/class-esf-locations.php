<?php

/**
 * Locations Page
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

/**
 * ESF_Locations_Page.
 */
class ESF_Locations_Page {
    /*
     * Plugin Slug
     */

    private static $plugin_slug = 'esf' ;

    /**
     * Output the Locations
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
     * display a new location creation page.
     */

    public static function display_new_page() {
        $name          = '' ;
        $description   = '' ;
        $address_line1 = '' ;
        $address_line2 = '' ;
        $city          = '' ;
        $state         = '' ;
        $country       = '' ;
        $post_code     = '' ;
        $image         = '' ;

        if ( isset( $_POST[ 'location' ] ) && esf_check_is_array( $_POST[ 'location' ] ) ) {
            $data = esf_sanitize_text_field( $_POST[ 'location' ] ) ;

            if ( isset( $data[ 'esf_name' ] ) ) {
                $name = esf_sanitize_text_field( $data[ 'esf_name' ] ) ;
            }

            if ( isset( $data[ 'esf_description' ] ) ) {
                $description = esf_sanitize_text_area( $data[ 'esf_description' ] ) ;
            }

            if ( isset( $data[ 'esf_address_line1' ] ) ) {
                $address_line1 = esf_sanitize_text_field( $data[ 'esf_address_line1' ] ) ;
            }

            if ( isset( $data[ 'esf_address_line2' ] ) ) {
                $address_line2 = esf_sanitize_text_field( $data[ 'esf_address_line2' ] ) ;
            }

            if ( isset( $data[ 'esf_city' ] ) ) {
                $city = esf_sanitize_text_field( $data[ 'esf_address_line2' ] ) ;
            }
            if ( isset( $data[ 'esf_country' ] ) ) {
                $country = esf_sanitize_text_field( $data[ 'esf_country' ] ) ;
            }

            if ( isset( $data[ 'esf_state' ] ) ) {
                $state = esf_sanitize_text_field( $data[ 'esf_state' ] ) ;
            }

            if ( isset( $data[ 'esf_post_code' ] ) ) {
                $post_code = esf_sanitize_text_field( $data[ 'esf_post_code' ] ) ;
            }

            if ( isset( $data[ 'esf_image' ] ) && ! filter_var( $data[ 'esf_image' ] , FILTER_VALIDATE_URL ) === FALSE ) {
                $image = $data[ 'esf_image' ] ;
            }
        }

        include_once( ESF_PLUGIN_PATH . '/inc/admin/menu/views/location-new.php' ) ;
    }

    /**
     * Output the edit locations page
     */
    public static function display_edit_page() {

        if ( ! isset( $_GET[ 'id' ] ) )
            return ;

        $location_id     = absint( $_GET[ 'id' ] ) ;
        $location_object = new ESF_Location( $location_id ) ;

        include_once( ESF_PLUGIN_PATH . '/inc/admin/menu/views/location-edit.php' ) ;
    }

    /**
     * Output the Locations table
     */
    public static function display_table() {
        if ( ! class_exists( 'ESF_Locations_Post_Table' ) ) {
            require_once( ESF_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-esf-locations-table.php' ) ;
        }

        $post_table = new ESF_Locations_Post_Table() ;
        $post_table->prepare_items() ;

        $new_section_url = add_query_arg( array( 'page' => 'locations' , 'section' => 'new' ) , esf_get_event_page_url() ) ;
        echo '<form method="POST" class="esf_form" action="">' ;
        echo '<div class="' . self::$plugin_slug . '_table_wrap">' ;
        echo '<h2 class="wp-heading-inline">' . esc_html__( 'Locations' , ESF_LOCALE ) . '</h2>' ;
        echo '<a class="page-title-action ' . self::$plugin_slug . '_add_btn" href="' . esc_url( $new_section_url ) . '">' . esc_html__( 'Add Location' , ESF_LOCALE ) . '</a>' ;

        if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
            /* translators: %s: search keywords */
            printf( ' <span class="subtitle">' . esc_html__( 'Search results for &#8220;%s&#8221;' , ESF_LOCALE ) . '</span>' , $_REQUEST[ 's' ] ) ;
        }

        $post_table->views() ;
        $post_table->search_box( esc_html__( 'Search Locations' , ESF_LOCALE ) , self::$plugin_slug . '_search' ) ;
        $post_table->display() ;
        echo '</div></form>' ;
    }

    /**
     * Save settings.
     */
    public static function save() {

        if ( isset( $_POST[ 'add_location' ] ) && ! empty( $_POST[ 'add_location' ] ) ) {
            self::create_new_location() ;
        } elseif ( isset( $_POST[ 'edit_location' ] ) && ! empty( $_POST[ 'edit_location' ] ) ) {
            self::update_location() ;
        }
    }

    /*
     * Create a new location
     */

    public static function create_new_location() {

        check_admin_referer( self::$plugin_slug . '_add_location' , '_' . self::$plugin_slug . '_nonce' ) ;

        try {

            // Check user permission
            if ( ! current_user_can( 'publish_posts' ) ) {
                throw new Exception( esc_html__( "You don't have permission to do this action" , ESF_LOCALE ) ) ;
            }

            // Check location post values are there in POST.
            if ( ! isset( $_POST[ 'location' ] ) || ! esf_check_is_array( $_POST[ 'location' ] ) ) {
                throw new Exception( esc_html__( 'Invalid POST Values' , ESF_LOCALE ) ) ;
            }

            $meta_data = esf_sanitize_text_field( $_POST[ 'location' ] ) ;

            if ( $meta_data[ 'esf_name' ] == '' ) {
                throw new Exception( esc_html__( 'Name cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_address_line1' ] == '' ) {
                throw new Exception( esc_html__( 'Address Line 1 cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_city' ] == '' ) {
                throw new Exception( esc_html__( 'City cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_state' ] == '' ) {
                throw new Exception( esc_html__( 'State cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_country' ] == '' ) {
                throw new Exception( esc_html__( 'Country cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_post_code' ] == '' ) {
                throw new Exception( esc_html__( 'ZIP Code cannot be empty' , ESF_LOCALE ) ) ;
            }

            $post_args = array(
                'post_title'   => esf_sanitize_text_field( $meta_data[ 'esf_name' ] ) ,
                'post_content' => esf_sanitize_text_area( $meta_data[ 'esf_description' ] )
                    ) ;

            $meta_args = array(
                'esf_address_line1' => esf_sanitize_text_field( $meta_data[ 'esf_address_line1' ] ) ,
                'esf_address_line2' => esf_sanitize_text_field( $meta_data[ 'esf_address_line2' ] ) ,
                'esf_city'          => esf_sanitize_text_field( $meta_data[ 'esf_city' ] ) ,
                'esf_state'         => esf_sanitize_text_field( $meta_data[ 'esf_state' ] ) ,
                'esf_country'       => esf_sanitize_text_field( $meta_data[ 'esf_country' ] ) ,
                'esf_post_code'     => esf_sanitize_text_field( $meta_data[ 'esf_post_code' ] ) ,
                'esf_image'         => esc_url_raw( $meta_data[ 'esf_image' ] ) ,
                'esf_date'          => current_time( 'mysql' , true )
                    ) ;

            $location_id = esf_create_new_location( $meta_args , $post_args ) ;

            ESF_Settings::add_message( esc_html__( 'Location created successfully.' , ESF_LOCALE ) ) ;

            unset( $_POST[ 'location' ] ) ;
        } catch ( Exception $ex ) {
            ESF_Settings::add_error( $ex->getMessage() ) ;
        }
    }

    /*
     * update location
     */

    public static function update_location() {
        check_admin_referer( self::$plugin_slug . '_edit_location' , '_' . self::$plugin_slug . '_nonce' ) ;

        try {
            // Check user permission
            if ( ! current_user_can( 'edit_posts' ) ) {
                throw new Exception( esc_html__( "You don't have permission to do this action" , ESF_LOCALE ) ) ;
            }

            // Check location post values are there in POST.
            if ( ! isset( $_POST[ 'location' ] ) || ! esf_check_is_array( $_POST[ 'location' ] ) ) {
                throw new Exception( esc_html__( 'Invalid POST Values' , ESF_LOCALE ) ) ;
            }

            $meta_data = esf_sanitize_text_field( $_POST[ 'location' ] ) ;

            if ( empty( $meta_data[ 'id' ] ) || $meta_data[ 'id' ] != absint( $_GET[ 'id' ] ) ) {
                throw new Exception( esc_html__( 'Cannot modify Location ID' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_name' ] == '' ) {
                throw new Exception( esc_html__( 'Name cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_address_line1' ] == '' ) {
                throw new Exception( esc_html__( 'Address Line 1 cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_city' ] == '' ) {
                throw new Exception( esc_html__( 'City cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_state' ] == '' ) {
                throw new Exception( esc_html__( 'State cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_country' ] == '' ) {
                throw new Exception( esc_html__( 'Country cannot be empty' , ESF_LOCALE ) ) ;
            }

            if ( $meta_data[ 'esf_post_code' ] == '' ) {
                throw new Exception( esc_html__( 'ZIP Code cannot be empty' , ESF_LOCALE ) ) ;
            }

            $post_args = array(
                'post_title'   => esf_sanitize_text_field( $meta_data[ 'esf_name' ] ) ,
                'post_content' => esf_sanitize_text_area( $meta_data[ 'esf_description' ] )
                    ) ;

            $meta_args = array(
                'esf_address_line1' => esf_sanitize_text_field( $meta_data[ 'esf_address_line1' ] ) ,
                'esf_address_line2' => esf_sanitize_text_field( $meta_data[ 'esf_address_line2' ] ) ,
                'esf_city'          => esf_sanitize_text_field( $meta_data[ 'esf_city' ] ) ,
                'esf_state'         => esf_sanitize_text_field( $meta_data[ 'esf_state' ] ) ,
                'esf_country'       => esf_sanitize_text_field( $meta_data[ 'esf_country' ] ) ,
                'esf_post_code'     => esf_sanitize_text_field( $meta_data[ 'esf_post_code' ] ) ,
                'esf_image'         => esc_url_raw( $meta_data[ 'esf_image' ] ) ,
                'esf_date'          => current_time( 'mysql' , true )
                    ) ;

            //update location
            esf_update_location( absint( $meta_data[ 'id' ] ) , $meta_args , $post_args ) ;

            unset( $_POST[ 'location' ] ) ;

            ESF_Settings::add_message( esc_html__( 'Location has been updated successfully.' , ESF_LOCALE ) ) ;
        } catch ( Exception $ex ) {
            ESF_Settings::add_error( $ex->getMessage() ) ;
        }
    }

}
