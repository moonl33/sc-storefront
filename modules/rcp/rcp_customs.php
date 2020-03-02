<?php
// change 1 year to billed annually 
if ( ! function_exists( 'sc_rcp_filter_duration_unit' ) ) :
    function sc_rcp_filter_duration_unit( $unit, $length ) {
        $new_unit = '';
        switch ( $unit ) :
            case 'day' :
                if( $length > 1 )
                    $new_unit = __( 'Days', 'rcp' );
                else
                    $new_unit = __( 'Billed Daily', 'rcp' );
            break;
            case 'month' :
                if( $length > 1 )
                    $new_unit = __( 'Months', 'rcp' );
                else
                    $new_unit = __( 'Billed Monthly', 'rcp' );
            break;
            case 'year' :
                if( $length > 1 )
                    $new_unit = __( 'Years', 'rcp' );
                else
                    $new_unit = __( 'Billed Annually', 'rcp' );
            break;
        endswitch;
        return $new_unit;
    }
endif;

// save user_meta for user's that are invited
if ( ! function_exists( 'sc_more_invite_fields' ) ) :
    add_action( 'rcpga_add_member_to_group_after', 'sc_more_invite_fields', 10, 2 );
    function sc_more_invite_fields( $user_id, $args ){

        if( ! empty( $_REQUEST["rcpga-position"] ) ) {
            update_user_meta( $user_id, 'sc_position', sanitize_text_field( $_REQUEST["rcpga-position"] ) );
        }
        
        if( ! empty( $_REQUEST["rcpga-phone"] ) ) {
            update_user_meta( $user_id, 'sc_phone_number', sanitize_text_field( $_REQUEST["rcpga-phone"] ) );
        }
    }
endif;

//add custom email templates
function ag_rcp_email_templates( $templates ) {
    $templates['sc-custom-template'] = __( 'SC Custom Template' );

    return $templates;
}

add_filter( 'rcp_email_templates', 'ag_rcp_email_templates' );