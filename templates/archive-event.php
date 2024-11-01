<?php
/**
 * This template displays event archives including events page which is a post type archive.
 * 
 * This template can be overridden by copying it to yourtheme/taugun/archive-event.php
 * 
 * To maintain compatibility, Taugun will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
global $post ;

get_header() ;
$prepare_data = ESF_Archive_Page_Handler::prepare_data() ;

extract( $prepare_data ) ;
?>
<div class = "esf_shop_wrapper">
    <div class="esf_shop_content">
        <form method="POST" id="mainform" enctype="multipart/form-data" class="esf_events_calendar_form">
            <div class="esf_shop_header_field">
                <div class="esf_shop_event_filters">
                    <select name="month">
                        <?php
                        $month_array = esf_get_drop_down_values( 'months' ) ;
                        foreach ( $month_array as $month_id => $month_name ) {
                            ?>
                            <option value="<?php echo esc_attr( $month_id ) ; ?>" <?php selected( $month , $month_id ) ; ?>><?php echo esc_html( $month_name ) ; ?></option>
                        <?php } ; ?>
                    </select>
                    <select name="year">
                        <?php
                        for ( $i = ( $year - 4 ) ; $i <= ( $year + 5 ) ; $i ++ ) :
                            ?>
                            <option value="<?php echo esc_attr( $i ) ; ?>" <?php selected( $year , $i ) ; ?>><?php echo esc_html( $i ) ; ?></option>
                        <?php endfor ; ?>
                    </select>
                    <button type="submit" title='<?php esc_html_e( 'Submit' , ESF_LOCALE ) ; ?>'class="esf_events_submit"> <?php esc_html_e( 'Submit' , ESF_LOCALE ) ; ?> </button>
                </div>
            </div>
            <div class="esf_shop_view_port">
                <table class="esf_shop_month_view_table">
                    <thead>
                        <tr>
                            <?php
                            $first_week = get_option( 'start_of_week' , 1 ) ;
                            $days       = esf_get_drop_down_values( 'days' ) ;
                            for ( $index = 0 ; $index < 7 ; $index ++ ) :
                                $i = ( $index + $first_week ) % 7 ;
                                ?>
                                <th><?php echo esc_html( $days[ $i ] ) ; ?></th>
                            <?php endfor ; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $current_time = strtotime( 'today midnight' ) ;
                            $index        = 0 ;
                            for ( $timestamp = $start_timestamp ; $timestamp <= $end_timestamp ; $timestamp = strtotime( '+1 day' , $timestamp ) ) :

                                $start_time = date( 'Y-m-d' , $timestamp ) ;

                                // class assigning
                                $attr       = array() ;
                                $class_name = (date( 'n' , $timestamp ) != absint( $month )) ? 'esf_events_calendar_diff_month' : 'esf_events_day_grid' ;
                                $class_name .= ($timestamp == $current_time) ? ' esf_current_date' : '' ;
                                if ( isset( $events[ $start_time ] ) ) {
                                    $class_name .= ' esf_event_presented_date' ;
                                    $attr       = $events[ $start_time ] ;
                                }
                                $start_time_object = ESF_Date_Time::get_tz_date_time_object( date( 'Y-m-d H:i:s' , $timestamp ) , false , true ) ;
                                $end_time_object   = ESF_Date_Time::get_tz_date_time_object( date( 'Y-m-d H:i:s' , $timestamp ) , false , true )->modify( '+1 day' ) ;
                                ?>
                                <td width="14.285%" class="<?php echo esc_attr( $class_name ) ; ?>" 
                                    data-event_ids ='<?php echo esc_attr( wp_json_encode( $attr ) ) ; ?>' 
                                    data-current_date = '<?php echo esc_attr( date( 'd' , $timestamp ) ) ; ?>'
                                    data-current_month ='<?php echo esc_attr( $month_array[ $month ] ) ; ?>'
                                    >
                                    <a class='esf_events_day' href="#">
                                        <?php echo esc_html( date( 'd' , $timestamp ) ) ; ?>
                                    </a>
                                    <span>
                                        <?php
                                        if ( isset( $events[ $start_time ] ) ) {
                                            echo sprintf( esc_html__( '%s Event(s)' , ESF_LOCALE ) , count( $events[ $start_time ] ) ) ;
                                        }
                                        ?>
                                    </span>
                                </td>
                                <?php
                                $index ++ ;
                                if ( $index % 7 === 0 )
                                    echo '</tr><tr>' ;

                            endfor ;
                            ?>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <h3> 
                        <?php echo esc_html( sprintf( __( '%s Month Events' , ESF_LOCALE ) , $month_array[ $month ] ) ) ; ?> 
                    </h3>
                    <div class="esf_month_event_list">
                        <?php
                        $count         = 0 ;
                        $dates         = array( '01' , '02' , '03' , '04' , '05' , '06' , '07' , '08' , '09' , '10' , '11' , '12' , '13' , '14' , '15' , '16' , '17' , '18' , '19' , '20' , '21' , '22' , '23' , '24' , '25' , '26' , '27' , '28' , '29' , '30' , '31' ) ;
                        $current_date  = esc_html( date( 'd' , $current_time ) ) ;
                        $current_month = esc_html( date( 'm' , $current_time ) ) ;

                        if ( $count != 1 && $month == $current_month ) {
                            foreach ( $dates as $date ) {
                                $start_month = $year . '-' . $month . '-' . $date ;
                                if ( $current_date <= $date && $count != 1 && isset( $events[ $start_month ] ) ) {
                                    foreach ( $events[ $start_month ] as $event_id ) {
                                        esf_display_event( $event_id , $date , $month_array[ $month ] ) ; // display events.
                                    }
                                    $count = 1 ;
                                }
                            }
                        }

                        if ( $count != 1 && $month == $current_month ) {
                            foreach ( $dates as $date ) {
                                $start_month = $year . '-' . $month . '-' . $date ;
                                if ( $current_date > $date && $count != 1 && isset( $events[ $start_month ] ) ) {
                                    esf_display_event_msg( esc_html__( 'All Events on this month has passed' , ESF_LOCALE ) ) ;
                                    $count = 1 ;
                                }
                            }
                        }

                        if ( $count != 1 && $month < $current_month ) {
                            foreach ( $dates as $date ) {
                                $start_month = $year . '-' . $month . '-' . $date ;
                                if ( $count != 1 && isset( $events[ $start_month ] ) ) {
                                    esf_display_event_msg( esc_html__( 'All Events on this month has passed' , ESF_LOCALE ) ) ;
                                    $count = 1 ;
                                }
                            }
                        }

                        if ( $count != 1 && $month > $current_month ) {
                            foreach ( $dates as $date ) {
                                $start_month = $year . '-' . $month . '-' . $date ;
                                if ( $count != 1 && isset( $events[ $start_month ] ) ) {
                                    foreach ( $events[ $start_month ] as $event_id ) {
                                        esf_display_event( $event_id , $date , $month_array[ $month ] ) ; // display events.
                                    }
                                    $count = 1 ;
                                }
                            }
                        }

                        if ( $count != 1 ) {
                            foreach ( $dates as $date ) {
                                $start_month = $year . '-' . $month . '-' . $date ;
                                if ( $count != 1 && ! isset( $events[ $start_month ] ) ) {
                                    esf_display_event_msg( esc_html__( 'No Events Found' , ESF_LOCALE ) ) ;
                                    $count = 1 ;
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div style="clear: both;"></div>
<?php
get_sidebar() ;
get_footer() ;
