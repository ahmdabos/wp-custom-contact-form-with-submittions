<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the current settings values
$smtp_host = get_option('custom_contact_form_smtp_host', '');
$smtp_port = get_option('custom_contact_form_smtp_port', '');
$smtp_username = get_option('custom_contact_form_smtp_username', '');
$smtp_password = get_option('custom_contact_form_smtp_password', '');
$email_template_user = get_option('custom_contact_form_email_template_user', '');
$email_template_admin = get_option('custom_contact_form_email_template_admin', '');
?>

<div class="wrap">
    <h1><?php esc_html_e('Custom Contact Form Settings', 'custom-contact-form'); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields('custom-contact-form-settings'); ?>
        <?php do_settings_sections('custom-contact-form-settings'); ?>

        <h2><?php esc_html_e('SMTP Settings', 'custom-contact-form'); ?></h2>
        <table class="form-table">
            <tr>
                <th><label for="smtp_host"><?php esc_html_e('SMTP Host', 'custom-contact-form'); ?></label></th>
                <td><input type="text" name="custom_contact_form_smtp_host" id="smtp_host" value="<?php echo esc_attr($smtp_host); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="smtp_port"><?php esc_html_e('SMTP Port', 'custom-contact-form'); ?></label></th>
                <td><input type="number" name="custom_contact_form_smtp_port" id="smtp_port" value="<?php echo esc_attr($smtp_port); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="smtp_username"><?php esc_html_e('SMTP Username', 'custom-contact-form'); ?></label></th>
                <td><input type="text" name="custom_contact_form_smtp_username" id="smtp_username" value="<?php echo esc_attr($smtp_username); ?>"
                           class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="smtp_password"><?php esc_html_e('SMTP Password', 'custom-contact-form'); ?></label></th>
                <td><input type="password" name="custom_contact_form_smtp_password" id="smtp_password" value="<?php echo esc_attr($smtp_password); ?>"
                           class="regular-text"></td>
            </tr>
        </table>

        <h2><?php esc_html_e('Email Templates', 'custom-contact-form'); ?></h2>
        <table class="form-table">
            <tr>
                <th><label for="email_template_user"><?php esc_html_e('User Email Template', 'custom-contact-form'); ?></label></th>
                <td><textarea name="custom_contact_form_email_template_user" id="email_template_user" rows="5"
                              class="large-text"><?php echo esc_textarea($email_template_user); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="email_template_admin"><?php esc_html_e('Admin Email Template', 'custom-contact-form'); ?></label></th>
                <td><textarea name="custom_contact_form_email_template_admin" id="email_template_admin" rows="5"
                              class="large-text"><?php echo esc_textarea($email_template_admin); ?></textarea></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
