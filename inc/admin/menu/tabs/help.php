<?php
/**
 * Help Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'ESF_Help_Tab' ) ) {
    return new ESF_Help_Tab() ;
}

/**
 * ESF_Help_Tab.
 */
class ESF_Help_Tab extends ESF_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'help' ;
        $this->code  = 'fa-life-ring' ;
        $this->label = esc_html__( 'Help' , ESF_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_help' ) , array( $this , 'output_help' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        return array(
            array( 'type' => 'output_help' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the help content
     */
    public function output_help() {
        $support_site_url = '<a href="https://flintop.com/contact" target="_blank"> ' ;
        ?>
        <div class="esf_help_content">
            <h3><?php esc_html_e( 'Documentation' , ESF_LOCALE ) ; ?></h3>
            <p> <?php esc_html_e( 'Please check the documentation as we have lots of information there. The documentation file can be found inside the documentation folder which you will find when you unzip the downloaded zip file.' , ESF_LOCALE ) ; ?></p>
            <h3><?php esc_html_e( 'Contact Support' , ESF_LOCALE ) ; ?></h3>
            <p id="esf_support_content"> <?php echo sprintf( esc_html__( 'For support, feature request or any help, please %s register and open a support ticket on our site' , ESF_LOCALE ) , $support_site_url ) ; ?></a></p>   
        </div>
        <?php
    }

}

return new ESF_Help_Tab() ;
