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