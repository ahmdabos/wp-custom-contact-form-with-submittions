<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_Contact_Form_Submissions
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_submissions_menu'));
        add_action('admin_init', array($this, 'export_to_excel'));
    }

    public function add_submissions_menu(): void
    {
        add_submenu_page(
            'custom-contact-form',
            __('Custom Contact Form Submissions', 'custom-contact-form'),
            __('Submissions', 'custom-contact-form'),
            'manage_options',
            'custom-contact-form-submissions',
            array($this, 'render_submissions_page')
        );
    }

    public function render_submissions_page(): void
    {
        // Render the submission page
        require_once(plugin_dir_path(__FILE__) . '../templates/admin-submissions.php');
    }

    public function export_to_excel()
    {

        if (!isset($_GET['action']) || $_GET['action'] != 'export') {
            return;
        }

        // Check for user capability
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check for nonce
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'export_submissions')) {
            return;
        }

        // Fetch submissions from the database
        $submissions = custom_contact_form_get_submissions();

        // Create a new Excel file
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Phone');
        $sheet->setCellValue('D1', 'Message');
        $sheet->setCellValue('E1', 'Date');

        // Add submissions data
        $row = 2;
        foreach ($submissions as $submission) {
            $sheet->setCellValue('A' . $row, $submission->name);
            $sheet->setCellValue('B' . $row, $submission->email);
            $sheet->setCellValue('C' . $row, $submission->phone);
            $sheet->setCellValue('D' . $row, $submission->message);
            $sheet->setCellValue('E' . $row, $submission->date);
            $row++;
        }

        // Prepare the Excel file for download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="submissions-' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
