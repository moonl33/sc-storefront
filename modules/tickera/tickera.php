<?php
// do_action('tc_order_details_table_front_after_table', $order_id, $tickets, $columns, $classes);
function iav_tc_show_event_meta($order_id, $tickets, $columns, $classes) {
    echo '<div id="checkout-event-hotel">'; //start checkout-event-hotel wrapper
    $events_array = [];
    foreach ($tickets as $ticket) {
        $ticket_type_id = get_post_meta( $ticket->ID , 'ticket_type_id', true );
        $ticket_type = new TC_Ticket( $ticket_type_id );
        $event_id = $ticket_type->get_ticket_event(apply_filters('tc_ticket_type_id', $ticket_type_id));
        // check if event is in array, if not add it and display details, if it exists(displayed) then do nothing
        if( ! empty( $event_id ) && ! in_array( $event_id , $events_array ) ) :
            $events_array[] = $event_id;
            $event = new TC_Event($event_id);
            // check for attachment image, if not do nothing
            if ( wp_get_attachment_url( $event->details->hotel_featured_image )  ) :
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
                <a href=\"$event_book_link\">Book Now</a>
            </div>
            </div>
            ";

            endif;
            //echo '<a href="' . apply_filters('tc_email_event_permalink', get_the_permalink($event->details->ID), $event_id, $ticket_instance_id) . '">' . $event->details->post_title . '</a>';
        endif;
    }
    echo '</div>'; //end checkout-event-hotel wrapper
}
add_action( 'tc_order_details_table_front_after_table' , 'iav_tc_show_event_meta', 4, 10 );