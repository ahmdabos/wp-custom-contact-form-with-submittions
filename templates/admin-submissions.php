<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


?>
<div class="wrap">
    <h1><?php esc_html_e( 'Custom Contact Form Submissions', 'custom-contact-form' ); ?></h1>
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=custom-contact-form-submissions&action=export' ), 'export_submissions' ); ?>" class="button button-primary"><?php _e( 'Export to Excel', 'custom-contact-form' ); ?></a>

        </div>
        <br class="clear">
    </div>
    <?php
    $submissions_table = new Custom_Contact_Form_Submissions_Table();
    $submissions_table->prepare_items();
    ?>
    <form method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
        <?php $submissions_table->search_box( __( 'Search', 'custom-contact-form' ), 'search' ); ?>
        <?php $submissions_table->display(); ?>
    </form>
</div>
