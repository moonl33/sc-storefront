<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'storefront-gutenberg-blocks','storefront-style','storefront-style','storefront-icons','storefront-woocommerce-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 30 );

// END ENQUEUE PARENT ACTION

define('IAVC_MODS', get_stylesheet_directory() . '/modules/' );

// check for  learndash then add hooks - just check for  any learndash class 
if ( class_exists( 'LearnDash_Theme_Register' ) && file_exists( IAVC_MODS . 'learndash/Iav_Elementor_Wrappers.php' ) ) :
    require_once IAVC_MODS . 'learndash/Iav_Elementor_Wrappers.php';
endif;

// stuff for ACF
if ( file_exists( IAVC_MODS . 'acf/acf.php' ) ) :
    require_once IAVC_MODS . 'acf/acf.php';
endif;

// tickera tweaks
if ( class_exists( 'TC' ) ) :
    if( file_exists( IAVC_MODS . 'tickera/tickera.php' ) ){
        require_once IAVC_MODS . 'tickera/tickera.php' ;
    }
endif;
//check if RCP exists then include if true
if ( class_exists( 'RCP_Requirements_Check' ) ) :
    if( file_exists( IAVC_MODS . 'rcp/rcp_customs.php' ) ){
        require_once IAVC_MODS . 'rcp/rcp_customs.php';
    }
    if( file_exists( IAVC_MODS . 'rcp/rcp_user_profiles.php' ) ){
        require_once IAVC_MODS . 'rcp/rcp_user_profiles.php';
    }
endif;

if ( file_exists( IAVC_MODS . 'shortcodes/Iav_Shortcodes.php' ) ) :
    require_once IAVC_MODS . 'shortcodes/Iav_Shortcodes.php';
endif;

// register custom post type for' Research Posts'
if ( file_exists( IAVC_MODS . 'post_types/research_post_type.php' ) ) :
    require_once  IAVC_MODS . 'post_types/research_post_type.php';
endif;

// dirty trick to disable rewrite if on search shortcode
// https://wordpress.stackexchange.com/questions/66273/disable-wordpress-pagination-url-rewrite-for-specific-page
function wpa66273_disable_canonical_redirect( $query ) {
    if( $_REQUEST["paged"] && $_REQUEST["keyword"] )
            remove_filter( 'template_redirect', 'redirect_canonical' ); 
    }
add_action( 'parse_query', 'wpa66273_disable_canonical_redirect' );
function iav_check_back_button(){
    if( isset($_SERVER['HTTP_REFERER']) ) {
        $url_components = parse_url( $_SERVER['HTTP_REFERER'] ); 
        parse_str($url_components['query'], $params);
        if( isset( $params['keyword'] ) &&  isset( $params['paged'] )) {        
            wp_enqueue_script ( 'iav-back-to-research',  get_stylesheet_directory_uri() . '/modules/iav-back-button.js', array( 'jquery' ), null, true );
        }
    }
}
add_action( 'init' , 'iav_check_back_button');

if ( ! function_exists( 'my_custom_add_to_cart_redirect' ) ) :
function my_custom_add_to_cart_redirect( $url ) {
	
	if ( ! isset( $_REQUEST['add-to-cart'] ) || ! is_numeric( $_REQUEST['add-to-cart'] ) ) {
		return $url;
	}
	
	$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
	
	// Only redirect the product IDs in the array to the checkout
	if ( in_array( $product_id, array( 2851 ) ) ) {
		//$url = WC()->cart->get_checkout_url();
		$url = WC()->cart->get_cart_url();
	}
	return $url;

}
add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );

endif;

// remove order again button
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );


// https://wordpress.stackexchange.com/questions/67336/how-to-log-out-without-confirmation-do-you-really-want-to-log-out/67342
add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action, $result)
{
    /**
     * Allow logout without confirmation
     */
    if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : 'url-you-want-to-redirect';
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
        header("Location: $location");
        die;
    }
}

// connection from woocommerce and tickera to hubspot
if ( file_exists( get_stylesheet_directory().'/modules/tickera/tickera-hubspot.php' ) ) {
    require ( get_stylesheet_directory().'/modules/tickera/tickera-hubspot.php');
}
// connection from woocommerce and tickera to hubspot
/*if ( file_exists( get_stylesheet_directory().'/modules/rcp/rcp-hubspot.php' ) ) {
    require ( get_stylesheet_directory().'/modules/rcp/rcp-hubspot.php');
}*/


function hubspot_add_contact_rcp ($user_id) {
    if (is_page('edit-your-profile')) {
        //echo "<script>console.log('here');</script>";
        $referer = explode("/",$_SERVER['HTTP_REFERER']);
        
        $referer = $referer[count($referer)-2];
        
        if($referer=="members") {
        
            //GET USER DETAILS
            $userid = get_current_user_id();

            //GET THE ORDER DETAILS

            // Bail if they don't have one of these level IDs.
            $customer = rcp_get_customer_by_user_id( $userid );
            $memberships = $customer->get_memberships();

            foreach ($memberships as $membership) {
               $memtype = $membership->get_object_id();
            }


            switch ($memtype) {
                    case "1": $sc_mem = "Individual"; break;
                    case "2": $sc_mem = "Corporate"; break;
                    case "3": $sc_mem = "Enterprise"; break;

            }



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
                    )
                )
            );

            $post_json = json_encode($arr);
            $hapikey = "c23a7346-4766-4e1b-abe5-7aa8b4de60a5";
            $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . $hapikey;
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
        }
    }

}

add_action("wp_head","hubspot_add_contact_rcp");
