<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Custom_Contact_Form
{
    private $smtp_host;
    private $smtp_port;
    private $smtp_username;
    private $smtp_password;
    private $email_template_user;
    private $email_template_admin;

    public function __construct()
    {

        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('init', array($this, 'register_shortcodes'));

        // Load the other classes
        require_once(plugin_dir_path(__FILE__) . 'class-custom-contact-form-settings.php');
        require_once(plugin_dir_path(__FILE__) . 'class-custom-contact-form-submissions.php');
        require_once(plugin_dir_path(__FILE__) . 'custom-contact-form-submissions-table.php');
        require_once(plugin_dir_path(__FILE__) . 'helpers.php');
        add_action('init', array($this, 'process_form_submission'));
        add_action('admin_menu', array($this, 'add_admin_menu'));

        new Custom_Contact_Form_Settings();
        new Custom_Contact_Form_Submissions();
    }

    public function load_textdomain()
    {
        load_plugin_textdomain('custom-contact-form', false, plugin_dir_path(__FILE__) . 'languages/');
    }

    public function register_shortcodes()
    {
        add_shortcode('custom_contact_form', array($this, 'render_form'));
    }

    public function render_form()
    {
        // Enqueue necessary scripts and styles
        wp_enqueue_style('custom-contact-form-style', plugin_dir_url(dirname(__FILE__)) . 'assets/css/style.css');
        wp_enqueue_script('custom-contact-form-script', plugin_dir_url(dirname(__FILE__)) . 'assets/js/script.js', array('jquery'), '1.0.0', true);

        // Render the form
        require_once(plugin_dir_path(dirname(__FILE__)) . 'templates/form-template.php');
    }

    public function process_form_submission()
    {
        if (!isset($_POST['custom_contact_form_nonce']) || !wp_verify_nonce($_POST['custom_contact_form_nonce'], 'custom_contact_form_submit')) {
            return;
        }

        // Gather form data
        $form_data = array(
            'name' => isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '',
            'email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : '',
            'phone' => isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '',
            'message' => isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '',
        );

        // Save the form submission
        custom_contact_form_save_submission($form_data);

        // Send emails to the user and admin
        $this->send_user_email($form_data);
        $this->send_admin_email($form_data);

        // Redirect to the same page with a success message
        wp_safe_redirect(add_query_arg('custom_contact_form_success', '1', $_SERVER['REQUEST_URI']));
        exit;
    }

    public function send_user_email($form_data)
    {

        $to = $form_data['email'];
        $subject = 'Thank you for contacting us';
        $message = $this->format_email_template($form_data, $this->email_template_user); // replace $email_template_user with the actual option name

        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        if (!empty($this->smtp_host) && !empty($this->smtp_port) && !empty($this->smtp_username) && !empty($this->smtp_password)) {
            // use SMTP settings if available
            $headers[] = 'From: ' . get_option('blogname') . ' <' . $this->smtp_username . '>';
            $smtp_args = array(
                'host' => $this->smtp_host,
                'port' => $this->smtp_port,
                'username' => $this->smtp_username,
                'password' => $this->smtp_password,
                'auth' => true,
            );
            add_filter('wp_mail_smtp_custom_options', function ($phpmailer) use ($smtp_args) {
                foreach ($smtp_args as $key => $value) {
                    $phpmailer->SMTP->{$key} = $value;
                }
                return $phpmailer;
            });
        } else {
            // use default PHP mail function
            $headers[] = 'From: ' . get_option('blogname');
        }

        wp_mail($to, $subject, $message, $headers);
    }

    public function send_admin_email($form_data)
    {
        $to = 'admin@example.com'; // Replace with your email address
        $subject = 'New contact form submission';
        $message = $this->format_email_template($form_data, $this->email_template_admin); // replace $email_template_admin with the actual option name

        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        if (!empty($this->smtp_host) && !empty($this->smtp_port) && !empty($this->smtp_username) && !empty($this->smtp_password)) {
            // use SMTP settings if available
            $headers[] = 'From: ' . get_option('blogname') . ' <' . $this->smtp_username . '>';
            $smtp_args = array(
                'host' => $this->smtp_host,
                'port' => $this->smtp_port,
                'username' => $this->smtp_username,
                'password' => $this->smtp_password,
                'auth' => true,
            );
            add_filter('wp_mail_smtp_custom_options', function ($phpmailer) use ($smtp_args) {
                foreach ($smtp_args as $key => $value) {
                    $phpmailer->SMTP->{$key} = $value;
                }
                return $phpmailer;
            });
        } else {
            // use default PHP mail function
            $headers[] = 'From: ' . get_option('blogname');
        }

        wp_mail($to, $subject, $message, $headers);
    }

    private function format_email_template($form_data, $template_option_name)
    {
        $template = get_option($template_option_name, '');
        // replace placeholders in the email template with actual form data
        $placeholders = array('{name}', '{email}', '{phone}', '{message}');
        $replacements = array($form_data['name'], $form_data['email'], $form_data['phone'], $form_data['message']);
        $formatted_template = str_replace($placeholders, $replacements, $template);

        return $formatted_template;
    }


    public function create_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_contact_form_submissions';

        if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(255) NOT NULL,
            message text NOT NULL,
            date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) {$charset_collate};";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function add_admin_menu()
    {
        add_menu_page(
            __('Custom Contact Form', 'custom-contact-form'),
            __('Custom Contact Form', 'custom-contact-form'),
            'manage_options',
            'custom-contact-form-submissions',
            '',
            'dashicons-email',
            26
        );
    }


}

new Custom_Contact_Form();
