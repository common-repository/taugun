<?php
/* New Location Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<form method="POST" class="esf_location_form" action="">
    <div class="esf_location_wrapper">
        <h2><?php esc_html_e( 'New Location' , ESF_LOCALE ) ; ?></h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Location Name' , ESF_LOCALE ) ; ?></label><span>*</span>
                    </th>
                    <td>
                        <input type="text" name='location[esf_name]' value='<?php echo esc_attr( $name ) ; ?>' placeholder="<?php esc_attr_e( 'Name' , ESF_LOCALE ) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Description' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <textarea name='location[esf_description]' placeholder="<?php echo esc_attr_e( 'Description' , ESF_LOCALE ) ?>"><?php echo esc_html( $description ) ; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Address Line1' , ESF_LOCALE ) ; ?></label><span>*</span>
                    </th>
                    <td>
                        <input type="text" name='location[esf_address_line1]' value='<?php echo esc_attr( $address_line1 ) ; ?>' placeholder="<?php esc_attr_e( 'Address Line 1' , ESF_LOCALE ) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Address Line2' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="text" name='location[esf_address_line2]' value='<?php echo esc_attr( $address_line2 ) ; ?>' placeholder="<?php esc_attr_e( 'Address Line 2' , ESF_LOCALE ) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'City' , ESF_LOCALE ) ; ?></label><span>*</span>
                    </th>
                    <td>
                        <input type="text" name='location[esf_city]' value='<?php echo esc_attr( $city ) ; ?>' placeholder="<?php esc_attr_e( 'City' , ESF_LOCALE ) ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'State' , ESF_LOCALE ) ; ?></label><span>*</span>
                    </th>
                    <td>
                        <input type="text" name='location[esf_state]' value='<?php echo esc_attr( $state ) ; ?>' placeholder="<?php esc_attr_e( 'State' , ESF_LOCALE ) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Country' , ESF_LOCALE ) ; ?></label><span>*</span>
                    </th>
                    <td>
                        <input type="text" name='location[esf_country]' value='<?php echo esc_attr( $country ) ; ?>' placeholder="<?php esc_attr_e( 'Country' , ESF_LOCALE ) ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'ZIP Code' , ESF_LOCALE ) ; ?></label><span>*</span>
                    </th>
                    <td>
                        <input type="text" name='location[esf_post_code]' value='<?php echo esc_attr( $post_code ) ; ?>' placeholder="<?php esc_attr_e( 'ZIP Code' , ESF_LOCALE ) ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Image' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="text" class="esf_upload_image_url" name='location[esf_image]' value='<?php echo esc_attr( $image ) ; ?>'/>
                        <input class="esf_upload_image_button button-secondary" data-title="<?php esc_attr_e( 'Choose Image' , ESF_LOCALE ) ; ?>"
                               data-button="<?php esc_attr_e( 'Use Image' , ESF_LOCALE ) ; ?>"
                               type="button" value="<?php esc_html_e( 'Choose Image' , ESF_LOCALE ) ; ?>" />
                        <div id="esf_preview_image">
                            <?php $img_class = ($image) ? '' : ' class=esf_hide' ; ?>
                            <img src="<?php echo esc_url( $image ) ; ?>"<?php echo esc_attr( $img_class ) ; ?> />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input name='esf_save' class='button-primary esf_save_btn' type='submit' value="<?php esc_attr_e( 'Add Location' , ESF_LOCALE ) ; ?>" />
            <input type="hidden" name="add_location" value="add-new"/>
            <?php
            wp_nonce_field( 'esf_add_location' , '_esf_nonce' , false , true ) ;
            ?>
        </p>
    </div>
</form>