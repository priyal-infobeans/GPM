<?php

function royalty_calculator_list()
{
	global $wpdb;
	$shortcode=$wpdb->get_results("SELECT * FROM royalty_report",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/royalty_list.php');	
}

add_action('wp_ajax_nopriv_royalty_calculator_list', 'royalty_calculator_list');
add_action('wp_ajax_royalty_calculator_list', 'royalty_calculator_list');

function content_list()
{
	global $wpdb;
	$report_id = isset($_GET['preview_id'])?  $_GET['preview_id'] :'';
	$shortcode=$wpdb->get_results("SELECT * FROM report_mapping where report_id=$report_id",ARRAY_A);
	$table_name = $shortcode[0]['report_name'];
	$export_record = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/content_list.php');
		
}
add_action('wp_ajax_nopriv_content_list', 'content_list');
add_action('wp_ajax_content_list', 'content_list');

function preview_list()
{
	global $wpdb;
	$report_id = isset($_POST['report_id'])? $_POST['report_id'] : $_GET['preview_id'];
	$shortcode=$wpdb->get_results("SELECT * FROM report_mapping where report_id=$report_id",ARRAY_A);
	$file_type = isset($_POST['file_type'])? $_POST['file_type'] :'';
	$btn_value = isset($_POST['btn_value'])? $_POST['btn_value'] :'';
	$table_name = formatReport($report_id, $file_type);
	$export_record = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/content_list.php');
	json_encode(array('status' => "success",'result'=>$export_record));
	wp_die();
}
add_action('wp_ajax_nopriv_preview_list', 'preview_list');
add_action('wp_ajax_preview_list', 'preview_list');

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
add_action('wp_ajax_create_quarter_report', 'create_quarter_report');

function add_report_data()
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

add_action('wp_ajax_nopriv_add_report_data', 'add_report_data');
add_action('wp_ajax_add_report_data', 'add_report_data');

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
		$mapping = $wpdb->get_results("SELECT * FROM report_mapping",ARRAY_A);
		require_once(plugin_dir_path(__FILE__) . 'includes/add_content.php');	
	}else{
		$shortcode=$wpdb->get_results("SELECT * FROM royalty_report", ARRAY_A);
		$mapping = $wpdb->get_results("SELECT * FROM report_mapping",ARRAY_A);
		require_once(plugin_dir_path(__FILE__) . 'includes/add_content.php');
	}
}

add_action('wp_ajax_nopriv_upload_report_data', 'upload_report_data');
add_action('wp_ajax_upload_report_data', 'upload_report_data');

// Enqueue necessary scripts and styles
function enqueue_custom_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts');

function mappingData($report_id, $file, $uploaded_table_name) {
	global $wpdb;

	$id = isset($_POST['edit_id']) ? $_POST['edit_id'] : 0;
	$mapping_data = $wpdb->get_row("SELECT * FROM report_mapping WHERE report_id=".$report_id." and report_name='".$uploaded_table_name."'",ARRAY_A);
	if(!empty($mapping_data)){
		$wpdb->update('report_mapping', array(
			'report_id' => $report_id,
			'report_name' => $uploaded_table_name,
		),array('id'=>$id));
	} else {
		$wpdb->insert('report_mapping', array(
			'report_id' => $report_id,
			'report_name' => $uploaded_table_name,
		));
	}
	return;
}

function add_content_data() {
	global $wpdb;
	$report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
	$id = isset($_POST['edit_id']) ? $_POST['edit_id'] : 0;
	$vimeo = $_FILES['vimeo']['name'];
	$sales = $_FILES['sales']['name'];
	$price = $_FILES['price']['name'];
	$mapping_data = $wpdb->get_row("SELECT * FROM report_mapping WHERE report_id=".$report_id,ARRAY_A);
	if(!empty($mapping_data)){
		$wpdb->update('report_mapping', array(
			'report_id' => $report_id,
			'upload_vimeo' => $vimeo,
			'upload_sales' => $sales,
			'upload_price' => $price
		),array('report_id'=>$report_id));
	} else {
		$wpdb->insert('report_mapping', array(
			'report_id' => $report_id,
			'upload_vimeo' => $vimeo,
			'upload_sales' => $sales,
			'upload_price' => $price
		));
	}
	echo json_encode(array('status' => 'success', 'preview' => $report_id));
	wp_die();
}
add_action('wp_ajax_nopriv_add_content_data', 'add_content_data');
add_action('wp_ajax_add_content_data', 'add_content_data');

// Handle AJAX request for parsing and importing files
function handle_ajax_xlsx_submission() {
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
    $file = $_FILES['file'];
	$report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
	// Upload the selected files
	$movefile = uploadFileData($file);
	$uploaded_table_name = formatReport($report_id, $file['name']);
    if ($file['error'] === UPLOAD_ERR_OK) {
		$filePath = $movefile['file'];

        // Load PhpSpreadsheet library
		require_once (plugin_dir_path(__FILE__) . 'PhpSpreadsheet/vendor/autoload.php');

        // Create a table with columns from XLSX file first row
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
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
		mappingData($report_id, $file, $uploaded_table_name);
		echo '<input type="hidden" name="report_name[]" id="report_name" value="'.$uploaded_table_name.'">';
        echo 'Data from ' . $file['name'] . ' imported successfully.<br>';
    } else {
        echo 'Error uploading file.';
    }
    wp_die();
}

add_action('wp_ajax_handle_ajax_xlsx_submission', 'handle_ajax_xlsx_submission');
add_action('wp_ajax_nopriv_handle_ajax_xlsx_submission', 'handle_ajax_xlsx_submission'); // Allow non-logged-in users to access the AJAX endpoint

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

// Function to create mapping table
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
		`report_name` varchar(255) NOT NULL,
		PRIMARY KEY (`id`)
	  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";    
	maybe_create_table( $wpdb->prefix . $tablename, $main_sql_create );
}

function update_report_logs()
{
	global $wpdb;
	$report_id = isset($_POST['report_id']) ? $_POST['report_id']:'';
	$report_name = isset($_POST['report_name']) ? $_POST['report_name']:'';
	$plays = isset($_POST['data'][1]) ? $_POST['data'][1] : '';
	$loads = isset($_POST['data'][2]) ? $_POST['data'][2] : '';
	$name = isset($_POST['data'][3]) ? $_POST['data'][3] : '';
	$viewers = isset($_POST['data'][4]) ? $_POST['data'][4] : '';

	// Create an array with the strings
	$dataArray = array(
		'plays' => trim($plays),
		'loads' => trim($loads),
		'name' => trim($name),
		'viewers' => trim($viewers)
	);
	$columnNames = ['report_id', 'report_name', 'change_log'];
	$table_name = $report_name.'_logs';
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (id INT AUTO_INCREMENT PRIMARY KEY, ";

	foreach ($columnNames as $columnName) {
		// Use VARCHAR for all columns except 'change_log', which is JSON
		$dataType = ($columnName === 'change_log') ? 'JSON' : 'VARCHAR(255)';
		// echo "$columnName $dataType,\n";
		$sql .= "$columnName $dataType, ";
	}
	$sql = rtrim($sql, ", ") . "
	,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";
    // Execute table creation SQL 
	maybe_create_table( $table_name, $sql );
	$wpdb->insert(
		$table_name,
		array(
			'report_id' => $report_id,
			'report_name' => $report_name,
			'change_log' => json_encode($dataArray)
		)
	);
	wp_die();
}
add_action('wp_ajax_nopriv_update_report_logs', 'update_report_logs');
add_action('wp_ajax_update_report_logs', 'update_report_logs');

function file_export() {
	global $wpdb;
	require_once(plugin_dir_path(__FILE__) . 'includes/file_export.php');
}

add_action('wp_ajax_file_export', 'file_export');

function export_share_report() {
	global $wpdb;
	// Load PhpSpreadsheet library
	require_once (plugin_dir_path(__FILE__) . 'PhpSpreadsheet/vendor/autoload.php');
	$report_name = isset($_POST['report_name'])? $_POST['report_name'] : '';
	$report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
	$data = $wpdb->get_results("SELECT * FROM $report_name",ARRAY_A);

	// Create a new PhpSpreadsheet instance
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Add headers
    $headers = array_keys($data[0]);
    $columnIndex = 1;
    foreach ($headers as $header) {
        $sheet->setCellValueByColumnAndRow($columnIndex, 1, $header);
        $columnIndex++;
    }
    // Add data
    $rowIndex = 2;
    foreach ($data as $row) {
        $columnIndex = 1;
        foreach ($row as $value) {
            $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
            $columnIndex++;
        }
        $rowIndex++;
    }
    // Save Excel file
    $filename = 'excel_export_' . date('YmdHis') . '.xlsx';
	$filepath = plugin_dir_path(__FILE__) . '/reports/'.$filename;

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($filepath);

	// Output file content for download
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// 	header("Content-type: application/vnd-ms-excel");
//     header('Content-Disposition: attachment;filename="' . $filename . '"');
//     // header('Cache-Control: max-age=0');
// 	header("Pragma: no-cache");
// header("Expires: 0");


// header("Content-type: application/vnd.ms-excel");
// header("Content-Disposition: attachment; filename=\"$filename\"");  
// echo plugins_url('/reports/'.$filename, __FILE__);
// print $csv_output;

    // readfile($filepath);

    // Delete the file after download
    // unlink($filepath);
    // Provide download link
    echo '<p>Excel file generated successfully to reports folder. Click to download <a href="' . plugins_url('/reports/'.$filename, __FILE__) . '" download>Download Excel</a></p>';
	wp_die();
}
add_action('wp_ajax_nopriv_export_share_report', 'export_share_report');
add_action('wp_ajax_export_share_report', 'export_share_report');
?>