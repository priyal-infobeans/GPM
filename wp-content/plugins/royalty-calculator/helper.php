<?php 
function royalty_calculator_list()
{
	global $wpdb;
	$shortcode=$wpdb->get_results("SELECT * FROM content_slider",ARRAY_A);
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
	$data=$wpdb->get_results("SELECT * FROM content_data ",ARRAY_A);
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$shortcode=$wpdb->get_row("SELECT * FROM content_slider WHERE id=".$id,ARRAY_A);

		require_once(plugin_dir_path(__FILE__) . 'includes/create_quarter_report.php');	
	}else{
		require_once(plugin_dir_path(__FILE__) . 'includes/create_quarter_report.php');
	}
	
}

add_action('wp_ajax_nopriv_create_quarter_report', 'create_quarter_report');
add_action('wp_ajax_add_create_quarter_report', 'create_quarter_report');

function addcontentslider()
{
	global $wpdb;
	$return = "success";
	$title = isset($_POST['title']) ? $_POST['title'] : '';
	$slider_name = isset($_POST['slider_name']) ? $_POST['slider_name'] : '';
	$button_url = isset($_POST['button_url']) ? $_POST['button_url'] : '';
	$button_text = isset($_POST['button_text']) ? $_POST['button_text'] : '';
	$content = isset($_POST['content']) ? $_POST['content'] : '';
	$last_slide_status = isset($_POST['last_slide_status']) ? $_POST['last_slide_status'] : '';
	$id = isset($_POST['edit_id']) ? $_POST['edit_id'] : $_GET['id'];
	$content_slider_id=isset($_POST['content_slider_id']) ? $_POST['content_slider_id'] : '';

	if(empty($id)){
		$id = 0;
	}

	$content_data = $wpdb->get_row("SELECT * FROM content_slider WHERE id=".$id,ARRAY_A);

	if(!empty($content_data)){
		$wpdb->update('content_slider', array(
			'name' => stripslashes($title),
			'slider_name'=>$slider_name,
			'content' => stripslashes($content),
			'button_text'=>$button_text,
			'button_url'=>$button_url,
			'content_slider_id'=>implode(',',$content_slider_id),
			'last_slide_status'=>$last_slide_status
		),array('id'=>$id));
		echo json_encode(array('status' => $return));
		wp_die();
	}else{
		$wpdb->insert('content_slider', array(
			'name' => stripslashes($title),
			'slider_name'=>$slider_name,
			'content' => stripslashes($content),
			'button_text'=>$button_text,
			'button_url'=>$button_url,
			'content_slider_id'=>implode(',',$content_slider_id),
			'last_slide_status'=>$last_slide_status
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

		$shortcode='[content id='.$lastId.' left-content="'.$val.'"]';
		$wpdb->update('content_slider', array(
			'content_shortcode' => $shortcode,

		),array('id'=>$lastId));
		echo json_encode(array('status' => $return));
		wp_die();
	}
	echo json_encode(array('status' => $return));
	wp_die();
}

add_action('wp_ajax_nopriv_addcontentslider', 'addcontentslider');
add_action('wp_ajax_addcontentslider', 'addcontentslider');

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

function delete_shortcode_content()
{
	global $wpdb;
	$id=$_POST['id'];
	$wpdb->delete('content_slider',array('id'=>$id));
	echo json_encode(array('status' => "success"));
	wp_die();
}

add_action('wp_ajax_nopriv_delete_shortcode_content', 'delete_shortcode_content');
add_action('wp_ajax_delete_shortcode_content', 'delete_shortcode_content');

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