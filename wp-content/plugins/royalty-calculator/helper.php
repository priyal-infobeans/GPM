<?php
// use  PhpOffice\PhpSpreadsheet\IOFactory;

function royalty_calculator_list()
{
	global $wpdb;
	$shortcode=$wpdb->get_results("SELECT * FROM royalty_report",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/royalty_list.php');	
}

add_action('wp_ajax_nopriv_royalty_calculator_list', 'royalty_calculator_list');
add_action('wp_ajax_add_royalty_calculator_list', 'royalty_calculator_list');

function content_list()
{
	global $wpdb;
	$shortcode=$wpdb->get_results("SELECT * FROM report_mapping",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/content_list.php');	
}

add_action('wp_ajax_nopriv_content_list', 'content_list');
add_action('wp_ajax_add_content_list', 'content_list');

function create_quarter_report()
{
	global $wpdb;
	$shortcode=array();
	$data=$wpdb->get_results("SELECT * FROM royalty_report ",ARRAY_A);
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$shortcode=$wpdb->get_row("SELECT * FROM royalty_report WHERE id=".$id,ARRAY_A);

		require_once(plugin_dir_path(__FILE__) . 'includes/create_quarter_report.php');	
	}else{
		require_once(plugin_dir_path(__FILE__) . 'includes/create_quarter_report.php');
	}
	
}

add_action('wp_ajax_nopriv_create_quarter_report', 'create_quarter_report');
add_action('wp_ajax_add_create_quarter_report', 'create_quarter_report');

function addreportdata()
{
	global $wpdb;
	$return = "success";
	$quarter_report_name = isset($_POST['quarter_report_name']) ? $_POST['quarter_report_name'] : '';
	$quarter = isset($_POST['quarter']) ? $_POST['quarter'] : '';
	$quarter_year = isset($_POST['quarter_year']) ? $_POST['quarter_year'] : '';
	$id = isset($_POST['edit_id']) ? $_POST['edit_id'] : $_GET['id'];
	if(empty($id)){
		$id = 0;
	}

	$content_data = $wpdb->get_row("SELECT * FROM royalty_report WHERE id=".$id,ARRAY_A);

	if(!empty($content_data)){
		$wpdb->update('royalty_report', array(
			'quarter_report_name' => $quarter_report_name,
			'quarter'=>$quarter,
			'quarter_year' => $quarter_year,
		),array('id'=>$id));
		echo json_encode(array('status' => $return));
		wp_die();
	}else{
		$wpdb->insert('royalty_report', array(
			'quarter_report_name' => $quarter_report_name,
			'quarter'=>$quarter,
			'quarter_year' => $quarter_year,
			'status' => 'processing'
		));
		$lastId = $wpdb->insert_id;
		if ($wpdb->last_error != "") {
			$return = $wpdb->last_error;
		}
		echo json_encode(array('status' => $return));
		wp_die();
	}
	echo json_encode(array('status' => $return));
	wp_die();
}

add_action('wp_ajax_nopriv_addreportdata', 'addreportdata');
add_action('wp_ajax_addreportdata', 'addreportdata');

function delete_selected_report()
{
	global $wpdb;
	$id=$_POST['id'];
	$wpdb->delete('royalty_report',array('id'=>$id));
	echo json_encode(array('status' => "success"));
	wp_die();
}

add_action('wp_ajax_nopriv_delete_selected_report', 'delete_selected_report');
add_action('wp_ajax_delete_selected_report', 'delete_selected_report');

function upload_report_data()
{
	global $wpdb;
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		require_once(plugin_dir_path(__FILE__) . 'includes/add_content.php');	
	}else{
		$shortcode=$wpdb->get_results("SELECT * FROM royalty_report", ARRAY_A);
		require_once(plugin_dir_path(__FILE__) . 'includes/add_content.php');
	}
}

add_action('wp_ajax_nopriv_upload_report_data', 'upload_report_data');
add_action('wp_ajax_add_upload_report_data', 'upload_report_data');

function addcontentdata()
{
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	$return = "success";
	global $wpdb;
	$report_table = create_mapping_table(); // to create report mapping table.
	$report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
	$id = isset($_POST['edit_id']) ? $_POST['edit_id'] : '0';

		$vimeo = $_FILES['vimeo'];
        $sales = $_FILES['sales'];
		$price = $_FILES['price'];
		if ($vimeo['error'] === UPLOAD_ERR_OK && $sales['error'] === UPLOAD_ERR_OK ) {//&& $price['error'] === UPLOAD_ERR_OK
			$files = array($vimeo, $sales);//, $price

            foreach ($files as $file) {
				// Upload the selected files
				$movefile = uploadFileData($file);
				$uploaded_table_name = formatReport($report_id, $file['name']);
				//START
				if ( $movefile && ! isset( $movefile['error'] ) ) {
					$file_path = $movefile['file'];
						// Load PhpSpreadsheet library
						require_once (plugin_dir_path(__FILE__) . 'PhpSpreadsheet/vendor/autoload.php');
		
						// Create a table with columns from XLSX file first row
						$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
						$sheet = $spreadsheet->getActiveSheet();
						$columnNames = [];
		
						foreach ($sheet->getRowIterator(1)->current()->getCellIterator() as $cell) {
							$columnNames[] = $cell->getValue();
						}
						createTable($columnNames, $uploaded_table_name);

						// Get the data starting from the second row
						$data = [];
						foreach ($sheet->getRowIterator(2) as $row) {
							$rowData = $row->getCellIterator();
							$rowDataArray = [];

							foreach ($rowData as $cell) {
								$rowDataArray[] = $cell->getValue();
							}
							// Combine column names and row data into an associative array
							$data[] = array_combine($columnNames, $rowDataArray);
						}
						// Insert data into the table
						foreach ($data as $row) {
							insertData($row, $uploaded_table_name);
						}
						echo 'Data imported successfully.';
				}
				//END
        	}// foreach
			$mapping_data = $wpdb->get_row("SELECT * FROM report_mapping WHERE id=".$id,ARRAY_A);
			if(!empty($mapping_data)){
				$wpdb->update('report_mapping', array(
					'report_id' => $report_id,
					'upload_vimeo'=> $vimeo['name'],
					'upload_sales' => $sales['name'],
					// 'upload_price' => $price['name'],
				),array('id'=>$id));
			} else {
				$wpdb->insert('report_mapping', array(
					'report_id' => $report_id,
					'upload_vimeo'=> $vimeo['name'],
					'upload_sales' => $sales['name'],
					// 'upload_price' => $price['name'],
				));
			}
        } else {
            echo 'Error uploading files.';
        }
	echo json_encode(array('status' => $return));
	wp_die();
}

add_action('wp_ajax_nopriv_addcontentdata', 'addcontentdata');
add_action('wp_ajax_addcontentdata', 'addcontentdata');

// Function to upload files in wp uploads folder
function uploadFileData($file) {
	$upload_dir = wp_upload_dir();
	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $file, $upload_overrides );
	return $movefile;
}
// Funtion to rename the report import table
function formatReport($report_id, $file) {
	global $wpdb;
	$data = $wpdb->get_row("SELECT * FROM royalty_report WHERE id=".$report_id,ARRAY_A);
	$report_name = strtolower(str_replace(' ', '_', $data['quarter_report_name']));
	$report_quarter = $data['quarter'];
	$report_year = $data['quarter_year'];
	$file_split = explode(' ', $file);
	$rename_file = strtolower(reset($file_split));
	$format_report_name = $report_name.'_'.$report_quarter.'_'.$report_year.'_'.$rename_file;
	return $format_report_name;
}

// Function to create a table with columns from XLSX file first row
function createTable($columns, $tableName) {
    global $wpdb;

    // $tableName = $wpdb->prefix .'vimeo_content';
	// Delete table SQL
	$wpdb->query( "DROP TABLE IF EXISTS $tableName" );
    // Create table SQL
    $sql = "CREATE TABLE IF NOT EXISTS $tableName (id INT AUTO_INCREMENT PRIMARY KEY, ";

    foreach ($columns as $column) {
        $sql .= "$column VARCHAR(255), ";
    }
    $sql = rtrim($sql, ", ") . ");";
    // Execute table creation SQL 
	maybe_create_table( $wpdb->prefix . $tableName, $sql );
}
// Function to insert data into the created table
function insertData($data, $tableName) {
    global $wpdb;
    // Insert data into the table
    $wpdb->insert($tableName, $data);
}

function create_mapping_table(){
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb;
	$tablename = 'report_mapping'; 
	$main_sql_create = "CREATE TABLE IF NOT EXISTS `".$tablename."` (
		`id` int NOT NULL AUTO_INCREMENT,
		`report_id` int NOT NULL,
		`upload_vimeo` varchar(255) NOT NULL,
		`upload_sales` varchar(255) NOT NULL,
		`upload_price` varchar(255) NOT NULL,
		PRIMARY KEY (`id`)
	  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";    
	maybe_create_table( $wpdb->prefix . $tablename, $main_sql_create );
}

?>