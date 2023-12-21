jQuery(document).ready(function($){
	$('#upload-btn').click(function(e) {
		e.preventDefault();
		$('#src').html('');
		var image = wp.media({ 
			title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()

		.on( 'select', function(){

			var attachments = image.state().get('selection').map( 

				function( attachment ) {
					attachment.toJSON();
					return attachment;
				});
			var ch = 'e';
			var str=$('#image_url').val();
			var count_data = str.split(",").length - 1;
			var j = count_data++;
			if(j > 0 || j != ''){
				var l=j++;
			}else {
				var l=0;
			}

			var myVariable = [];
			for (i = 0; i < attachments.length; ++i) {
				myVariable.push(attachments[i].attributes.url);
				$('#src').append('<div class="img_wrp" id="img_wrp'+i+'"><img id="imgstatic'+l+'" src="'+attachments[i].attributes.url+'"><a class="close" href="javascript:void(0);" class="close"><img  id="closestatic'+l+'" onclick="delete_image_static('+l+');" src="'+origin+'/wp-content/plugins/gallery-slider/images/close.png" /></a></div>');
				l++;
			}
			var commaSepValues = myVariable.join(',');
			var new_image=$('#image_url').val();
			
				$('#image_url').val(commaSepValues);
			
		});
	});
});
var $ = jQuery;
var url = window.location.href;
var pathname = window.location.pathname;
var origin = window.location.origin;

$(document).on('click', '#checkAll', function (){
	$('input:checkbox').not(this).prop('checked', this.checked);
});

function delete_image_static(val)
{
	$('#img_wrp'+val).remove();
	var new_image=$('#image_url').val();
	var arr=new_image.split(",");
	var remove_Item = $('#imgstatic'+val).attr('src');
	var test= removeValue(new_image, remove_Item);
	$('#image_url').val(test)

}

function delete_multiple_content()
	{
		var post_arr = [];

    // Get checked checkboxes
    $('#gal_list input[type=checkbox]').each(function() {
    	if (jQuery(this).is(":checked")) {
    		var id = this.id;
    		var splitid = id.split('_');
    		var postid = splitid[1];

    		post_arr.push(postid);

    	}
    });

    if(post_arr.length > 0){

    	var isDelete = confirm("Do you really want to delete records?");
    	if (isDelete == true) {
           // AJAX Request
           $.ajax({
           	url: royaltycallajax.ajaxurl,
           	type: 'POST',
           	data: { action: 'delete_multiple_content',post_id: post_arr},
           	success: function(response){
           		window.location.reload();
           	}
           });
       } 
   } 
}

function delete_selected_report(id)
	{
		var id = id;
		if (confirm('Are you sure you want to delete this?'))
		{
			$.ajax(
			{
				url: royaltycallajax.ajaxurl,
				method: "POST",

				data:
				{
					action: 'delete_selected_report',
					id: id,
				},
				beforeSend: function ()
				{
					$("#loader-content").css('display', '');

				},
				success: function (response)
				{
					window.location.reload();
				}
			});
		}
	}
 $(document).ready(function() {
});


	$(document).on('click', '#saveContentSlider', function ()
	{
	var quarter_report_name = $("#quarter_report_name").val();
	var quarter = $("#quarter option:selected").val();
	var quarter_year = $("#year option:selected").text();
	var edit_id = $("#edit_id").val();
	validate = 0;
	$("div").removeClass("checkRequired");
	if (quarter_report_name == "")
	{
		$('#quarter_report_name').next('.err-msg').remove();
		$('#quarter_report_name').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#quarter_report_name').next('.err-msg').remove();
	}
	if (validate == 0)
	{
		$.ajax(
		{
			url: royaltycallajax.ajaxurl,
			method: "POST",
			cache: false,
			async: false,
			data:
			{
				action: 'addreportdata',
				quarter_report_name: quarter_report_name,
				quarter: quarter,
				quarter_year: quarter_year,
				edit_id:edit_id,
			},
			beforeSend: function ()
			{
				$("#loader-content").css('display', '');
			},
			success: function (response)
			{
				var options = JSON.parse(response);
				if (options.status == "success")
				{
					$("#loader-content").hide();
					$("#add-default-msg").addClass("alert alert-success");
					$("#add-default-msg").html("Submit Successfully!!");
					setTimeout(function ()
					{
						$("#add-default-msg").html("");
						$("#add-default-msg").removeClass("alert alert-success");
					}, 3000);
					window.location.replace(origin + pathname + '?page=royalty_calculator_list');
				}
				else
				{
					$("#loader-content").hide();
					$("#add-default-msg").addClass("alert alert-danger");
					$("#add-default-msg").html(options.status);
					setTimeout(function ()
					{
						$("#add-default-msg").html("");
						$("#add-default-msg").removeClass("alert alert-danger");
					}, 3000);
				}
			}
		});
	}
});

$(document).on('click', '#savecontentdata', function ()
	{
		var title = $("#title").val();
	var content = $.trim($('.summernote').summernote('code'));//$.trim($('#content').val());//$.trim($('.summernote').summernote('code'));
	var image = $("#image_url").val();
	var image_position = $('#image_position :selected').val();
	var edit_id=$("#edit_id").val();
	var valid = 0;
	validate = 0;
	$("div").removeClass("checkRequired");

	if (title == "")
	{
		$('#title').next('.err-msg').remove();
		$('#title').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#title').next('.err-msg').remove();
	}

	
	if (content == "")
	{
		$('#content').next('.err-msg').remove();
		$('#content').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#content').next('.err-msg').remove();
	}

	if (image_position == "")
	{
		$('#image_position').next('.err-msg').remove();
		$('#image_position').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#image_position').next('.err-msg').remove();
	}
	
	
	if (validate == 0)
	{
		$.ajax(
		{
			url: royaltycallajax.ajaxurl,
			method: "POST",
			cache: false,
			async: false,
			data:
			{
				action: 'addcontentdata',
				title: title,
				content: content,
				image:image,
				image_position:image_position,
				edit_id:edit_id,
				
			},
			beforeSend: function ()
			{
				$("#loader-content").css('display', '');
			},
			success: function (response)
			{
				var options = JSON.parse(response);
				if (options.status == "success")
				{
					$("#loader-content").hide();
					$("#add-default-msg").addClass("alert alert-success");
					$("#add-default-msg").html("Submit Successfully!!");
					setTimeout(function ()
					{
						$("#add-default-msg").html("");
						$("#add-default-msg").removeClass("alert alert-success");
					}, 3000);
					window.location.replace(origin + pathname + '?page=content_list');
				}
				else
				{
					$("#loader-content").hide();
					$("#add-default-msg").addClass("alert alert-danger");
					$("#add-default-msg").html(options.status);
					setTimeout(function ()
					{
						$("#add-default-msg").html("");
						$("#add-default-msg").removeClass("alert alert-danger");
					}, 3000);
				}
			}
		});
	}
});

function delete_multiple_contentdata()
{
	var post_arr = [];

    // Get checked checkboxes
    $('#gal_list input[type=checkbox]').each(function() {
    	if (jQuery(this).is(":checked")) {
    		var id = this.id;
    		var splitid = id.split('_');
    		var postid = splitid[1];

    		post_arr.push(postid);

    	}
    });

    if(post_arr.length > 0){

    	var isDelete = confirm("Do you really want to delete records?");
    	if (isDelete == true) {
           // AJAX Request
           $.ajax({
           	url: royaltycallajax.ajaxurl,
           	type: 'POST',
           	data: { action: 'delete_multiple_contentdata',post_id: post_arr},
           	success: function(response){
           		window.location.reload();
           	}
           });
       } 
   } 
}

function delete_content_data(id)
	{
		var id = id;
		if (confirm('Are you sure you want to delete this?'))
		{
			$.ajax(
			{
				url: royaltycallajax.ajaxurl,
				method: "POST",

				data:
				{
					action: 'delete_shortcode_contentdata',
					id: id,
				},
				beforeSend: function ()
				{
					$("#loader-content").css('display', '');

				},
				success: function (response)
				{
					window.location.reload();
				}
			});
		}
	}
