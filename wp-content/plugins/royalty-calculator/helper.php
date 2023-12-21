<?php 
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
	$shortcode=$wpdb->get_results("SELECT * FROM content_data",ARRAY_A);
	require_once(plugin_dir_path(__FILE__) . 'includes/content_list.php');	
}

add_action('wp_ajax_nopriv_content_slider_list', 'content_list');
add_action('wp_ajax_add_content_slider_list', 'content_list');

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
			'quarter_year' => $quarter_year
		),array('id'=>$id));
		echo json_encode(array('status' => $return));
		wp_die();
	}else{
		$wpdb->insert('royalty_report', array(
			'quarter_report_name' => $quarter_report_name,
			'quarter'=>$quarter,
			'quarter_year' => $quarter_year
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

function delete_multiple_content()
{
	global $wpdb;
	$id=$_POST['post_id'];
	foreach($id as $arr){
		$wpdb->delete('content_slider',array('id'=>$arr));
	}
	
	echo json_encode(array('status' => "success"));
	
}

add_action('wp_ajax_nopriv_delete_multiple_content', 'delete_multiple_content');
add_action('wp_ajax_delete_multiple_content', 'delete_multiple_content');

function delete_multiple_contentdata()
{
	global $wpdb;
	$id=$_POST['post_id'];
	foreach($id as $arr){
		$wpdb->delete('content_data',array('id'=>$arr));
	}
	
	echo json_encode(array('status' => "success"));
	
}

add_action('wp_ajax_nopriv_delete_multiple_contentdata', 'delete_multiple_contentdata');
add_action('wp_ajax_delete_multiple_contentdata', 'delete_multiple_contentdata');

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

function delete_shortcode_contentdata()
{
	global $wpdb;
	$id=$_POST['id'];
	$wpdb->delete('content_data',array('id'=>$id));
	echo json_encode(array('status' => "success"));
	wp_die();
}

add_action('wp_ajax_nopriv_delete_shortcode_contentdata', 'delete_shortcode_contentdata');
add_action('wp_ajax_delete_shortcode_contentdata', 'delete_shortcode_contentdata');

function create_content()
{
	global $wpdb;
	$shortcode=array();
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$shortcode=$wpdb->get_row("SELECT * FROM content_data WHERE id=$id");

		require_once(plugin_dir_path(__FILE__) . 'includes/add_content.php');	
	}else{
		require_once(plugin_dir_path(__FILE__) . 'includes/add_content.php');
	}
	
}

add_action('wp_ajax_nopriv_create_content', 'create_content');
add_action('wp_ajax_add_create_content', 'create_content');

function addcontentdata()
{
	global $wpdb;
	$return = "success";
	$title = isset($_POST['title']) ? $_POST['title'] : '';
	$image = isset($_POST['image']) ? $_POST['image'] : '';
	$image_position = isset($_POST['image_position']) ? $_POST['image_position'] : '';
	$content = isset($_POST['content']) ? $_POST['content'] : '';
	 $id=isset($_POST['edit_id']) ? $_POST['edit_id'] : '0';
	$testimonial_data = $wpdb->get_row("SELECT * FROM content_data WHERE id=".$id,ARRAY_A);

	if(!empty($testimonial_data)){
		$wpdb->update('content_data', array(
			'title' => stripslashes($title),
			'description' => stripslashes($content),
			'image'=>$image,
			'image_position'=>$image_position
		),array('id'=>$id));
		echo json_encode(array('status' => $return));
		wp_die();
	}else{
		$wpdb->insert('content_data', array(
			'title' => stripslashes($title),
			'description' => stripslashes($content),
			'image'=>$image,
			'image_position'=>$image_position
		));
		$lastId = $wpdb->insert_id;
		if ($wpdb->last_error != "") {
			$return = $wpdb->last_error;
		}
		if(!empty($content)){
			$val="true";
		}else{
			$val="false";
		}
	}
	echo json_encode(array('status' => $return));
	wp_die();
}

add_action('wp_ajax_nopriv_addcontentdata', 'addcontentdata');
add_action('wp_ajax_addcontentdata', 'addcontentdata');

function display_shortcode_content($atts, $content, $tag)
{
	global $wpdb;
	$arr = shortcode_atts( array(
		'id' => $atts['id'],
		'left-content'=>$atts['left-content']
	), $atts );
	 wp_enqueue_style('img-content-bootstrap-min',plugins_url().'/image-content-slider/css/frontend/bootstrap.min.css');
	 wp_enqueue_style('img-content-swiper-bundle',plugins_url().'/image-content-slider/css/frontend/swiper-bundle.css');
	 wp_enqueue_style('img-content-style',plugins_url().'/image-content-slider/css/frontend/style.css');
         wp_enqueue_script('img-content-bootstrap-bundle-min',plugins_url().'/image-content-slider/js/frontend/bootstrap.bundle.min.js');
         //wp_enqueue_script('img-content-lightbox-min',plugins_url().'/image-content-slider/js/frontend/bs5-lightbox.min.js');
         wp_enqueue_script('img-content-custom-lib-swiperJS',plugins_url().'/image-content-slider/js/frontend/swiper-bundle.min.js');
         // wp_enqueue_script('img-content-custom',plugins_url().'/image-content-slider/js/frontend/custom.js');

         ?>
         <script id="img-content-custom-js" type="module" src="<?php echo plugins_url().'/image-content-slider/js/frontend/custom.js';?>"></script>
	 <?php 
	 //wp_enqueue_script('jquery-min',plugins_url().'/image-content-slider/js/frontend/jquery.min.js');
	 
	$shortcode=$wpdb->get_row("SELECT * FROM content_slider WHERE id=".$arr['id'],ARRAY_A);
        $shortcode_mobile=$wpdb->get_row("SELECT * FROM content_slider WHERE id=".$arr['id'],ARRAY_A);
	ob_start();
	
	$file = plugin_dir_path(__FILE__) . 'includes/shortcode-template.php';
	include($file);
	
	$content = ob_get_clean();
	return $content; 

}

?>