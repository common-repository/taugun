jQuery( function ( $ ) {
    'use strict' ;

    var file_frame ;
    $( 'body' ).on( 'click' , '.esf_upload_image_button' , function ( e ) {

        e.preventDefault( ) ;
        var $button = $( this ) ;
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open( ) ;
            return ;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media( {
            frame : 'select' ,
            title : $button.data( 'title' ) ,
            multiple : false ,
            library : {
                type : 'image'
            } ,
            button : {
                text : $button.data( 'button' )
            }
        } ) ;
        // When an image is selected, run a callback.
        file_frame.on( 'select' , function ( ) {
            var selection = file_frame.state( ).get( 'selection' ) ;
            selection.map( function ( attachment ) {
                attachment = attachment.toJSON( ) ;
                if ( attachment.id ) {
                    $button.closest( 'td' ).find( '#esf_preview_image img' ).attr( 'src' , attachment.url ).removeClass( 'esf_hide' ) ;
                    $button.closest( 'td' ).find( '.esf_upload_image_url' ).val( attachment.url ) ;
                }
            } ) ;
            // replace previous image with new one if selected
        } ) ;
        // Finally, open the modal
        file_frame.open( ) ;
    } ) ;

    var BSF_Admin = {
        init : function ( ) {
            this.trigger_load_event() ;

            $( document ).on( 'change' , '.esf_all_day' , this.hide_time_picker ) ;
            $( document ).on( 'click' , '.esf_save_close' , this.close_message_popup ) ;

        } , trigger_load_event : function () {
            this.hide_time_picker() ;

        } , hide_time_picker : function ( ) {
            if ( $( '.esf_all_day' ).is( ':checked' ) ) {
                $( '.esf_start_time' ).hide() ;
                $( '.esf_end_time' ).hide() ;
            } else {
                $( '.esf_start_time' ).show() ;
                $( '.esf_end_time' ).show() ;
            }
        } , close_message_popup : function ( ) {
            $( '.esf_save_msg' ).remove() ;
        } ,
    } ;

    BSF_Admin.init( ) ;
} ) ;