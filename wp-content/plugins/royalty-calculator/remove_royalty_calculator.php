<?php
/**
 * @method remove_royalty_table_uninstaller
 * @brief This function is used to delete tables if tables are not exists into DB whenever plugin get activated.
 * @global $wpdb
 * @global $currentDbName
 */
function uninstall_royalty_plugin()
{
  remove_menu_page('GPM', 'GPM', 'manage_options', 'royalty-calculator-call-list', 'royalty_calculator_list', 'dashicons-screenoptions', 9);
}
function remove_royalty_table_uninstaller()
{
  global $wpdb;
  $x=$sites_arr['blog_id'];
  $shortcode_generator='royalty_report';
  $sql = "DROP TABLE IF EXISTS ".$shortcode_generator;
  $wpdb->query($sql);
  delete_option("my_plugin_db_version");
}
