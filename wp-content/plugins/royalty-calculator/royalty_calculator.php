<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
  Plugin Name: Royalty Calculations
  Description: Custom royalty calculation plugin
  Version: 1.0
  Author: Infobeans
 */
/* Include required files. */
require_once(plugin_dir_path(__FILE__) . 'helper.php');
require_once('royalty_calculator_plugin_installer.php');
register_activation_hook(__FILE__, 'royalty_calculator_table_installer');
require_once('remove_royalty_calculator.php');
register_uninstall_hook(__FILE__, 'remove_royalty_table_uninstaller');
register_deactivation_hook(__FILE__, 'uninstall_royalty_plugin');

/**
 * @method RoyaltyCalculator
 * @brief this method is used for create menu and submenu in admin panel.
 */
function RoyaltyCalculator() {
    global $wpdb;
    add_menu_page('GPM', 'GPM', 'manage_options', 'royalty-calculator-call-list', 'royalty-calculator-call-list', 'dashicons-screenoptions', 21);
    add_submenu_page('royalty-calculator-call-list', 'All Royalties', 'All Royalties', 'manage_options', 'royalty_calculator_list', 'royalty_calculator_list');
    add_submenu_page('royalty-calculator-call-list', 'Initial Information', 'Initial Information', 'manage_options', 'create_quarter_report', 'create_quarter_report');
    add_submenu_page('royalty-calculator-call-list', 'Pre Data', 'Pre Data', 'manage_options', 'upload_report_data', 'upload_report_data');
    add_submenu_page('royalty-calculator-call-list', 'Data Preview', 'Data Preview', 'manage_options', 'content_list', 'content_list');
    add_action('init', 'addcontent');
}

add_action('admin_menu', 'RoyaltyCalculator');

/**
 * @method remove_royalty_submenu
 * @brief This method is used for remove ad update menu from the admin panel.
 */
function remove_royalty_submenu() {
    remove_submenu_page('royalty-calculator-call-list', 'royalty-calculator-call-list');
}

add_action('admin_head', 'remove_royalty_submenu');

function royaltyScriptStyle() {
    if ((isset($_GET['page']) && $_GET['page'] == "royalty_calculator_list") || (isset($_GET['page']) && $_GET['page'] == 'content_list')) {
        wp_enqueue_style('bootstrap-min', plugins_url() . '/royalty-calculator/css/bootstrap.min.css');
        wp_enqueue_style('style-css', plugins_url() . '/royalty-calculator/css/style.css');
        wp_enqueue_script('jquery-js', plugins_url() . '/royalty-calculator/js/jquery-3.6.0.min.js');
        wp_enqueue_script('checkbox-js', plugins_url() . '/royalty-calculator/js/jquery.simple-checkbox-table.min.js');
        wp_enqueue_script('call-js', plugins_url() . '/royalty-calculator/js/custom-settings.js');
        wp_localize_script('call-js', 'royaltycallajax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    if ((isset($_GET['page']) && $_GET['page'] == "create_quarter_report") || (isset($_GET['page']) && $_GET['page'] == 'upload_report_data')) {
        wp_enqueue_script('popper-min', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', '', '', '', $in_footer = false);
        wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.13.1/jquery-ui.js', '', '', '', $in_footer = false);
        wp_enqueue_script('bootstrap-min', plugins_url() . '/royalty-calculator/js/bootstrap.min.js', '', '', '', $in_footer = false);
        wp_enqueue_style('bootstrap-min', plugins_url() . '/royalty-calculator/css/bootstrap.min.css');
        wp_enqueue_style('style-css', plugins_url() . '/royalty-calculator/css/style.css');
        wp_enqueue_script('call-js', plugins_url() . '/royalty-calculator/js/custom-settings.js', $in_footer = false);
        wp_enqueue_style('bootstrap-min', plugins_url() . '/royalty-calculator/js/bootstrap.min.js', '', '', '', $in_footer = false);
        wp_enqueue_script('popper-min', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', '', '', '', $in_footer = false);
        wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', '', '', '', $in_footer = false);
        wp_localize_script('call-js', 'royaltycallajax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}
add_action('admin_enqueue_scripts', 'royaltyScriptStyle');
