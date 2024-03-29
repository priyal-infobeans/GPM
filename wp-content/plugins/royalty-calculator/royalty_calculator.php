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
 * @method royalty_calculator
 * @brief this method is used for create menu and submenu in admin panel.
 */
function royalty_calculator() {
    global $wpdb;
    $val = $wpdb->get_results("SELECT * FROM royalty_report", ARRAY_A);

    add_menu_page('GPM', 'GPM', 'manage_options', 'royalty-calculator-call-list', 'royalty-calculator-call-list', 'dashicons-screenoptions', 21);
    add_submenu_page('royalty-calculator-call-list', 'All Royalties', 'All Royalties', 'manage_options', 'royalty_calculator_list', 'royalty_calculator_list');
    add_submenu_page('royalty-calculator-call-list', 'Initial Information', 'Initial Information', 'manage_options', 'create_quarter_report', 'create_quarter_report');
    if($val !== FALSE && !empty($val))
    {
        add_submenu_page('royalty-calculator-call-list', 'Pre Data', 'Pre Data', 'manage_options', 'upload_report_data', 'upload_report_data');
    }
    if (isset($_GET['preview_id']) || (isset($_GET['page']) && $_GET['page'] == 'content_list')) {
        add_submenu_page('royalty-calculator-call-list', 'Data Information', 'Data Information', 'manage_options', 'content_list', 'content_list');
    }
    if (isset($_GET['report_id'])) {
        add_submenu_page('royalty-calculator-call-list', 'Export & Share', 'Export & Share', 'manage_options', 'file_export', 'file_export');
    }
    if (isset($_GET['preview_id']) && (isset($_GET['page']) && $_GET['page'] == 'logs_request')) {
        add_submenu_page('royalty-calculator-call-list', 'View Logs', 'View Logs', 'manage_options', 'logs_request', 'view_change_logs');
    }
    if ((isset($_GET['page']) && $_GET['page'] == 'data_calculation') && isset($_GET['preview_id'])) {
        add_submenu_page('royalty-calculator-call-list', 'Data Calculation', 'Data Calculation', 'manage_options', 'data_calculation', 'data_calculation');
    }
    
    add_action('init', 'addcontent');
}

add_action('admin_menu', 'royalty_calculator');

/**
 * @method remove_royalty_submenu
 * @brief This method is used for remove ad update menu from the admin panel.
 */
function remove_royalty_submenu() {
    remove_submenu_page('royalty-calculator-call-list', 'royalty-calculator-call-list');
}

add_action('admin_head', 'remove_royalty_submenu');

/**
 * @method royalty_script_style
 * @brief this method is used to load necessary scripts and styles in plugin pages.
 */
function royalty_script_style() {
    if ((isset($_GET['page']) && $_GET['page'] == "royalty_calculator_list") || (isset($_GET['page']) && $_GET['page'] == 'content_list') || (isset($_GET['page']) && $_GET['page'] == 'file_export')
    || (isset($_GET['page']) && $_GET['page'] == 'logs_request') || (isset($_GET['page']) && $_GET['page'] == 'data_calculation')) {
        wp_enqueue_style('bootstrap-min', plugins_url() . '/royalty-calculator/css/bootstrap.min.css');
        wp_enqueue_style('style-css', plugins_url() . '/royalty-calculator/css/style.css');
        wp_enqueue_script('jquery-js', plugins_url() . '/royalty-calculator/js/jquery-3.6.0.min.js');
        wp_enqueue_script('call-js', plugins_url() . '/royalty-calculator/js/custom-settings.js');
        wp_localize_script('call-js', 'royaltycallajax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    if ((isset($_GET['page']) && $_GET['page'] == "create_quarter_report") || (isset($_GET['page']) && $_GET['page'] == 'upload_report_data')) {
        wp_enqueue_script('jquery-ui', plugins_url() . '/royalty-calculator/js/jquery-ui.js', '', '', '', $in_footer = false);
        wp_enqueue_script('bootstrap-min', plugins_url() . '/royalty-calculator/js/bootstrap.min.js', '', '', '', $in_footer = false);
        wp_enqueue_style('bootstrap-min', plugins_url() . '/royalty-calculator/css/bootstrap.min.css');
        wp_enqueue_style('style-css', plugins_url() . '/royalty-calculator/css/style.css');
        wp_enqueue_script('call-js', plugins_url() . '/royalty-calculator/js/custom-settings.js', $in_footer = false);
        wp_localize_script('call-js', 'royaltycallajax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}
add_action('admin_enqueue_scripts', 'royalty_script_style');
