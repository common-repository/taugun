<?php

/**
 * General Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'ESF_General_Tab' ) ) {
    return new ESF_General_Tab() ;
}

/**
 * ESF_General_Tab.
 */
class ESF_General_Tab extends ESF_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'general' ;
        $this->code  = 'fa-cogs' ;
        $this->label = esc_html__( 'General' , ESF_LOCALE ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        $settings = array() ;
        $function = $current_section . '_section_array' ;

        if ( method_exists( $this , $function ) )
            $settings = $this->$function() ;

        return apply_filters( sanitize_key( $this->plugin_slug . '_get_settings_' . $this->id ) , $settings , $current_section ) ;
    }

    /**
     * Get settings general section array.
     */
    public function general_section_array() {

        $section_fields = array() ;
        $currencies     = get_esf_currencies() ;

        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Currency Settings' , ESF_LOCALE ) ,
            'id'    => 'esf_currency_options' ,
                ) ;

        $section_fields[] = array(
            'title'   => esc_html__( 'Currency' , ESF_LOCALE ) ,
            'type'    => 'select' ,
            'default' => 'USD' ,
            'options' => $currencies ,
            'id'      => 'esf_currency' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Currency Symbol Position' , ESF_LOCALE ) ,
            'type'    => 'select' ,
            'default' => 'left' ,
            'options' => array(
                'left'        => esc_html__( 'Left' , ESF_LOCALE ) ,
                'right'       => esc_html__( 'Right' , ESF_LOCALE ) ,
                'left_space'  => esc_html__( 'Left After a Space' , ESF_LOCALE ) ,
                'right_space' => esc_html__( 'Right After a Space' , ESF_LOCALE )
            ) ,
            'id'      => 'esf_currency_position' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Decimal Separator' , ESF_LOCALE ) ,
            'type'    => 'text' ,
            'default' => '.' ,
            'id'      => 'esf_currency_decimal_separator' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Number of Decimals' , ESF_LOCALE ) ,
            'type'    => 'text' ,
            'default' => '2' ,
            'id'      => 'esf_price_num_decimals' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Thousand Separator' , ESF_LOCALE ) ,
            'type'    => 'text' ,
            'default' => ',' ,
            'id'      => 'esf_currency_thousand_separator' ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'esf_currency_options' ,
                ) ;
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Permalink Settings' , ESF_LOCALE ) ,
            'id'    => 'esf_permalink_options' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Custom Single Event Slug' , ESF_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'event' ,
            'id'      => $this->get_option_key( 'custom_event_slug' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Custom Category Slug' , ESF_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'event-category' ,
            'id'      => $this->get_option_key( 'custom_category_slug' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Custom Tag Slug' , ESF_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'event-tag' ,
            'id'      => $this->get_option_key( 'custom_tag_slug' ) ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'esf_permalink_options' ,
                ) ;
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Display Settings' , ESF_LOCALE ) ,
            'id'    => 'esf_display_settings' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Display Timezone' , ESF_LOCALE ) ,
            'type'    => 'checkbox' ,
            'default' => 'yes' ,
            'id'      => $this->get_option_key( 'hide_utc' ) ,
                ) ;
        $section_fields[] = array(
            'title' => esc_html__( 'Custom CSS' , ESF_LOCALE ) ,
            'type'  => 'textarea' ,
            'id'    => $this->get_option_key( 'custom_css' ) ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'esf_display_settings' ,
                ) ;

        return $section_fields ;
    }

}

return new ESF_General_Tab() ;
