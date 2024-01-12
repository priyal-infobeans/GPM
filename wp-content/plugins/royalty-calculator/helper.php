<?php
/**
 * @method royalty_calculator_list
 * @brief this method is used to load report list.
 */
use PhpOffice\PhpSpreadsheet\IOFactory;
function royalty_calculator_list()
{
	global $wpdb;
	$shortcode=$wpdb->get_results("SELECT * FROM royalty_report",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/royalty_list.php');	
}

add_action('wp_ajax_royalty_calculator_list', 'royalty_calculator_list');

/**
 * @method content_list
 * @brief this method is used to load uploaded report list.
 */
function content_list()
{
	global $wpdb;
	$report_id = isset($_GET['preview_id'])?  $_GET['preview_id'] :'';
	$shortcode=$wpdb->get_results("SELECT * FROM report_mapping where report_id=$report_id",ARRAY_A);
	$table_name = $shortcode[0]['report_name'];
	$export_record = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/content_list.php');
		
}
add_action('wp_ajax_content_list', 'content_list');

/**
 * @method preview_list
 * @brief this method is used to view uploaded report preview.
 */
function preview_list()
{
	global $wpdb;
	$report_id = isset($_POST['report_id'])? $_POST['report_id'] : $_GET['preview_id'];
	$shortcode=$wpdb->get_results("SELECT * FROM report_mapping where report_id=$report_id",ARRAY_A);
	$file_type = isset($_POST['file_type'])? $_POST['file_type'] :'';
	$btn_value = isset($_POST['btn_value'])? $_POST['btn_value'] :'';
	$table_name = format_report($report_id, $btn_value);//($report_id, $file_type)
	$export_record = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/content_list.php');
	json_encode(array('status' => "success",'result'=>$export_record));
	wp_die();
}
add_action('wp_ajax_nopriv_preview_list', 'preview_list');
add_action('wp_ajax_preview_list', 'preview_list');

/**
 * @method create_quarter_report
 * @brief this method is used to load/add form to upload quarterly reports.
 */
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

add_action('wp_ajax_create_quarter_report', 'create_quarter_report');

/**
 * @method add_report_data
 * @brief this method is used to fetch royalty report quarter details.
 */
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

/**
 * @method delete_selected_report
 * @brief this method is used to delete the reports added.
 */
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

/**
 * @method upload_report_data
 * @brief this method is used to load form to upload all report types and its mapping.
 */
function upload_report_data()
{
	global $wpdb;
	create_mapping_table();
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

add_action('wp_ajax_upload_report_data', 'upload_report_data');

// Enqueue necessary scripts and styles
function enqueue_custom_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts');

/**
 * @method mapping_data
 * @brief this method is used to map report data while uploading files.
 */
function mapping_data($report_id, $file, $uploaded_table_name) {
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

/**
 * @method add_content_data
 * @brief this method is used to save the uploaded file records.
 */
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

/**
 * @method handle_ajax_xlsx_submission
 * @brief this method is used to handle AJAX request for parsing and importing files.
 */
function handle_ajax_xlsx_submission() {
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
    $file = $_FILES['file'];
	$report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
	$file_type = isset($_POST['file_type']) ? $_POST['file_type'] : '';
	// Upload the selected files
	$movefile = upload_file_data($file);
	$uploaded_table_name = format_report($report_id, $file_type);//($report_id, $file['name'])
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
		create_table($columnNames, $uploaded_table_name);

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
            insert_data($row, $uploaded_table_name);
        }
		mapping_data($report_id, $file, $uploaded_table_name);
		echo '<input type="hidden" name="report_name[]" id="report_name" value="'.$uploaded_table_name.'">';
        echo 'Data from ' . $file['name'] . ' imported successfully.<br>';
    } else {
        echo 'Error uploading file.';
    }
    wp_die();
}

add_action('wp_ajax_handle_ajax_xlsx_submission', 'handle_ajax_xlsx_submission');
add_action('wp_ajax_nopriv_handle_ajax_xlsx_submission', 'handle_ajax_xlsx_submission'); // Allow non-logged-in users to access the AJAX endpoint

/**
 * @method upload_file_data
 * @brief this method is used to upload files in wp uploads folder.
 */
function upload_file_data($file) {
	$upload_dir = wp_upload_dir();
	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $file, $upload_overrides );
	return $movefile;
}

/**
 * @method format_report
 * @brief this method is used to rename the report import table.
 */
function format_report($report_id, $file) {
	global $wpdb;
	$data = $wpdb->get_row("SELECT * FROM royalty_report WHERE id=".$report_id,ARRAY_A);
	$report_name = strtolower(str_replace(' ', '_', $data['quarter_report_name']));
	$report_quarter = $data['quarter'];
	$report_year = $data['quarter_year'];
	$file_split = explode('_', $file);//explode(' ', $file);
	$rename_file = strtolower(reset($file_split));
	$format_report_name = $report_name.'_'.$report_quarter.'_'.$report_year.'_'.$rename_file;
	return $format_report_name;
}

/**
 * @method create_table
 * @brief this method is used to create a table with columns from XLSX file first row.
 */
function create_table($columns, $tableName) {
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

/**
 * @method insert_data
 * @brief this method is used to insert data into the created table.
 */
function insert_data($data, $tableName) {
    global $wpdb;
    // Insert data into the table
    $wpdb->insert($tableName, $data);
}

/**
 * @method create_mapping_table
 * @brief this method is used to create mysql query for mapping table.
 */
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

/**
 * @method update_report_logs
 * @brief this method is used to manage the logs entry.
 */
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

/**
 * @method file_export
 * @brief this method is used to include export file.
 */
function file_export() {
	global $wpdb;
	require_once(plugin_dir_path(__FILE__) . 'includes/file_export.php');
}

add_action('wp_ajax_file_export', 'file_export');

/**
 * @method export_share_report
 * @brief this method is used to generate and download excel file.
 */
function export_share_report() {
	global $wpdb;
	// Load PhpSpreadsheet library
	require_once (plugin_dir_path(__FILE__) . 'PhpSpreadsheet/vendor/autoload.php');
	$report_name = isset($_POST['report_name'])? $_POST['report_name'] : '';
	$report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
	$data = $wpdb->get_results("SELECT * FROM $report_name",ARRAY_A);
	// update query for report status
	$wpdb->update('royalty_report', array(
		'status' => 'completed',
	),array('id'=>$report_id));

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

	create_reports_directory();
    // Save Excel file
    $filename = 'excel_export_' . date('YmdHis') . '.xlsx';
	$filepath = plugin_dir_path(__FILE__) . '/reports/'.$filename;

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($filepath);

    // Provide download link
    echo '<p>Excel file generated successfully to reports folder. Click to download <a href="' . plugins_url('/reports/'.$filename, __FILE__) . '" download>Download Excel</a></p>';
	wp_die();
}
add_action('wp_ajax_nopriv_export_share_report', 'export_share_report');
add_action('wp_ajax_export_share_report', 'export_share_report');

/**
 * @method export_share_report
 * @brief this method is used to create 'reports' directory and set permissions.
 */
function create_reports_directory() {
    $directory_path = plugin_dir_path(__FILE__) . 'reports';

    // Check if the directory already exists
    if (!file_exists($directory_path)) {
        // Create the 'reports' directory with read and write permissions for everyone
        mkdir($directory_path, 0777, true);
    }
}

/**
 * @method view_change_logs
 * @brief this method is used to check all the logs.
 */
function view_change_logs() {
	global $wpdb;
	$report_id = isset($_GET['preview_id'])?  $_GET['preview_id'] :'';
	$shortcode=$wpdb->get_results("SELECT * FROM report_mapping where report_id=$report_id",ARRAY_A);
	$table_name = $shortcode[0]['report_name'].'_logs';
	$view_logs = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/view_logs.php');
		
}
add_action('wp_ajax_view_change_logs', 'view_change_logs');

/**
 * @method data_calculation
 * @brief this method is used to load calculation page and generate and download excel file after the searching calculations.
 */
function data_calculation() {
	global $wpdb;
	$report_id = isset($_GET['preview_id']) ? $_GET['preview_id'] : '';
	$data_id = isset($_GET['id']) ? $_GET['id'] : '';
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$table_for_search = $wpdb->get_row("SELECT report_name FROM report_mapping WHERE id=".$data_id." and report_id=".$report_id,ARRAY_A);
	$table_to_search = $wpdb->get_row("SELECT report_name FROM report_mapping WHERE report_id=".$report_id." and report_name LIKE '%".$type."%'",ARRAY_A);
	$columnForSearch = 'sku';
	$tableNameToSearch = 'calc_q1_2024_sales';
	$columnToSearch = 'Title';
	$filename = vlookup($table_for_search['report_name'], $columnForSearch, $table_to_search['report_name'], $columnToSearch);
	require_once(plugin_dir_path(__FILE__) . 'includes/data_calculations.php');
	wp_die();
}
add_action('wp_ajax_data_calculation', 'data_calculation');

/**
 * @method vlookup
 * @brief this method is used to search from within excel files.
 */
function vlookup($tableNameForSearch, $columnForSearch, $tableNameToSearch, $columnToSearch)
{
    global $wpdb;
	// Load PhpSpreadsheet library
	require_once (plugin_dir_path(__FILE__) . 'PhpSpreadsheet/vendor/autoload.php');
    // Query to retrieve SKU values from tableForSearch
    $queryForSearch = $wpdb->prepare("SELECT %i FROM %i", $columnForSearch, $tableNameForSearch);

    $skuValues = $wpdb->get_col($queryForSearch);

	// Initialize array to store matching results
    $matchingResults = [];

    // Iterate through SKU values
	for($i=0;$i<count($skuValues);$i++){
		$searched_val = '%' . $skuValues[$i] . '%';
        $queryToSearch = $wpdb->prepare("SELECT %i FROM %i WHERE %i LIKE %s", $columnToSearch, $tableNameToSearch, $columnToSearch, $searched_val);
        $matchingTitles = $wpdb->get_col($queryToSearch);

		 // Count the number of matches
		 $matchesCount = count($matchingTitles);

		 if ($matchesCount > 0) {
            $matchingResults[] = [
                'title' => implode(', ', $matchingTitles),
                'matching_sku' => $skuValues[$i],
                'count' => $matchesCount,
            ];
        	}
		}

		// Create a new PhpSpreadsheet instance
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
	
		// Set headers
		$sheet->setCellValue('A1', 'Title');
		$sheet->setCellValue('B1', 'Matching SKU');
		$sheet->setCellValue('C1', 'Count');
	
		// Populate data
		$row = 2;
		foreach ($matchingResults as $result) {
			$sheet->setCellValue('A' . $row, $result['title']);
			$sheet->setCellValue('B' . $row, $result['matching_sku']);
			$sheet->setCellValue('C' . $row, $result['count']);
			$row++;
		}

		$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		// Save Excel file
		$filename = 'calculation_export_' . date('YmdHis') . '.xlsx';
		$filepath = plugin_dir_path(__FILE__) . 'reports/'.$filename;
		
		$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save($filepath);

		$file = plugins_url("/reports/".$filename, __FILE__);
		echo '<div class="container-fluid p-5">
		<div class="row mb-3">
		  <a class="btn btn-sm btn-primary" href="'.$file.'">Download Calculation Sheet</a>
		</div>
	</div>';
}
?>