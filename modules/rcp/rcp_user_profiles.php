<?php
//https://docs.restrictcontentpro.com/article/1720-creating-custom-registration-fields
/**
 * Adds the custom fields to the registration form and profile editor
 * Company : sc_company
 * Position/Title: sc_position
 * Phone: sc_phone_number
 */


function pw_rcp_add_user_fields() {
	
	$company = get_user_meta( get_current_user_id(), 'sc_company', true );
	$position_title   = get_user_meta( get_current_user_id(), 'sc_position', true );
	$phone_number   = get_user_meta( get_current_user_id(), 'sc_phone_number', true );

	?>
	<p>
		<label class="sc-required-field" for="sc_company"><?php _e( 'Company', 'rcp' ); ?></label>
		<input name="sc_company" id="sc_company" type="text" value="<?php echo esc_attr( $company ); ?>" required/>
	</p>
	<p>
		<label class="sc-required-field" for="sc_position"><?php _e( 'Position/Title', 'rcp' ); ?></label>
		<input name="sc_position" id="sc_position" type="text" value="<?php echo esc_attr( $position_title ); ?>"required/>
	</p>
	<p>
		<label class="sc-required-field" for="sc_phone_number"><?php _e( 'Contact Number', 'rcp' ); ?></label>
		<input name="sc_phone_number" id="sc_phone_number" type="text" value="<?php echo esc_attr( $phone_number ); ?>"required/>
	</p>
	<?php
}
add_action( 'rcp_after_password_registration_field', 'pw_rcp_add_user_fields' );
add_action( 'rcp_profile_editor_after', 'pw_rcp_add_user_fields' );


/**
 * Adds the custom fields to the member edit screen
 *
 */
function pw_rcp_add_member_edit_fields( $user_id = 0 ) {
	
	
	$company = get_user_meta( $user_id, 'sc_company', true );
	$position_title   = get_user_meta( $user_id, 'sc_position', true );
	$phone_number   = get_user_meta( $user_id, 'sc_phone_number', true );

	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="sc_company"><?php _e( 'Company', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="sc_company" id="sc_company" type="text" value="<?php echo esc_attr( $company ); ?>"/>
			<p class="description"><?php _e( 'Member\'s Company Name', 'rcp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="sc_position"><?php _e( 'Position/Title', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="sc_position" id="sc_position" type="text" value="<?php echo esc_attr( $position_title ); ?>"/>
			<p class="description"><?php _e( 'Member\'s Position/Title', 'rcp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="sc_phone_number"><?php _e( 'Phone Number', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="sc_phone_number" id="sc_phone_number" type="text" value="<?php echo esc_attr( $phone_number ); ?>"/>
			<p class="description"><?php _e( 'Member\'s Phone Number', 'rcp' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'pw_rcp_add_member_edit_fields' );

/**
 * Determines if there are problems with the registration data submitted
 * Company : sc_company
 * Position/Title: sc_position
 * Phone: sc_phone_number
 */
function pw_rcp_validate_user_fields_on_register( $posted ) {

	if ( is_user_logged_in() ) {
	   return;
    	}

	if( empty( $posted['sc_company'] ) ) {
		rcp_errors()->add( 'invalid_company', __( 'Please enter Company name', 'rcp' ), 'register' );
	}

	if( empty( $posted['sc_phone_number'] ) ) {
		rcp_errors()->add( 'invalid_phone_number', __( 'Please enter your Contact Number', 'rcp' ), 'register' );
    }
    
	if( empty( $posted['sc_position'] ) ) {
		rcp_errors()->add( 'invalid_position', __( 'Please enter your Position in the company', 'rcp' ), 'register' );
	}

}
add_action( 'rcp_form_errors', 'pw_rcp_validate_user_fields_on_register', 10 );

/**
 * Stores the information submitted during registration
 * Company : sc_company
 * Position/Title: sc_position
 * Phone: sc_phone_number
 */
function pw_rcp_save_user_fields_on_register( $posted, $user_id ) {

	if( ! empty( $posted['sc_company'] ) ) {
		update_user_meta( $user_id, 'sc_company', sanitize_text_field( $posted['sc_company'] ) );
	}

	if( ! empty( $posted['sc_position'] ) ) {
		update_user_meta( $user_id, 'sc_position', sanitize_text_field( $posted['sc_position'] ) );
    }
    
	if( ! empty( $posted['sc_phone_number'] ) ) {
		update_user_meta( $user_id, 'sc_phone_number', sanitize_text_field( $posted['sc_phone_number'] ) );
	}

}
add_action( 'rcp_form_processing', 'pw_rcp_save_user_fields_on_register', 10, 2 );

/**
 * Stores the information submitted profile update
 * Company : sc_company
 * Position/Title: sc_position
 * Phone: sc_phone_number
 */
function pw_rcp_save_user_fields_on_profile_save( $user_id ) {

	if( ! empty( $_POST['sc_company'] ) ) {
		update_user_meta( $user_id, 'sc_company', sanitize_text_field( $_POST['sc_company'] ) );
	}

	if( ! empty( $_POST['sc_position'] ) ) {
		update_user_meta( $user_id, 'sc_position', sanitize_text_field( $_POST['sc_position'] ) );
	}

	if( ! empty( $_POST['sc_phone_number'] ) ) {
		update_user_meta( $user_id, 'sc_phone_number', sanitize_text_field( $_POST['sc_phone_number'] ) );
	}

}
add_action( 'rcp_user_profile_updated', 'pw_rcp_save_user_fields_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'pw_rcp_save_user_fields_on_profile_save', 10 );
