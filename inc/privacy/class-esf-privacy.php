<?php
/*
 * GDPR Compliance
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly
}

if ( ! class_exists( 'ESF_Privacy' ) ) :

    /**
     * ESF_Privacy class
     */
    class ESF_Privacy {

        /**
         * ESF_Privacy constructor.
         */
        public function __construct() {
            $this->init_hooks() ;
        }

        /**
         * Register plugin
         */
        public function init_hooks() {
            // This hook registers Booking System privacy content
            add_action( 'admin_init' , array( __CLASS__ , 'register_privacy_content' ) , 20 ) ;
        }

        /**
         * Register Privacy Content
         */
        public static function register_privacy_content() {
            if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
                return ;
            }

            $content = self::get_privacy_message() ;
            if ( $content ) {
                wp_add_privacy_policy_content( esc_html__( 'Taugun' , ESF_LOCALE ) , $content ) ;
            }
        }

        /**
         * Prepare Privacy Content
         */
        public static function get_privacy_message() {

            return self::get_privacy_message_html() ;
        }

        /**
         * Get Privacy Content
         */
        public static function get_privacy_message_html() {
            ob_start() ;
            ?>
            <p><?php esc_html_e( 'This includes the basics of what personal data your store may be collecting, storing and sharing. Depending on what settings are enabled and which additional plugins are used, the specific information shared by your store will vary.' , ESF_LOCALE ) ?></p>
            <h2><?php esc_html_e( 'WHAT DOES THE PLUGIN DO?' , ESF_LOCALE ) ; ?></h2>
            <p><?php esc_html_e( 'Create Events and display the created events on the frontend.' , ESF_LOCALE ) ; ?> </p>
            <h2><?php esc_html_e( 'WHAT WE COLLECT AND STORE?' , ESF_LOCALE ) ; ?></h2>
            <p><?php esc_html_e( "This plugin doesn't collect any personal information from the users." , ESF_LOCALE ) ; ?> </p>
            <?php
            $contents = ob_get_contents() ;
            ob_end_clean() ;

            return $contents ;
        }

    }

    new ESF_Privacy() ;

endif;
