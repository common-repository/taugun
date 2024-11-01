<?php
/* Event Settings */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="esf_events_settings_wrapper">
    <?php wp_nonce_field( 'esf_save_event_settings' , 'esf_events_nonce' ) ; ?>

    <div class="esf_events_row">
        <h2><?php esc_html_e( 'Event Date and Time' , ESF_LOCALE ) ; ?></h2>
        <?php
        $args           = array(
            'name'        => 'esf_start_date' ,
            'value'       => $event->get_formatted_datetime( 'date' , true , false ) ,
            'wp_zone'     => false ,
            'placeholder' => ESF_Date_Time::get_wp_date_format() ,
                ) ;
        esf_get_datepicker_html( $args ) ;
        ?>
        <input type="time" class="esf_start_time" name="esf_start_time" value="<?php echo esc_attr( $event->get_formatted_datetime( 'time' , true , false ) ) ; ?>"/>
        <?php
        esc_html_e( 'to' , ESF_LOCALE ) ;
        $args           = array(
            'name'        => 'esf_end_date' ,
            'value'       => $event->get_formatted_datetime( 'date' , false , false ) ,
            'wp_zone'     => false ,
            'placeholder' => ESF_Date_Time::get_wp_date_format() ,
                ) ;
        esf_get_datepicker_html( $args ) ;
        ?>
        <input type="time" class="esf_end_time" name="esf_end_time" value="<?php echo esc_attr( $event->get_formatted_datetime( 'time' , false , false ) ) ; ?>"/>
        <select class="esf_select2" name="esf_timezone">
            <?php echo wp_timezone_choice( $event->maybe_wp_timezone() , get_user_locale() ) ; ?>
        </select>
    </div>

    <div class="esf_events_row">
        <input type="checkbox" class="esf_all_day" name="esf_all_day" <?php checked( $event->get_all_day() , 'yes' ) ; ?>/><?php esc_html_e( 'All Day' , ESF_LOCALE ) ?>
    </div>

    <div class="esf_events_row">
        <h2><?php esc_html_e( 'Event Location' , ESF_LOCALE ) ; ?></h2>
        <?php
        $location_args  = array(
            'name'        => 'esf_location' ,
            'list_type'   => 'locations' ,
            'action'      => 'esf_location_search' ,
            'placeholder' => esc_html__( 'Search a Location' , ESF_LOCALE ) ,
            'multiple'    => false ,
            'options'     => array( $event->get_location_id() ) ,
                ) ;
        esf_select2_html( $location_args ) ;
        ?>
    </div>

    <div class="esf_events_row">
        <h2><?php esc_html_e( 'Event Organizer' , ESF_LOCALE ) ; ?></h2>
        <?php
        $organizer_args = array(
            'name'        => 'esf_organizer' ,
            'list_type'   => 'organizers' ,
            'action'      => 'esf_organizer_search' ,
            'placeholder' => esc_html__( 'Search an Oganizer' , ESF_LOCALE ) ,
            'multiple'    => false ,
            'options'     => array( $event->get_organizer_id() ) ,
                ) ;
        esf_select2_html( $organizer_args ) ;
        ?>
    </div>
    <div class="esf_events_row">
        <h2><?php esc_html_e( 'Event Price' , ESF_LOCALE ) ; ?></h2>
        <input type="number" name="esf_price" value="<?php echo esc_attr( $event->get_price() ) ; ?>"/>
    </div>
    <div class="esf_events_row">
        <h2><?php esc_html_e( 'Event Website' , ESF_LOCALE ) ; ?></h2>
        <input type="url" name="esf_website" value="<?php echo esc_attr( $event->get_website() ) ; ?>"/>
    </div>
</div>
<?php
