<?php
// do_action('tc_order_details_table_front_after_table', $order_id, $tickets, $columns, $classes);
function iav_tc_show_event_meta($order_id, $tickets, $columns, $classes) {
    echo '<div id="checkout-event-hotel">'; //start checkout-event-hotel wrapper
    $events_array = [];
    foreach ( $tickets as $ticket ) {
        $ticket_type_id = get_post_meta( $ticket->ID , 'ticket_type_id', true );
        $ticket_type = new TC_Ticket( $ticket_type_id );
        $event_id = $ticket_type->get_ticket_event( apply_filters( 'tc_ticket_type_id' , $ticket_type_id ) );
        // check if event is in array, if not add it and display details, if it exists(displayed) then do nothing
        if (  ! empty( $event_id ) && ! in_array( $event_id , $events_array ) ) :
            $events_array[] = $event_id;
            $event = new TC_Event( $event_id );
            // check for attachment image, if not do nothing

            //old stuff maybe use this as fallback
            /* if ( wp_get_attachment_url( $event->details->hotel_featured_image )  ) :
            $event_title = $event->details->post_title;
            $event_image = wp_get_attachment_url( $event->details->hotel_featured_image ) ;
            $event_book_link = $event->details->hotel_booking_link;
            echo "
            <div class=\"hotel-info\">
            <h3>Hotel for $event_title</h3>
            <div class=\"hotel-image\">
                <img src=\" $event_image \" alt=\"$event_title Image\">
            </div>
            <div class=\"hotel-book-link\">
                <a class=\"button\" href=\"$event_book_link\" target=\"_blank\" >Book Now</a>
            </div>
            </div>
            "; 
            endif;
            */
            //end old stuff
            $elem_id = $event->details->elementor_template_id;
            if (  $elem_id  ) :
                echo do_shortcode("[elementor-template id=\"$elem_id\"]");
            endif;
            //echo '<a href="' . apply_filters('tc_email_event_permalink', get_the_permalink($event->details->ID), $event_id, $ticket_instance_id) . '">' . $event->details->post_title . '</a>';
        endif;
    }
    echo '</div>'; //end checkout-event-hotel wrapper
}
add_action( 'tc_order_details_table_front_after_table' , 'iav_tc_show_event_meta', 4, 10 );


// replace Tickets word with Reservation from orders table
add_filter( 'tc_order_details_table_front_show_tickets_header' , 'remove_tc_order_header' );
function remove_tc_order_header() {
    return false;
}
// add word Reservation on orders summary
add_action( 'tc_order_details_table_front_before_table' , 'tc_print_reservation_header' );
function tc_print_reservation_header() {
    echo '<h2>' . __('Reservation') . '</h2>';
}