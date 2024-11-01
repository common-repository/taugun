<?php

/*
 * Post functions
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists( 'esf_create_new_event' ) ) {

    function esf_create_new_event( $meta_args , $post_args = array() ) {

        $object = new ESF_Event() ;
        $id     = $object->create( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists( 'esf_get_event' ) ) {

    function esf_get_event( $id ) {

        $object = new ESF_Event( $id ) ;

        return $object ;
    }

}

if ( ! function_exists( 'esf_update_event' ) ) {

    function esf_update_event( $id , $meta_args , $post_args = array() ) {

        $object = new ESF_Event( $id ) ;
        $id     = $object->update( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists( 'esf_delete_event' ) ) {

    function esf_delete_event( $id , $force = true ) {

        wp_delete_post( $id , $force ) ;

        return true ;
    }

}

if ( ! function_exists( 'esf_create_new_location' ) ) {

    function esf_create_new_location( $meta_args , $post_args = array() ) {

        $object = new ESF_Location() ;
        $id     = $object->create( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists( 'esf_get_location' ) ) {

    function esf_get_location( $id ) {

        $object = new ESF_Location( $id ) ;

        return $object ;
    }

}

if ( ! function_exists( 'esf_update_location' ) ) {

    function esf_update_location( $id , $meta_args , $post_args = array() ) {

        $object = new ESF_Location( $id ) ;
        $id     = $object->update( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists( 'esf_delete_location' ) ) {

    function esf_delete_location( $id , $force = true ) {

        wp_delete_post( $id , $force ) ;

        return true ;
    }

}
if ( ! function_exists( 'esf_create_new_organizer' ) ) {

    function esf_create_new_organizer( $meta_args , $post_args = array() ) {

        $object = new ESF_Organizer() ;
        $id     = $object->create( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists( 'esf_get_organizer' ) ) {

    function esf_get_organizer( $id ) {

        $object = new ESF_Organizer( $id ) ;

        return $object ;
    }

}

if ( ! function_exists( 'esf_update_organizer' ) ) {

    function esf_update_organizer( $id , $meta_args , $post_args = array() ) {

        $object = new ESF_Organizer( $id ) ;
        $id     = $object->update( $meta_args , $post_args ) ;

        return $id ;
    }

}

if ( ! function_exists( 'esf_delete_organizer' ) ) {

    function esf_delete_organizer( $id , $force = true ) {

        wp_delete_post( $id , $force ) ;

        return true ;
    }

}


