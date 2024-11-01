<?php
/**
 * Pro Version Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'ESF_Pro_Version_Tab' ) ) {
    return new ESF_Pro_Version_Tab() ;
}

/**
 * ESF_Pro_Version_Tab.
 */
class ESF_Pro_Version_Tab extends ESF_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'pro-version' ;
        $this->code  = 'fa-diamond' ;
        $this->label = esc_html__( 'Pro Version' , ESF_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_pro_version' ) , array( $this , 'output_pro_version' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        return array(
            array( 'type' => 'output_pro_version' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the pro version content
     */
    public function output_pro_version() {
        ?>
        <div class="esf_pro_content">
            <div class="esf-Pro-botton-top"><a href="https://flintop.com/taugun-events-calendar/" target="_blank"><?php esc_html_e( 'Buy Taugun Pro' , ESF_LOCALE ) ; ?></a></div>
            <p><?php esc_html_e( 'Pro Version of Taugun has the following features' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'Views' , ESF_LOCALE ) ; ?></h4>
            <p> <?php esc_html_e( 'In addition to Monthly View, Pro version supports Grid and List Views.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'Tickets' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'You can sell tickets for your events. Your users can make payments for the tickets using the inbuilt PayPal, Stripe and Offline payment gateways.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'RSVP' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'You can create and manage RSVP events on your site.' , ESF_LOCALE ) ?></p>
            <h4><?php esc_html_e( 'Additional Fields' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'If you want to display any additional options for events, then using Additional Fields you can add fields to any events and display the information for the users.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'Event Timer' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'Using Event Timer, you can display a count-down timer before the start of the event, before end of the event, before the start of ticket sale, before end of ticket sale, before the start of RSVP events and before end of RSVP for events.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'QR Code' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'For Tickets and RSVP, you can check-in the attendees by scanning the QR-Code which will be generated for each Ticket and RSVP.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'SMS' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'In addition to email notifications, the pro version supports SMS notifications which can be sent to customers for ticket confirmation and rsvp confirmation.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'Account Management' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'Customers can signup for an account using the signup form that comes up with the plugin.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'Customer Dashboard' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'Customers can see their ticket information, rsvp information from their dashboard after they login to the site.' , ESF_LOCALE ) ; ?></p>
            <h4><?php esc_html_e( 'WooCommerce' , ESF_LOCALE ) ; ?></h4>
            <p><?php esc_html_e( 'Taugun Pro supports WooCommerce integration using which ticket purchase process will go through WooCommerce and payments will be handled by WooCommerce.' , ESF_LOCALE ) ; ?></p>
            <div class="esf-Pro-botton-bottom"><a href="https://flintop.com/taugun-events-calendar/" target="_blank"><?php esc_html_e( 'Buy Taugun Pro' , ESF_LOCALE ) ; ?></a></div>

        </div>
        <?php
    }

}

return new ESF_Pro_Version_Tab() ;
