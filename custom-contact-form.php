<?php
/*
Plugin Name: Custom Contact Form
Description: A custom contact form plugin with admin listing and export features.
Version: 1.1
Author: Ahmed Abbous
Author URI: https://ahmedabbous.com
Text Domain: custom-contact-form
*/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


define( 'CUSTOM_CONTACT_FORM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CUSTOM_CONTACT_FORM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once CUSTOM_CONTACT_FORM_PLUGIN_DIR . 'includes/class-custom-contact-form.php';
require_once CUSTOM_CONTACT_FORM_PLUGIN_DIR . 'includes/class-custom-contact-form-settings.php';
require_once CUSTOM_CONTACT_FORM_PLUGIN_DIR . 'includes/class-custom-contact-form-submissions.php';
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
function custom_contact_form_activate() {
    $plugin = new Custom_Contact_Form();
    $plugin->create_table();
}
register_activation_hook( __FILE__, 'custom_contact_form_activate' );




