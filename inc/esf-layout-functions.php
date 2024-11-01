<?php
/*
 * Layout functions
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists( 'esf_select2_html' ) ) {

    /**
     * Function to display Select2 HTML
     * */
    function esf_select2_html( $args , $echo = true ) {
        $args = wp_parse_args( $args , array(
            'class'             => '' ,
            'id'                => '' ,
            'name'              => '' ,
            'list_type'         => '' ,
            'action'            => '' ,
            'placeholder'       => '' ,
            'custom_attributes' => '' ,
            'multiple'          => true ,
            'allow_clear'       => true ,
            'selected'          => true ,
            'options'           => array() ,
                )
                ) ;

        $multiple = $args[ 'multiple' ] ? 'multiple="multiple"' : '' ;
        $name     = esc_attr( '' !== $args[ 'name' ] ? $args[ 'name' ] : $args[ 'id' ] ) . '[]' ;
        $options  = array_filter( esf_check_is_array( $args[ 'options' ] ) ? $args[ 'options' ] : array() ) ;

        // Custom attribute handling.
        $custom_attributes = esf_get_select2_custom_attributes( $args ) ;

        ob_start() ;
        ?><select <?php echo esc_attr( $multiple ) ; ?> 
            name="<?php echo esc_attr( $name ) ; ?>" 
            id="<?php echo esc_attr( $args[ 'id' ] ) ; ?>" 
            data-action="<?php echo esc_attr( $args[ 'action' ] ) ; ?>" 
            class="esf_select2_search <?php echo esc_attr( $args[ 'class' ] ) ; ?>" 
            data-placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ) ; ?>" 
            <?php echo implode( ' ' , $custom_attributes ) ; ?>
            <?php echo $args[ 'allow_clear' ] ? 'data-allow_clear="true"' : '' ?> >
                <?php
                foreach ( $options as $option_id ) {
                    $option_value = '' ;
                    switch ( $args[ 'list_type' ] ) {
                        case 'post':
                            $option_value = get_the_title( $option_id ) ;
                            break ;
                        case 'locations':
                            $option_value = get_the_title( $option_id ) . ' (#' . absint( $option_id ) . ')' ;
                            break ;
                        case 'organizers':
                            $option_value = get_the_title( $option_id ) . ' (#' . absint( $option_id ) . ')' ;
                            break ;
                    }

                    if ( $option_value ) {
                        ?>
                    <option value="<?php echo esc_attr( $option_id ) ; ?>" <?php echo $args[ 'selected' ] ? 'selected="selected"' : '' ?>><?php echo esc_html( $option_value ) ; ?></option>
                    <?php
                }
            }
            ?></select><?php
        $html = ob_get_clean() ;

        if ( $echo )
            echo $html ;

        return $html ;
    }

}

if ( ! function_exists( 'esf_get_select2_custom_attributes' ) ) {

    function esf_get_select2_custom_attributes( $value ) {
        $custom_attributes = array() ;

        if ( ! empty( $value[ 'custom_attributes' ] ) && is_array( $value[ 'custom_attributes' ] ) ) {
            foreach ( $value[ 'custom_attributes' ] as $attribute => $attribute_value ) {
                $custom_attributes[] = esc_attr( $attribute ) . '=' . esc_attr( $attribute_value ) . '' ;
            }
        }

        return $custom_attributes ;
    }

}

if ( ! function_exists( 'esf_get_datepicker_html' ) ) {

    function esf_get_datepicker_html( $args , $echo = true ) {
        $args = wp_parse_args( $args , array(
            'class'             => '' ,
            'id'                => '' ,
            'name'              => '' ,
            'placeholder'       => '' ,
            'custom_attributes' => '' ,
            'value'             => '' ,
            'wp_zone'           => true ,
                ) ) ;

        $name = ('' !== $args[ 'name' ]) ? $args[ 'name' ] : $args[ 'id' ] ;

        // Custom attribute handling.
        $custom_attributes = esf_get_select2_custom_attributes( $args ) ;
        $value             = ! empty( $args[ 'value' ] ) ? ESF_Date_Time::get_date_object_format_datetime( $args[ 'value' ] , 'date' , $args[ 'wp_zone' ] ) : '' ;
        ob_start() ;
        ?>
        <input type = "text" 
               id="<?php echo esc_attr( $args[ 'id' ] ) ; ?>"
               value = "<?php echo esc_attr( $value ) ; ?>"
               class="esf_datepicker <?php echo esc_attr( $args[ 'class' ] ) ; ?>" 
               placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ) ; ?>" 
               <?php echo implode( ' ' , $custom_attributes ) ; ?>
               />

        <input type = "hidden" 
               class="esf_alter_datepicker_value" 
               name="<?php echo esc_attr( $name ) ; ?>"
               value = "<?php echo esc_attr( $args[ 'value' ] ) ; ?>"
               /> 
        <?php
        $html              = ob_get_clean() ;

        if ( $echo )
            echo $html ;

        return $html ;
    }

}


if ( ! function_exists( 'esf_display_action' ) ) {

    function esf_display_action( $status , $id , $current_url ) {
        switch ( $status ) {
            case 'edit':
                $status_name = esc_html__( 'Edit' , ESF_LOCALE ) ;
                break ;
            default :
                $status_name = esc_html__( 'Delete Permanently' , ESF_LOCALE ) ;
                break ;
        }

        if ( $status == 'edit' ) {
            return '<a href="' . esc_url( add_query_arg( array( 'section' => $status , 'id' => $id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
        } elseif ( $status == 'delete' ) {
            return '<a class="esf_delete_data" href="' . esc_url( add_query_arg( array( 'action' => $status , 'id' => $id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
        } else {
            return '<a href="' . esc_url( add_query_arg( array( 'action' => $status , 'id' => $id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
        }
    }

}