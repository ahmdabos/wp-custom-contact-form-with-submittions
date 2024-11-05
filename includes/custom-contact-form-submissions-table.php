<?php

// Add this code within the Custom_Contact_Form_Submissions_Table class
class Custom_Contact_Form_Submissions_Table extends WP_List_Table
{

    public function __construct()
    {
        parent::__construct(array(
            'singular' => __('Submission', 'custom-contact-form'),
            'plural' => __('Submissions', 'custom-contact-form'),
            'ajax' => false
        ));
    }

    // Define methods here


    public function get_columns()
    {
        $columns = array(
            'name' => __('Name', 'custom-contact-form'),
            'email' => __('Email', 'custom-contact-form'),
            'phone' => __('Phone', 'custom-contact-form'),
            'message' => __('Message', 'custom-contact-form'),
            'date' => __('Date', 'custom-contact-form'),
        );

        return $columns;
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page = 20;
        $current_page = $this->get_pagenum();

        $search = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';

        $data = custom_contact_form_get_submissions($search, $per_page, $current_page);

        $total_items = custom_contact_form_get_submissions_count($search);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));

        $this->items = $data;
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'name':
            case 'email':
            case 'phone':
            case 'message':
            case 'date':
                return $item->$column_name;
            default:
                return print_r($item, true);
        }
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', false),
            'email' => array('email', false),
            'phone' => array('phone', false),
            'date' => array('date', false),
        );

        return $sortable_columns;
    }
}