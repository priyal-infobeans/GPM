<?php
$GLOBALS['site_id'] = get_current_blog_id();
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
/**
 * @method royalty_calculator_table_installer
 * @brief This function is used to create tables if tables are not exists into DB whenever plugin get activated.
 * @global $wpdb
 */
function royalty_calculator_table_installer()
{
  global $wpdb;
  $databasename = $wpdb->dbname;
  $currentDbName = $wpdb->prefix . $databasename;
  global $currentDbName;
  // if ($wpdb->get_var("show tables like '$currentDbName'") != $currentDbName) {
  $royalty_report="CREATE TABLE `royalty_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quarter_report_name` varchar(300) DEFAULT NULL,
  `quarter` varchar(255) DEFAULT NULL,
  `quarter_year` varchar(255) DEFAULT NULL,
  `status` ENUM('completed','processing','cancelled') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
  dbDelta($royalty_report);
// }
}
