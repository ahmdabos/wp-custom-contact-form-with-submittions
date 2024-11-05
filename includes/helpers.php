<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



function custom_contact_form_get_submissions( $search = '', $per_page = 20, $page_number = 1 ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_contact_form_submissions';

    $sql = "SELECT * FROM {$table_name}";

    if ( ! empty( $search ) ) {
        $sql .= " WHERE name LIKE '%" . esc_sql( $search ) . "%' OR email LIKE '%" . esc_sql( $search ) . "%' OR phone LIKE '%" . esc_sql( $search ) . "%' OR message LIKE '%" . esc_sql( $search ) . "%'";
    }

    // Add sorting
    if ( ! empty( $_REQUEST['orderby'] ) ) {
        $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
        $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
    }

    $sql .= " LIMIT $per_page";
    $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

    $result = $wpdb->get_results( $sql, 'OBJECT' );

    return $result;
}


function custom_contact_form_save_submission( $data ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_contact_form_submissions';

    $result = $wpdb->insert(
        $table_name,
        array(
            'name'    => sanitize_text_field( $data['name'] ),
            'email'   => sanitize_email( $data['email'] ),
            'phone'   => sanitize_text_field( $data['phone'] ),
            'message' => sanitize_textarea_field( $data['message'] ),
            'date'    => current_time( 'mysql' ),
        ),
        array( '%s', '%s', '%s', '%s', '%s' )
    );

    return $result;
}
function custom_contact_form_get_submissions_count( $search = '' ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_contact_form_submissions';

    $sql = "SELECT COUNT(*) FROM {$table_name}";

    if ( ! empty( $search ) ) {
        $sql .= " WHERE name LIKE '%" . esc_sql( $search ) . "%' OR email LIKE '%" . esc_sql( $search ) . "%' OR phone LIKE '%" . esc_sql( $search ) . "%' OR message LIKE '%" . esc_sql( $search ) . "%'";
    }

    return $wpdb->get_var( $sql );
}
