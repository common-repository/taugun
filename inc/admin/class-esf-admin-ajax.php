<?php
/*
 * Admin Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'ESF_Admin_Ajax' ) ) {

    /**
     * ESF_Admin_Ajax Class
     */
    class ESF_Admin_Ajax {

        /**
         * ESF_Admin_Ajax Class initialization
         */
        public static function init() {

            $actions = array(
                'location_search'  => false ,
                'organizer_search' => false ,
                'toggle_modules'   => false ,
                'events_view'      => false ,
                    ) ;

            foreach ( $actions as $action => $nopriv ) {
                add_action( 'wp_ajax_esf_' . $action , array( __CLASS__ , $action ) ) ;

                if ( $nopriv )
                    add_action( 'wp_ajax_nopriv_esf_' . $action , array( __CLASS__ , $action ) ) ;
            }
        }

        /**
         * Search Locations
         */
        public static function location_search() {
            check_ajax_referer( 'esf-search-nonce' , 'esf_security' ) ;

            try {
                $term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

                if ( empty( $term ) )
                    throw new exception( esc_html__( 'Invalid Request' , ESF_LOCALE ) ) ;

                global $wpdb ;

                $listoflocations = array() ;

                $limit = (strlen( $term ) > 3) ? '' : 20 ;

                if ( $limit )
                    $limit_query = $wpdb->prepare( ' LIMIT %d' , $limit ) ;

                $query = $wpdb->prepare(
                        "SELECT * FROM {$wpdb->posts} "
                        . "WHERE post_type=%s AND post_status='publish' "
                        . "AND ((post_title LIKE %s) OR (ID LIKE %s)){$limit_query}"
                        , ESF_Register_Post_Types::LOCATION_POSTTYPE , '%' . $term . '%' , '%' . $term . '%'
                        ) ;

                $search_results = $wpdb->get_results( $query ) ;

                if ( esf_check_is_array( $search_results ) ) {
                    foreach ( $search_results as $location ) {
                        if ( ! is_object( $location ) )
                            continue ;

                        $listoflocations[ $location->ID ] = esc_html( $location->post_title . '(#' . absint( $location->ID ) . ')' ) ;
                    }
                }

                wp_send_json( $listoflocations ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * Search Organizers
         */
        public static function organizer_search() {
            check_ajax_referer( 'esf-search-nonce' , 'esf_security' ) ;

            try {

                $term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

                if ( empty( $term ) )
                    throw new exception( esc_html__( 'Invalid Request' , ESF_LOCALE ) ) ;

                global $wpdb ;

                $listoforganizers = array() ;
                $limit            = (strlen( $term ) > 3) ? '' : 20 ;

                if ( $limit )
                    $limit_query = $wpdb->prepare( ' LIMIT %d' , $limit ) ;

                $query = $wpdb->prepare(
                        "SELECT * FROM {$wpdb->posts} "
                        . "WHERE post_type=%s AND post_status='publish' "
                        . "AND ((post_title LIKE %s) OR (ID LIKE %s)){$limit_query}"
                        , ESF_Register_Post_Types::ORGANIZER_POSTTYPE , '%' . $term . '%' , '%' . $term . '%'
                        ) ;

                $search_results = $wpdb->get_results( $query ) ;

                if ( esf_check_is_array( $search_results ) ) {
                    foreach ( $search_results as $organizer ) {
                        if ( ! is_object( $organizer ) )
                            continue ;

                        $listoforganizers[ $organizer->ID ] = esc_html( $organizer->post_title . '(#' . absint( $organizer->ID ) . ')' ) ;
                    }
                }

                wp_send_json( $listoforganizers ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

        /**
         * Toggle Modules
         */
        public static function toggle_modules() {
            check_ajax_referer( 'esf-module-nonce' , 'esf_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'module_name' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , ESF_LOCALE ) ) ;

                $module_object = ESF_Module_Instances::get_module_by_id( sanitize_key( $_REQUEST[ 'module_name' ] ) ) ;
                if ( is_object( $module_object ) ) {
                    $value = (sanitize_key( $_REQUEST[ 'enabled' ] ) == 'true') ? 'yes' : 'no' ;
                    $module_object->update_option( 'enabled' , $value ) ;
                }

                wp_send_json_success() ;
            } catch ( Exception $ex ) {
                wp_send_json_success( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Event View
         */
        public static function events_view() {
            check_ajax_referer( 'esf-events-nonce' , 'esf_security' ) ;

            ob_start() ;
            try {
                if ( ! isset( $_REQUEST[ 'current_date' ] , $_REQUEST[ 'current_month' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , ESF_LOCALE ) ) ;


                $current_date  = esf_sanitize_text_field( $_REQUEST[ 'current_date' ] ) ;
                $current_month = esf_sanitize_text_field( $_REQUEST[ 'current_month' ] ) ;

                if ( empty( $_REQUEST[ 'event_ids' ] ) ) {
                    ?>
                    <div class="esf_month_event_list_view">
                        <div class="esf_month_event_date">
                            <h2><?php echo esc_html( $current_date ) ; ?></h2>
                            <h4><?php echo esc_html( $current_month ) ; ?></h4>
                        </div>
                        <div class="esf_no_events">
                            <h3><?php esc_html_e( 'No Events found' , ESF_LOCALE ) ; ?></h3>
                        </div>
                    </div>
                    <?php
                } else {
                    $event_ids = esf_sanitize_text_field( $_REQUEST[ 'event_ids' ] ) ;

                    foreach ( $event_ids as $event_id ) {
                        $event_obj = new ESF_Event( $event_id ) ;
                        ?>
                        <div class="esf_month_event_list_view">
                            <div class="esf_month_event_date">
                                <h2><?php echo esc_html( $current_date ) ; ?></h2>
                                <h4><?php echo esc_html( $current_month ) ; ?></h4>
                            </div>
                            <div class="esf_month_event_name">
                                <h3><a href="<?php echo esc_url( $event_obj->get_permalink() ) ; ?>"><?php echo esc_html( $event_obj->get_name() ) ; ?></a></h3>
                                <p><?php echo esc_html( $event_obj->display_datetime( 'start-end-datetime' ) ) ; ?></p>
                            </div>
                            <div class="esf_month_event_image">
                                <img  src="<?php echo esc_url( $event_obj->get_formatted_featured_image() ) ; ?>" />
                            </div>
                        </div>
                        <?php
                    }
                }

                $events = ob_get_clean() ;
                ob_end_clean() ;

                wp_send_json_success( array( 'content' => $events ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
            }
        }

    }

    ESF_Admin_Ajax::init() ;
}