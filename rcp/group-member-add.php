<?php
/**
 * Template: Add Group Member
 *
 * For modifying this template, please see: http://docs.restrictcontentpro.com/article/1738-template-files
 *
 * @package   rcp-group-accounts
 * @copyright Copyright (c) 2019, Restrict Content Pro team
 * @license   GPL2+
 * @since     1.0
 */

use function RCPGA\Shortcodes\get_dashboard;

$dashboard = get_dashboard();

global $rcp_options;
?>
<h3 class="rcp-header"><?php _e( 'Add Group Members', 'rcp-group-accounts' ); ?></h3>

<form method="post" id="rcpga-group-member-add-form" class="rcp_form">

	<fieldset>
		<div id="member-add-fields">
			<div class="member-column">

				<p id="rcpga-group-member-first-name-wrap">
					<label for="rcpga-group-member-first-name"><?php _e( 'Member First Name', 'rcp-group-accounts' ); ?></label>
					<input type="text" name="rcpga-first-name" id="rcpga-group-member-first-name" placeholder="<?php _e( 'First Name', 'rcp-group-accounts' ); ?>" autocomplete="off"/>
					<span></span>
				</p>

				<p id="rcpga-group-member-last-name-wrap">
					<label for="rcpga-group-member-last-name"><?php _e( 'Member Last Name', 'rcp-group-accounts' ); ?></label>
					<input type="text" name="rcpga-last-name" id="rcpga-group-member-last-name" placeholder="<?php _e( 'Last Name', 'rcp-group-accounts' ); ?>" autocomplete="off"/>
					<span></span>
				</p>

				<p id="rcpga-group-member-position-wrap">
					<label for="rcpga-group-member-position"><?php _e( 'Position/Title', 'rcp-group-accounts' ); ?></label>
					<input type="text" name="rcpga-position" id="rcpga-group-member-position" placeholder="<?php _e( 'Position/Title', 'rcp-group-accounts' ); ?>" autocomplete="off"/>
					<span></span>
				</p>

				<p id="rcpga-group-member-phone-wrap">
					<label for="rcpga-group-member-phone"><?php _e( 'Phone', 'rcp-group-accounts' ); ?></label>
					<input type="text" name="rcpga-phone" id="rcpga-group-member-phone" placeholder="<?php _e( 'Phone', 'rcp-group-accounts' ); ?>" autocomplete="off"/>
					<span></span>
				</p>

			</div>
			<div class="member-column">

				<p id="rcpga-group-member-email-wrap">
					<label for="rcpga-group-member-email"><?php _e( 'Member Email', 'rcp-group-accounts' ); ?></label>
					<input type="email" name="rcpga-user-email" id="rcpga-group-member-email" placeholder="<?php _e( 'Email', 'rcp-group-accounts' ); ?>" autocomplete="off"/>
				</p>

				<p id="rcpga-group-member-login-wrap">
					<label for="rcpga-group-member-login"><?php _e( 'Member Username (optional)', 'rcp-group-accounts' ); ?></label>
					<input type="text" name="rcpga-user-login" id="rcpga-group-member-login" placeholder="<?php esc_attr_e( 'Username', 'rcp-group-accounts' ); ?>" autocomplete="off"/>
					<span><?php _e( 'If left blank, the member\'s email address will be used.', 'rcp-group-accounts' ); ?></span>
				</p>

				<p id="rcpga-group-member-password-wrap">
					<label for="rcpga-group-member-password"><?php _e( 'Member Password (optional)', 'rcp-group-accounts' ); ?></label>
					<input type="password" name="rcpga-user-password" id="rcpga-group-member-password" placeholder="<?php esc_attr_e( 'Password', 'rcp-group-accounts' ); ?>" autocomplete="off"/>
					<span><?php _e( 'If left blank, the password will be randomly generated.', 'rcp-group-accounts' ); ?></span>
				</p>

			</div>
		</div>

		<?php if ( isset( $rcp_options['group_invite_email'] ) && empty( $rcp_options['disable_group_invite_email'] ) ) : ?>
			<p id="rcpga-group-member-disable-invite-wrap">
				<label for="rcpga-group-member-disable-invite">
					<input type="checkbox" name="rcpga-disable-invite-email" id="rcpga-group-member-disable-invite"/>
					<?php _e( 'Disable the group invite email and automatically add this user to the group.', 'rcp-group-accounts' ); ?>
					<?php if ( empty( $rcp_options['disable_new_user_notices'] ) ) : ?>
						<?php _e( '(If a new user is created, then the new user notification will be sent out.)', 'rcp-group-accounts' ); ?>
					<?php endif; ?>
				</label>
			</p>
		<?php endif; ?>

		<p class="rcp_submit_wrap">
			<input type="hidden" name="rcpga-group" value="<?php echo absint( $dashboard->get_group()->get_group_id() ); ?>"/>
			<input type="hidden" name="rcpga-action" value="add-member"/>
			<?php wp_nonce_field( 'rcpga_add_group_member', 'rcpga_add_group_member_nonce' ); ?>
			<input type="submit" value="<?php _e( 'Add Member', 'rcp-group-accounts' ); ?>"/>
		</p>
	</fieldset>

</form>
