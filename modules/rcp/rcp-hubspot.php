<?php
function hubspot_add_contact_rcp () {

    file_put_contents("hookfired.txt","hook fired");
    
}


add_action("rcp_successful_registration","hubspot_add_contact_rcp");
