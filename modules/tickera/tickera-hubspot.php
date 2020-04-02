<?php
function hubspot_add_contact_wc ($order_id) { 
    //GET THE ORDER DETAILS
    
    //get the product ID
    $order = wc_get_order( $order_id );

    //get the order
    $order_items = $order->get_items();
    
    foreach ( $order_items as $item ) {
        $prodid = $item->get_product_id();
        
        //proceed ONLY for these 2 products
        if ($prodid == 2914 || $prodid == 3103) {
            if ($prodid == 2914) {
                $tickettype = "Individual";
            } else if ($prodid==3103) {
                $tickettype = "Team";
            }

            //get tickera details of ticket associated with the order

            $tcorder = new TC_Order($order_id);
            $order_attendees = TC_Orders::get_tickets_ids($tcorder->details->ID);

            foreach ($order_attendees as $order_attendee_id) {
                $iavtc_buyer_data = array(
                    'firstname' => get_post_meta($order_attendee_id, 'first_name', true),
                    'lastname' => get_post_meta($order_attendee_id, 'last_name', true),
                    'email' => get_post_meta($order_attendee_id, 'tc_ff_email_tcfn_1795', true),
                    'company' => get_post_meta($order_attendee_id, 'tc_ff_company_tcfn_3205', true),
                    'position' => get_post_meta($order_attendee_id, 'tc_ff_positiontitle_tcfn_1177', true),
                    'phone' => get_post_meta($order_attendee_id, 'tc_ff_phonenumber_tcfn_166', true)
                );


                //CONNECT TO HUBSPOT AND SEND THE CUSTOMER DETAILS
                $arr = array(
                    'properties' => array(
                        array(
                            'property' => 'firstname',
                            'value' => $iavtc_buyer_data['firstname']
                        ),
                        array(
                            'property' => 'lastname',
                            'value' => $iavtc_buyer_data['lastname']
                        ),
                        array(
                            'property' => 'company',
                            'value' => $iavtc_buyer_data['company']
                        ),
                        array(
                            'property' => 'jobtitle',
                            'value' => $iavtc_buyer_data['position']
                        ),
                        array(
                            'property' => 'email',
                            'value' => $iavtc_buyer_data['email']
                        ),
                        array(
                            'property' => 'phone',
                            'value' => $iavtc_buyer_data['phone']
                        ),
                        array(
                            'property' => 'n2020_symposium_ticket_type',
                            'value' => $tickettype
                        )
                    )
                );

                $post_json = json_encode($arr);
                $hapikey = "c23a7346-4766-4e1b-abe5-7aa8b4de60a5";
                $endpoint = 'https://api.hubapi.com/contacts/v1/contact/createOrUpdate/email/'.$iavtc_buyer_data['email'].'/?hapikey=' . $hapikey;
                $ch = @curl_init();
                @curl_setopt($ch, CURLOPT_POST, true);
                @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
                @curl_setopt($ch, CURLOPT_URL, $endpoint);
                @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = @curl_exec($ch);
                $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_errors = curl_error($ch);
                @curl_close($ch);
                //echo "curl Errors: " . $curl_errors;
                //echo "\nStatus code: " . $status_code;
                //echo "\nResponse: " . $response;
                //echo "<script>console.log('triggered');</script>";          


            }

        }                    
        
    }
        
}

add_action("woocommerce_payment_complete","hubspot_add_contact_wc");