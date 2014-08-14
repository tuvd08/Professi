<?php

/**
 * Add Commissions link
 * *
 * @access      private
 * @since       1.0
 * @return      void
*/

function eddc_add_commissions_link() {
	global $edd_commissions_page;

	$edd_commissions_page = add_submenu_page('edit.php?post_type=download', __('Easy Digital Download Commissions', 'eddc'), __('Commissions', 'eddc'), 'manage_options', 'edd-commissions', 'edd_commissions_page');
}
add_action('admin_menu', 'eddc_add_commissions_link', 10);


function edd_commissions_page() {

    $js_dir = EDD_PLUGIN_URL . 'assets/js/';
    $css_dir = EDD_PLUGIN_URL . 'assets/css/';

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    wp_enqueue_script( 'jquery-ui-datepicker' );
    $ui_style = ( 'classic' == get_user_option( 'admin_color' ) ) ? 'classic' : 'fresh';
    wp_enqueue_style( 'jquery-ui-css', $css_dir . 'jquery-ui-' . $ui_style . $suffix . '.css' );

    ?>
    <div class="wrap">

        <div id="icon-edit" class="icon32"><br/></div>
        <h2><?php _e('Easy Digital Download Commissions', 'eddc'); ?></h2>

        <?php

        if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
            include( EDDC_PLUGIN_DIR . 'includes/edit-commission.php' );
        } else {

            $commissions_table = new EDD_C_List_Table();

            //Fetch, prepare, sort, and filter our data...
            $commissions_table->prepare_items();

            $user_id = isset( $_GET['user'] ) ? absint( $_GET['user'] ) : 0;

            $total_unpaid = edd_currency_filter( edd_format_amount( eddc_get_unpaid_totals( $user_id ) ) );

            ?>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#commission-payouts').submit(function() {
                        if( confirm( "<?php _e('Generating a payout file will mark all unpaid commissions as paid. Do you want to continue?', 'eddc'); ?>" ) ) {
                            return true;
                        }
                        return false;
                    });
                    if ($('.edd_datepicker').length > 0) {
                        var dateFormat = 'mm/dd/yy';
                        $('.edd_datepicker').datepicker({
                            dateFormat: dateFormat
                        });
                    }
                });
            </script>
            <form id="commission-payouts" method="get" style="float:right;margin:0;">
                <input type="text" name="from" class="edd_datepicker" placeholder="<?php _e( 'From - mm/dd/yyyy', 'eddc' ); ?>"/>
                <input type="text" name="to" class="edd_datepicker" placeholder="<?php _e( 'To - mm/dd/yyyy', 'eddc' ); ?>"/>
                <input type="hidden" name="post_type" value="download" />
                <input type="hidden" name="page" value="edd-commissions" />
                <input type="hidden" name="edd_action" value="generate_payouts" />
                <?php echo wp_nonce_field( 'eddc-payout-nonce', 'eddc-payout-nonce' ); ?>
                <?php echo submit_button( __('Generate Mass Payment File', 'eddc'), 'secondary', '', false ); ?>
            </form>

            <form id="commissions-filter" method="get">

                <input type="hidden" name="post_type" value="download" />
                <input type="hidden" name="page" value="edd-commissions" />
                <!-- Now we can render the completed list table -->
                <?php $commissions_table->views() ?>

                <?php $commissions_table->display() ?>
            </form>

            <div class="commission-totals">
                <?php _e('Total Unpaid:', 'eddc'); ?>&nbsp;<strong><?php echo $total_unpaid; ?></strong>
            </div>
           <?php
        }
        ?>
    </div>
    <?php

}