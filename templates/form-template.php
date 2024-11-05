<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>


<div id="custom-contact-form-success" style="display: none">Thank you for your message, we will get in touch with you soon!</div>
<form id="custom-contact-form" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <?php wp_nonce_field('custom_contact_form_submit', 'custom_contact_form_nonce'); ?>
    <div class="form-group">
        <label for="custom-contact-form-name"><?php esc_html_e('Name', 'custom-contact-form'); ?></label>
        <input type="text" name="name" id="custom-contact-form-name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="custom-contact-form-email"><?php esc_html_e('Email', 'custom-contact-form'); ?></label>
        <input type="email" name="email" id="custom-contact-form-email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="custom-contact-form-phone"><?php esc_html_e('Phone', 'custom-contact-form'); ?></label>
        <input type="tel" name="phone" id="custom-contact-form-phone" class="form-control">
    </div>
    <div class="form-group">
        <label for="custom-contact-form-message"><?php esc_html_e('Message', 'custom-contact-form'); ?></label>
        <textarea name="message" id="custom-contact-form-message" class="form-control" rows="4" required></textarea>
    </div>
    <?php wp_nonce_field('custom_contact_form_submit', 'custom_contact_form_nonce'); ?>
    <input type="hidden" name="action" value="submit_contact_form">
    <button type="submit" class="btn btn-primary"><?php esc_html_e('Submit', 'custom-contact-form'); ?></button>
</form>
<div id="custom-contact-form-message" class="mt-3" style="display:none;"></div>
