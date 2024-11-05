<?php
class Custom_Contact_Form_Settings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_settings_menu() {
        add_submenu_page(
            'custom-contact-form-submissions',
            __( 'Custom Contact Form Settings', 'custom-contact-form' ),
            __( 'Settings', 'custom-contact-form' ),
            'manage_options',
            'custom-contact-form-settings',
            array( $this, 'render_settings_page' )
        );



    }



    public function register_settings() {
        register_setting( 'custom-contact-form-settings', 'custom_contact_form_smtp_host' );
        register_setting( 'custom-contact-form-settings', 'custom_contact_form_smtp_port' );
        register_setting( 'custom-contact-form-settings', 'custom_contact_form_smtp_username' );
        register_setting( 'custom-contact-form-settings', 'custom_contact_form_smtp_password' );
        register_setting( 'custom-contact-form-settings', 'custom_contact_form_email_template_user' );
        register_setting( 'custom-contact-form-settings', 'custom_contact_form_email_template_admin' );
    }

    public function render_settings_page() {
        // Render the settings page
        require_once( plugin_dir_path( __FILE__ ) . '../templates/admin-settings.php' );
    }
}
