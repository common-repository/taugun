<?php

/**
 * Locations Post Table
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( ! class_exists( 'ESF_Locations_Post_Table' ) ) {

    /**
     * ESF_Locations_Post_Table Class.
     * */
    class ESF_Locations_Post_Table extends WP_List_Table {

        /**
         * Total Count of Table
         * */
        private $total_items ;

        /**
         * Per page count
         * */
        private $perpage ;

        /**
         * Offset
         * */
        private $offset ;

        /**
         * Order BY
         * */
        private $orderby = 'ORDER BY ID DESC' ;

        /**
         * Post type
         * */
        private $post_type = ESF_Register_Post_Types::LOCATION_POSTTYPE ;

        /**
         * Base URL
         * */
        private $base_url ;

        /**
         * Current URL
         * */
        private $current_url ;

        /**
         * Plugin slug.
         */
        protected $plugin_slug = 'esf' ;

        /**
         * Prepare the table Data to display table based on pagination.
         * */
        public function prepare_items() {

            $this->base_url = add_query_arg( array( 'page' => 'locations' ) , esf_get_event_page_url() ) ;

            add_filter( sanitize_key( $this->plugin_slug . '_query_where' ) , array( $this , 'custom_search' ) , 10 , 1 ) ;

            $this->prepare_current_url() ;
            $this->process_bulk_action() ;
            $this->get_perpage_count() ;
            $this->get_current_pagenum() ;
            $this->get_current_page_items() ;
            $this->prepare_pagination_args() ;
            //display header columns
            $this->prepare_column_headers() ;
        }

        /**
         * get per page count
         * */
        private function get_perpage_count() {

            $this->perpage = 20 ;
        }

        /**
         * Prepare pagination
         * */
        private function prepare_pagination_args() {

            $this->set_pagination_args( array(
                'total_items' => $this->total_items ,
                'per_page'    => $this->perpage
            ) ) ;
        }

        /**
         * get current page number
         * */
        private function get_current_pagenum() {
            $this->offset = 20 * ($this->get_pagenum() - 1) ;
        }

        /**
         * Prepare header columns
         * */
        private function prepare_column_headers() {
            $columns               = $this->get_columns() ;
            $hidden                = $this->get_hidden_columns() ;
            $sortable              = $this->get_sortable_columns() ;
            $this->_column_headers = array( $columns , $hidden , $sortable ) ;
        }

        /**
         * Initialize the columns
         * */
        public function get_columns() {
            $columns = array(
                'cb'       => '<input type="checkbox" />' , //Render a checkbox instead of text
                'location' => esc_html__( 'Location' , ESF_LOCALE ) ,
                'info'     => esc_html__( 'Info' , ESF_LOCALE ) ,
                'actions'  => esc_html__( 'Actions' , ESF_LOCALE ) ,
                    ) ;

            return $columns ;
        }

        /**
         * Initialize the hidden columns
         * */
        public function get_hidden_columns() {
            return array() ;
        }

        /**
         * Initialize the bulk actions
         * */
        protected function get_bulk_actions() {
            $action             = array() ;
            $action[ 'delete' ] = esc_html__( 'Delete' , ESF_LOCALE ) ;

            return $action ;
        }

        /**
         * Display the list of views available on this table.
         * */
        public function get_views() {
            $args        = array() ;
            $status_link = array() ;

            $status_link_array = array(
                ''        => esc_html__( 'All' , ESF_LOCALE ) ,
                'publish' => esc_html__( 'Published' , ESF_LOCALE ) ,
                    ) ;

            foreach ( $status_link_array as $status_name => $status_label ) {
                $status_count = $this->get_total_item_for_status( $status_name ) ;

                if ( ! $status_count )
                    continue ;

                if ( $status_name )
                    $args[ 'status' ] = $status_name ;

                $label                       = $status_label . ' (' . $status_count . ')' ;
                $class                       = (isset( $_GET[ 'status' ] ) && sanitize_title( $_GET[ 'status' ] ) == $status_name ) ? 'current' : '' ;
                $class                       = ( ! isset( $_GET[ 'status' ] ) && '' == $status_name ) ? 'current' : $class ;
                $status_link[ $status_name ] = $this->get_edit_link( $args , $label , $class ) ;
            }

            return $status_link ;
        }

        /**
         * Edit link for status
         * */
        private function get_edit_link( $args , $label , $class = '' ) {
            $url        = add_query_arg( $args , $this->base_url ) ;
            $class_html = '' ;
            if ( ! empty( $class ) ) {
                $class_html = sprintf(
                        ' class="%s"' , esc_attr( $class )
                        ) ;
            }

            return sprintf(
                    '<a href="%s"%s>%s</a>' , esc_url( $url ) , $class_html , $label
                    ) ;
        }

        /**
         * get current url
         * */
        private function prepare_current_url() {
            //Build row actions
            if ( isset( $_GET[ 'status' ] ) )
                $args[ 'status' ] = sanitize_title( $_GET[ 'status' ] ) ;

            $pagenum         = $this->get_pagenum() ;
            $args[ 'paged' ] = $pagenum ;
            $url             = add_query_arg( $args , $this->base_url ) ;

            $this->current_url = $url ;
        }

        /**
         * bulk action functionality
         * */
        public function process_bulk_action() {

            $ids = isset( $_REQUEST[ 'id' ] ) ? esf_sanitize_text_field( $_REQUEST[ 'id' ] ) : array() ;
            $ids = ! is_array( $ids ) ? explode( ',' , $ids ) : $ids ;

            if ( ! esf_check_is_array( $ids ) )
                return ;

            if ( ! current_user_can( 'edit_posts' ) )
                wp_die( '<p class="esf_warning_notice">' . esc_html__( "You don't have permission to do this action" , ESF_LOCALE ) . '</p>' ) ;

            $action = $this->current_action() ;

            foreach ( $ids as $id ) {
                if ( 'delete' === $action ) {
                    esf_delete_location( $id ) ;
                }
            }

            wp_safe_redirect( $this->current_url ) ;
            exit() ;
        }

        /**
         * Prepare cb column data
         * */
        protected function column_cb( $item ) {
            return sprintf(
                    '<input type="checkbox" name="id[]" value="%s" />' , $item->get_id()
                    ) ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch ( $column_name ) {
                case 'location':
                    return $item->get_name() ;
                case 'info':
                    return $item->get_formatted_address() ;
                    break ;
                case 'actions':
                    $action_html = '' ;
                    $actions     = array() ;

                    $actions[ 'edit' ]   = esf_display_action( 'edit' , $item->get_id() , $this->current_url ) ;
                    $actions[ 'delete' ] = esf_display_action( 'delete' , $item->get_id() , $this->current_url ) ;

                    end( $actions ) ;

                    $last_key = key( $actions ) ;
                    foreach ( $actions as $key => $action ) {
                        $action_html .= $action ;

                        if ( $last_key == $key )
                            break ;

                        $action_html .= ' | ' ;
                    }

                    return $action_html ;
                    break ;
            }
        }

        /**
         * Get Current Page Items
         * */
        private function get_current_page_items() {
            global $wpdb ;

            $status = isset( $_GET[ 'status' ] ) ? ' IN("' . sanitize_title( $_GET[ 'status' ] ) . '")' : ' NOT IN("trash")' ;

            $where = " where post_type='" . $this->post_type . "' and post_status" . $status ;

            $where   = apply_filters( sanitize_key( $this->plugin_slug . '_query_where' ) , $where ) ;
            $limit   = apply_filters( sanitize_key( $this->plugin_slug . '_query_limit' ) , $this->perpage ) ;
            $offset  = apply_filters( sanitize_key( $this->plugin_slug . '_query_offset' ) , $this->offset ) ;
            $orderby = apply_filters( sanitize_key( $this->plugin_slug . '_query_orderby' ) , $this->orderby ) ;

            $count_items       = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " $where $orderby" ) ;
            $this->total_items = count( $count_items ) ;

            $prepare_query = $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " $where $orderby LIMIT %d,%d" , $offset , $limit ) ;
            $items         = $wpdb->get_results( $prepare_query , ARRAY_A ) ;

            $this->prepare_item_object( $items ) ;
        }

        /**
         * Prepare item Object
         * */
        private function prepare_item_object( $items ) {
            $prepare_items = array() ;
            if ( esf_check_is_array( $items ) ) {
                foreach ( $items as $item ) {
                    $prepare_items[] = new ESF_Location( absint( $item[ 'ID' ] ) ) ;
                }
            }

            $this->items = $prepare_items ;
        }

        /**
         * get total item for status
         * */
        private function get_total_item_for_status( $status = '' ) {
            global $wpdb ;
            $where  = "WHERE post_type='" . $this->post_type . "' and post_status" ;
            $status = ($status == '') ? "NOT IN('trash')" : "IN('" . $status . "')" ;

            $data = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " $where $status" , ARRAY_A ) ;

            return count( $data ) ;
        }

        /**
         * Search Functionality
         * */
        public function custom_search( $where ) {
            global $wpdb ;
            if ( isset( $_REQUEST[ 's' ] ) ) {
                $search_ids = array() ;
                $terms      = explode( ',' , esf_sanitize_text_field( $_REQUEST[ 's' ] ) ) ;

                foreach ( $terms as $term ) {
                    $term      = $wpdb->esc_like( $term ) ;
                    $meta_keys = array(
                        'esf_address_line1' ,
                        'esf_address_line2' ,
                        'esf_city' ,
                        'esf_state' ,
                        'esf_country' ,
                        'esf_post_code'
                            ) ;

                    $implode_meta_keys = implode( "','" , $meta_keys ) ;
                    if ( isset( $_GET[ 'post_status' ] ) && sanitize_title( $_GET[ 'post_status' ] ) != 'all' ) {
                        $post_status = sanitize_title( $_GET[ 'post_status' ] ) ;
                    } else {
                        $post_statuses = array( 'publish' ) ;
                        $post_status   = implode( "','" , $post_statuses ) ;
                    }

                    $search_ids = $wpdb->get_col( $wpdb->prepare(
                                    "SELECT DISTINCT ID FROM {$wpdb->posts} as p "
                                    . "INNER JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id "
                                    . "WHERE p.post_type=%s AND p.post_status IN ('$post_status') AND (("
                                    . "pm.meta_key IN ('$implode_meta_keys') "
                                    . "AND pm.meta_value LIKE %s) OR (p.ID LIKE %s) OR (p.post_title LIKE %s))" , $this->post_type , '%' . $term . '%' , '%' . $term . '%' , '%' . $term . '%' )
                            ) ;
                }

                $search_ids = array_filter( array_unique( array_map( 'absint' , $search_ids ) ) ) ;

                $search_ids = esf_check_is_array( $search_ids ) ? $search_ids : array( 0 ) ;

                $where .= " AND ({$wpdb->posts}.ID IN (" . implode( ',' , $search_ids ) . "))" ;
            }

            return $where ;
        }

    }

}
