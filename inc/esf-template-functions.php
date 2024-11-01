<?php
/*
 * Template functions
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists( 'esf_get_the_terms_html' ) ) {

    function esf_get_the_terms_html( $terms , $frontend = true , $echo = false ) {
        $html = '' ;
        if ( esf_check_is_array( $terms ) && ! is_wp_error( $terms ) ) {

            foreach ( $terms as $term ) {
                if ( $frontend ) {
                    $url = get_term_link( $term ) ;
                } else {
                    $url = add_query_arg( array( 'taxonomy' => $term->taxonomy , 'tag_ID' => $term->term_id , 'post_type' => ESF_Register_Post_Types::EVENT_POSTTYPE ) , admin_url( 'term.php' ) ) ;
                }
                $html .= '<a href="' . esc_url( $url ) . '">' . esc_html( $term->name ) . '</a>, ' ;
            }

            $html = rtrim( $html , ', ' ) ;
        }

        if ( ! $echo )
            return $html ;

        echo $html ;
    }

}

if ( ! function_exists( 'esf_get_template' ) ) {

    function esf_get_template( $template_name , $args = array() , $template_path = '' , $default_path = '' ) {

        $cache_key = sanitize_key( implode( '-' , array( 'template' , $template_name , $template_path , $default_path ) ) ) ;
        $template  = ( string ) wp_cache_get( $cache_key , 'esf_events' ) ;

        if ( ! $template )
            $template      = esf_locate_template( $template_name , $template_path = '' , $default_path  = '' ) ;

        if ( ! file_exists( $template ) ) {
            return ;
        }

        if ( esf_check_is_array( $args ) ) {
            extract( $args ) ;
        }

        include( $template ) ;
    }

}

if ( ! function_exists( 'esf_locate_template' ) ) {

    function esf_locate_template( $template_name , $template_path = '' , $default_path = '' ) {

        if ( ! $template_path ) {
            $template_path = apply_filters( 'esf_template_path' , 'esf/' ) ;
        }

        if ( ! $default_path ) {
            $default_path = ESF_PLUGIN_PATH . '/templates/' ;
        }

        $template = locate_template( array(
            trailingslashit( $template_path ) . $template_name . '.php' ,
            $template_name . '.php' ,
                ) ) ;

        if ( ! $template ) {
            $template = $default_path . $template_name . '.php' ;
        }

        return apply_filters( 'esf_locate_template' , $template , $template_name ) ;
    }

}

if ( ! function_exists( 'esf_get_template_part' ) ) {

    function esf_get_template_part( $slug , $name = null ) {
        $template_name = isset( $name ) ? $slug . '-' . $name : $slug ;

        $cache_key = sanitize_key( implode( '-' , array( 'template-part' , $template_name ) ) ) ;
        $template  = ( string ) wp_cache_get( $cache_key , 'esf_events' ) ;

        if ( ! $template ) {
            $template = esf_locate_template( $template_name ) ;

            wp_cache_set( $cache_key , $template , 'esf_events' ) ;
        }

        $template = apply_filters( 'esf_get_template_part' , $template , $template_name ) ;

        if ( $template ) {
            load_template( $template , false ) ;
        }
    }

}

if ( ! function_exists( 'esf_display_event' ) ) {

    function esf_display_event( $event_id , $date , $month ) {
        ob_start() ;
        $event_obj = new ESF_Event( $event_id ) ;
        ?>
        <div class="esf_month_event_list_view">
            <div class="esf_month_event_date">
                <h2><?php echo esc_html( $date ) ; ?></h2>
                <h4><?php echo esc_html( $month ) ; ?></h4>
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
        $content   = ob_get_contents() ;
        ob_end_flush() ;

        return $content ;
    }

}

if ( ! function_exists( 'esf_display_event_msg' ) ) {

    function esf_display_event_msg( $msg ) {
        ob_start() ;
        ?>
        <div class="esf_month_event_list_view">
            <div class="esf_month_event_date"></div>
            <div class="esf_month_event_name">
                <div class="esf_no_events">
                    <h3><?php echo esc_html( $msg ) ; ?></h3>
                </div>
            </div>
        </div>
        <?php
        $content = ob_get_contents() ;
        ob_end_flush() ;

        return $content ;
    }

}