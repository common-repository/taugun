/* global esf_archive_event_params */
jQuery( function ( $ ) {
    'use strict' ;

    var ESF_Archive_event = {

        init : function () {
            $( document ).on( 'click' , 'td.esf_events_day_grid' , this.view_events ) ;
        } ,
        view_events : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;

            var data = {
                action : 'esf_events_view' ,
                event_ids : $( $this ).data( 'event_ids' ) ,
                current_date : $( $this ).data( 'current_date' ) ,
                current_month : $( $this ).data( 'current_month' ) ,
                esf_security : esf_archive_event_params.events_nonce ,
            } ;

            $.ajax( {
                type : 'POST' ,
                url : esf_archive_event_params.ajax_url ,
                data : data
            } ).done( function ( response ) {
                if ( true === response.success ) {
                    $( 'div.esf_month_event_list_view' ).remove() ;
                    $( response.data.content ).appendTo( 'div.esf_month_event_list' ) ;
                    $( 'html,body' ).animate( { scrollTop : $( "#esf_month_events_wrapper" ).offset().top } , 700 ) ;
                } else {
                    window.alert( response.data.error ) ;
                }
            } ) ;

        } ,
        block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        } , unblock : function ( id ) {
            $( id ).unblock() ;
        } ,
    } ;
    ESF_Archive_event.init() ;
} ) ;
