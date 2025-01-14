jQuery( function ( $ ) {
    'use strict' ;

    try {
        $( document.body ).on( 'esf-enhanced-init' , function () {
            if ( $( 'select.esf_select2' ).length ) {
                //Select2 with customization
                $( 'select.esf_select2' ).each( function () {
                    var select2_args = {
                        allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
                        placeholder : $( this ).data( 'placeholder' ) ,
                        minimumResultsForSearch : 10 ,
                    } ;
                    $( this ).select2( select2_args ) ;
                } ) ;
            }
            if ( $( 'select.esf_select2_search' ).length ) {
                //Multiple select with ajax search
                $( 'select.esf_select2_search' ).each( function () {
                    var select2_args = {
                        allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
                        placeholder : $( this ).data( 'placeholder' ) ,
                        minimumInputLength : $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : 3 ,
                        escapeMarkup : function ( m ) {
                            return m ;
                        } ,
                        ajax : {
                            url : ajaxurl ,
                            dataType : 'json' ,
                            delay : 250 ,
                            data : function ( params ) {
                                return {
                                    term : params.term ,
                                    action : $( this ).data( 'action' ) ? $( this ).data( 'action' ) : '' ,
                                    esf_security : $( this ).data( 'nonce' ) ? $( this ).data( 'nonce' ) : esf_enhanced_select_params.search_nonce ,
                                } ;
                            } ,
                            processResults : function ( data ) {
                                var terms = [ ] ;
                                if ( data ) {
                                    $.each( data , function ( id , term ) {
                                        terms.push( {
                                            id : id ,
                                            text : term
                                        } ) ;
                                    } ) ;
                                }
                                return {
                                    results : terms
                                } ;
                            } ,
                            cache : true
                        }
                    } ;

                    $( this ).select2( select2_args ) ;
                } ) ;
            }

            if ( $( '#esf_from_date' ).length ) {
                $( '#esf_from_date' ).each( function ( ) {

                    $( this ).datepicker( {
                        altField : $( this ).next( ".esf_alter_datepicker_value" ) ,
                        altFormat : 'yy-mm-dd' ,
                        changeMonth : true ,
                        changeYear : true ,
                        onClose : function ( selectedDate ) {
                            var maxDate = new Date( Date.parse( selectedDate ) ) ;
                            maxDate.setDate( maxDate.getDate() + 1 ) ;
                            $( '#esf_to_date' ).datepicker( 'option' , 'minDate' , maxDate ) ;
                        }
                    } ) ;

                } ) ;
            }

            if ( $( '#esf_to_date' ).length ) {
                $( '#esf_to_date' ).each( function ( ) {

                    $( this ).datepicker( {
                        altField : $( this ).next( ".esf_alter_datepicker_value" ) ,
                        altFormat : 'yy-mm-dd' ,
                        changeMonth : true ,
                        changeYear : true ,
                        onClose : function ( selectedDate ) {
                            $( '#esf_from_date' ).datepicker( 'option' , 'maxDate' , selectedDate ) ;
                        }
                    } ) ;

                } ) ;
            }

            if ( $( '.esf_datepicker' ).length ) {
                $( '.esf_datepicker' ).on( 'change' , function( ) {
                    if( $( this ).val() === '' ) {
                        $( this ).next( ".esf_alter_datepicker_value" ).val( '' ) ;
                    }
                } ) ;
                
                $( '.esf_datepicker' ).each( function ( ) {
                    $( this ).datepicker( {
                        altField : $( this ).next( ".esf_alter_datepicker_value" ) ,
                        altFormat : 'yy-mm-dd' ,
                        changeMonth : true ,
                        changeYear : true
                    } ) ;
                } ) ;
            }

            if ( $( '.esf_colorpicker' ).length ) {
                $( '.esf_colorpicker' ).each( function ( ) {

                    $( this ).iris( {
                        change : function ( event , ui ) {
                            $( this ).css( { backgroundColor : ui.color.toString( ) } ) ;
                        } ,
                        hide : true ,
                        border : true
                    } ) ;

                    $( this ).css( 'background-color' , $( this ).val() ) ;
                } ) ;

                $( document ).on( 'click' , function ( e ) {
                    if ( !$( e.target ).is( ".esf_colorpicker, .iris-picker, .iris-picker-inner" ) ) {
                        $( '.esf_colorpicker' ).iris( 'hide' ) ;
                    }
                } ) ;

                $( '.esf_colorpicker' ).on( 'click' , function ( e ) {
                    $( '.esf_colorpicker' ).iris( 'hide' ) ;
                    $( this ).iris( 'show' ) ;
                } ) ;
            }
        } ) ;

        $( document.body ).trigger( 'esf-enhanced-init' ) ;
    } catch ( err ) {
        window.console.log( err ) ;
    }

} ) ;