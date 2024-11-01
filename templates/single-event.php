<?php
/**
 * This template displays all single events
 * 
 * This template can be overridden by copying it to yourtheme/taugun/archive-event.php
 * 
 * To maintain compatibility, Taugun will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

global $post , $esf_event ;

if ( post_password_required() ) {
    echo get_the_password_form() ;
    return ;
}

$details_class = '' ;
$esf_event     = esf_get_event( $post->ID ) ;
$ical_link     = add_query_arg( array( 'action' => 'esf_download_ics_file' , 'event_id' => $esf_event->get_id() ) , get_permalink() ) ;

if ( ! $esf_event->get_location()->exists() && ! $esf_event->get_organizer()->exists() ) {
    $details_class = ' esf_one_column' ;
} elseif ( $esf_event->get_location()->exists() && ! $esf_event->get_organizer()->exists() ) {
    $details_class = ' esf_two_column' ;
} elseif ( ! $esf_event->get_location()->exists() && $esf_event->get_organizer()->exists() ) {
    $details_class = ' esf_two_column' ;
}
/**
 * Header
 */
get_header() ;
?>
<div class="esf_single_event_wrapper">
    <div class="esf_single_event_container">
        <?php
        /**
         * Hook: esf_before_single_event_summary.
         */
        do_action( 'esf_before_single_event_header' ) ;
        ?>
        <div class="esf_single_event_head">
            <div class="esf_single_event_feature_image">
                <img src="<?php echo esc_url( $esf_event->get_formatted_featured_image() ) ; ?>" />
            </div>
            <div class="esf_single_event_title_section">
                <div class="esf_single_event_title_content">
                    <div class="esf_single_event_title_content_date">
                        <h3><?php echo esc_html( $esf_event->format_datetime( 'd' ) ) ; ?></h3>
                        <h4><?php echo esc_html( $esf_event->format_datetime( 'M' ) ) ; ?></h4>
                    </div>
                    <div class="esf_single_event_title">
                        <h3><?php echo esc_html( $esf_event->get_name() ) ; ?></h3>
                        <p><?php echo esc_html( $esf_event->display_datetime( 'start-end-date' ) ) ; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        /**
         * Hook: esf_before_single_event_summary.
         */
        do_action( 'esf_after_single_event_header' ) ;
        ?>
        <?php
        /**
         * Hook: esf_before_single_event_content.
         */
        do_action( 'esf_before_single_event_content' ) ;
        ?>
        <div class="esf_single_event_content">
            <?php
            /**
             * Hook: esf_single_event_content_start.
             */
            do_action( 'esf_single_event_content_start' ) ;
            ?>
            <?php if ( $esf_event->get_organizer()->exists() ): ?>
                <div class="esf_single_event_organizer">
                    <h4 class="esf_organizer_title"><?php esc_html_e( 'Organizer' , ESF_LOCALE ) ; ?></h4>
                    <img src="<?php echo esc_url( $esf_event->get_organizer()->get_formatted_image() ) ; ?>" />
                    <h4><?php echo esc_html( $esf_event->get_organizer()->get_name() ) ; ?></h4>
                    <?php echo wp_kses_post( $esf_event->get_organizer()->get_description() ) ; ?>
                    <?php if ( $esf_event->get_organizer()->get_phone() ): ?>
                        <p>
                            <b><i class="fa fa-mobile"></i><?php esc_html_e( 'Phone Number' , ESF_LOCALE ) ; ?>:</b>
                            <?php echo esc_html( $esf_event->get_organizer()->get_phone() ) ; ?>
                        </p>
                    <?php endif ; ?>
                    <?php if ( $esf_event->get_organizer()->get_email() ): ?>
                        <p>
                            <b><i class="fa fa-envelope"></i><?php esc_html_e( 'Email' , ESF_LOCALE ) ; ?>:</b>
                            <?php echo esc_html( $esf_event->get_organizer()->get_email() ) ; ?>
                        </p>
                    <?php endif ; ?>
                    <?php if ( $esf_event->get_organizer()->get_website() ): ?>
                        <p>
                            <b><i class="fa fa-globe"></i><?php esc_html_e( 'Website' , ESF_LOCALE ) ; ?>:</b>
                            <?php echo esc_html( $esf_event->get_organizer()->get_website() ) ; ?>
                        </p>
                    <?php endif ; ?>
                    <?php if ( $esf_event->get_organizer()->get_additional_info() ): ?>
                        <p>
                            <b><i class="fa fa-globe"></i><?php esc_html_e( 'Info' , ESF_LOCALE ) ; ?>:</b>
                            <?php echo wp_kses_post( $esf_event->get_organizer()->get_additional_info() ) ; ?>
                        </p>
                    <?php endif ; ?>
                </div>
            <?php endif ; ?>
            <div class="esf_single_event_details<?php echo esc_attr( $details_class ) ; ?>">
                <div class="esf_single_event_decription">
                    <a  href="<?php echo esf_get_google_calendar_link( $esf_event ) ; ?>"target="_blank" class="esf_single_event_gc_btn"><?php esc_html_e( 'Google Calendar' , ESF_LOCALE ) ; ?></a>
                    <a href="<?php echo esc_url( $ical_link ) ; ?>"  class="esf_single_event_ie_btn"><?php esc_html_e( 'ICAL Export' , ESF_LOCALE ) ; ?></a>
                </div>
                <div class="esf_single_event_details_info">
                    <div class="esf_single_event_date">
                        <h4><i class="fa fa-calendar"></i><?php esc_html_e( 'Start Date' , ESF_LOCALE ) ; ?></h4>
                        <p><?php echo esc_html( $esf_event->display_datetime( 'start-datetime' ) ) ; ?></p>
                    </div>
                    <div class="esf_single_event_time">
                        <h4><i class="fa fa-calendar"></i><?php esc_html_e( 'End Date' , ESF_LOCALE ) ; ?></h4>
                        <p><?php echo esc_html( $esf_event->display_datetime( 'end-datetime' ) ) ; ?></p>
                    </div>
                    <div class="esf_single_event_website">
                        <h4><i class="fa fa-money"></i><?php esc_html_e( 'Price' , ESF_LOCALE ) ; ?></h4>
                        <p><?php echo ($esf_event->get_price()) ? esf_price( $esf_event->get_price() ) : esc_html__( 'Free' , ESF_LOCALE ) ; ?></p>
                    </div>
                </div>
                <div class="esf_single_event_decription">
                    <?php echo wp_kses_post( wpautop( $esf_event->get_description() ) ) ; ?>
                </div>
                <div class="esf_single_event_tag_cat">
                    <?php
                    if ( esf_check_is_array( $esf_event->get_tag() ) ):
                        ?>
                        <div class="esf_single_event_tag">
                            <h4><i class="fa fa-tag"></i><?php esc_html_e( 'Tag' , ESF_LOCALE ) ; ?></h4>
                            <?php echo esf_get_the_terms_html( $esf_event->get_tag() ) ; ?>
                        </div>
                    <?php endif ; ?>
                    <?php
                    if ( esf_check_is_array( $esf_event->get_category() ) ):
                        ?>
                        <div class="esf_single_event_cat">
                            <h4><i class="fa fa-folder"></i><?php esc_html_e( 'Category' , ESF_LOCALE ) ; ?></h4>
                            <?php echo esf_get_the_terms_html( $esf_event->get_category() ) ; ?>
                        </div>
                    <?php endif ; ?>
                    <?php
                    if ( $esf_event->get_website() ):
                        ?>
                        <div class="esf_single_event_cat">
                            <h4><i class="fa fa-globe"></i><?php esc_html_e( 'Website' , ESF_LOCALE ) ; ?></h4>
                            <a href="<?php echo esc_url( $esf_event->get_website() ) ; ?>"><?php echo esc_html( $esf_event->get_website() ) ; ?></a>
                        </div>
                    <?php endif ; ?>
                </div>
            </div>
            <?php if ( $esf_event->get_location()->exists() ): ?>
                <div class="esf_single_event_location">
                    <h4><?php esc_html_e( 'Location' , ESF_LOCALE ) ; ?></h4>
                    <address><?php echo wp_kses_post( $esf_event->get_location()->get_formatted_address() ) ; ?> </address>
                    <div class="esf_single_event_location_map">
                        <img src="<?php echo esc_url( $esf_event->get_location()->get_formatted_image() ) ; ?>" />
                    </div>
                </div>
                <?php
            endif ;
            /**
             * Hook: esf_single_event_content_end.
             */
            do_action( 'esf_single_event_content_end' ) ;
            ?>
        </div>
        <?php
        /**
         * Hook: esf_after_single_event_content.
         */
        do_action( 'esf_after_single_event_content' ) ;
        ?>
    </div>
</div>
<div class="esf_clear"></div>
<?php
/**
 * Side Bar
 */
get_sidebar() ;

/**
 * Footer
 */
get_footer() ;
