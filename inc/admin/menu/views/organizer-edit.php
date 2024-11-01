<?php
/* Edit Organizer Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<form method="POST" class="esf_organizer_form" action="">
    <div class="esf_organizer_edit">
        <h2><?php esc_html_e( 'Edit Organizer' , ESF_LOCALE ) ; ?></h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Name' , ESF_LOCALE ) ; ?></label><span>*</span>
                    </th>
                    <td>
                        <input type="text" name='organizer[esf_name]' value='<?php echo esc_attr( $organizer_object->get_name() ) ; ?>' placeholder="<?php esc_attr_e( 'Name' , ESF_LOCALE ) ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Description' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <textarea name='organizer[esf_description]' placeholder="<?php esc_attr_e( 'Description' , ESF_LOCALE ) ?>"><?php echo esc_html( $organizer_object->get_description() ) ; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Image' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="text" class="esf_upload_image_url" name='organizer[esf_image]' value='<?php echo esc_attr( $organizer_object->get_image() ) ; ?>'/>
                        <input class="esf_upload_image_button button-secondary" data-title="<?php esc_attr_e( 'Choose Image' , ESF_LOCALE ) ; ?>"
                               data-button="<?php esc_attr_e( 'Use Image' , ESF_LOCALE ) ; ?>"
                               type="button" value="<?php esc_html_e( 'Choose Image' , ESF_LOCALE ) ; ?>" />
                        <div id="esf_preview_image">
                            <?php $img_class = ($organizer_object->get_image()) ? '' : ' class=esf_hide' ; ?>
                            <img src="<?php echo esc_url( $organizer_object->get_image() ) ; ?>"<?php echo esc_attr( $img_class ) ; ?> />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Phone' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="text" name='organizer[esf_phone]' value='<?php echo esc_attr( $organizer_object->get_phone() ) ; ?>' placeholder="<?php esc_attr_e( 'Phone' , ESF_LOCALE ) ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Email ID' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="email" name='organizer[esf_email]' value='<?php echo esc_attr( $organizer_object->get_email() ) ; ?>' placeholder="<?php esc_attr_e( 'Email ID' , ESF_LOCALE ) ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Website' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="url" name='organizer[esf_website]' value='<?php echo esc_attr( $organizer_object->get_website() ) ; ?>' placeholder="<?php esc_attr_e( 'Website' , ESF_LOCALE ) ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope='row'>
                        <label><?php esc_html_e( 'Additional Info' , ESF_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <textarea name='organizer[esf_additional_info]' placeholder="<?php esc_attr_e( 'Additional Info' , ESF_LOCALE ) ?>"><?php echo esc_html( $organizer_object->get_additional_info() ) ; ?></textarea>

                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input name='esf_save' class='button-primary esf_save_btn' type='submit' value="<?php esc_attr_e( 'Update Organizer' , ESF_LOCALE ) ; ?>" />
            <input type='hidden' name='organizer[id]' value='<?php echo esc_attr( $organizer_object->get_id() ) ; ?>' />
            <input type="hidden" name="edit_organizer" value="add-edit"/>
            <?php wp_nonce_field( 'esf_edit_organizer' , '_esf_nonce' , false , true ) ; ?>
        </p>
    </div>
</form>