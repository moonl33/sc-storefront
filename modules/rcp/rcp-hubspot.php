<?php
function hubspot_add_contact_rcp () {
    if (is_page('edit-your-profile')) {
        //echo "<script>console.log('here');</script>";
        $referer = explode("/",$_SERVER['HTTP_REFERER']);
        
        if ($referer[count($referer)-1] != "members") {
            $referer = $referer[count($referer)-2];
        } else {
            $referer = $referer[count($referer)-1];
        }
        //echo "<script>console.log('$referer');</script>";
        
        if($referer=="members") {
            //echo "<script>console.log('here2');</script>";
        
            //GET USER DETAILS
            $userid = get_current_user_id();

            //GET THE ORDER DETAILS

            // Bail if they don't have one of these level IDs.
            $customer = rcp_get_customer_by_user_id( $userid );
            $memberships = $customer->get_memberships();

            foreach ($memberships as $membership) {
                $memtype = $membership->get_object_id();
                $memexpiration = date("M d, Y",$membership->get_expiration_time());
                $memexpiration = strtotime($memexpiration)*1000;
                
            }
            

            switch ($memtype) {
                    case "1": $sc_mem = "Individual"; break;
                    case "2": $sc_mem = "Corporate"; break;
                    case "3": $sc_mem = "Enterprise"; break;

            }
            
            //membership expiration
            echo "<script>console.log('$memexpiration');</script>";



            $iavtc_buyer_data = array(
                'firstname' => get_user_meta($userid,'first_name',true),
                'lastname' => get_user_meta($userid,'last_name',true),
                'email' => get_userdata($userid)->user_email,
                'company' => get_user_meta($userid,'sc_company',true),
                'position' => get_user_meta($userid,'sc_position',true),
                'phone' => get_user_meta($userid,'sc_phone_number',true)
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
                        'property' => 'serviceconnect_membership',
                        'value' => $sc_mem
                    ),
                    array(
                        'property' => 'membership_expiration',
                        'value' => $memexpiration
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

add_action("wp_head","hubspot_add_contact_rcp");
