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
           	url: contentcallajax.ajaxurl,
           	type: 'POST',
           	data: { action: 'delete_multiple_content',post_id: post_arr},
           	success: function(response){
           		window.location.reload();
           	}
           });
       } 
   } 
}

function delete_content_shortcode(id)
	{
		var id = id;
		if (confirm('Are you sure you want to delete this?'))
		{
			$.ajax(
			{
				url: contentcallajax.ajaxurl,
				method: "POST",

				data:
				{
					action: 'delete_shortcode_content',
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
  $('.summernote').summernote(
   {
      height: 400, //set editable area's height
      codemirror:
      { // codemirror options
         theme: 'monokai'
      },
   });
});
$(document).ready(function ()
{
	    // $("table#gal_list").simpleCheckboxTable({
     //    onCheckedStateChanged: function ($checkbox) {
     //        // Do something
     //    }
    });

	$(document).on('click', '#saveContentSlider', function ()
	{
	var title = $("#name").val();
	var slider_name = $("#slider_name").val();
	var content = $.trim($('.summernote').summernote('code'));//$.trim($('#content').val());
	var button_text = $("#button_text").val();
	var button_url = $("#button_url").val();
	var edit_id = $("#edit_id").val();
	var checked = '0'; 
	 if($('#last_slide_status').prop('checked')){ 
	 	checked = '1'; 
	 }
	var str = [];
	var values = $('input:checkbox:checked.assign').map(function () {
  	str.push(this.value);
	}).get();
	var selected=$('input:checkbox:checked.assign').length;
	var valid = 0;
	validate = 0;
	$("div").removeClass("checkRequired");

	if(selected < 3){
		$('#msg_select').next('.err-msg').remove();
		$('#msg_select').after("<small class='err-msg'>Please select checkbox atleast  3.</small>");
		validate = 1;
	}else{
		$('#msg_select').next('.err-msg').remove();
	}

	if (title == "")
	{
		$('#name').next('.err-msg').remove();
		$('#name').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#name').next('.err-msg').remove();
	}
	if (slider_name == "")
	{
		$('#slider_name').next('.err-msg').remove();
		$('#slider_name').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#slider_name').next('.err-msg').remove();
	}

	if (button_text == "")
	{
		$('#button_text').next('.err-msg').remove();
		$('#button_text').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#button_text').next('.err-msg').remove();
	}

	if (button_url == "")
	{
		$('#button_url').next('.err-msg').remove();
		$('#button_url').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('#button_url').next('.err-msg').remove();
	}
	
	if (content == "")
	{
		$('.note-editor').next('.err-msg').remove();
		$('.note-editor').after("<small class='err-msg'>This field is required.</small>");
		validate = 1;
	}
	else
	{
		$('.note-editor').next('.err-msg').remove();
	}
	if (validate == 0)
	{
		$.ajax(
		{
			url: contentcallajax.ajaxurl,
			method: "POST",
			cache: false,
			async: false,
			data:
			{
				action: 'addcontentslider',
				title: title,
				slider_name:slider_name,
				content: content,
				button_text:button_text,
				button_url:button_url,
				edit_id:edit_id,
				content_slider_id:str,
				last_slide_status:checked,
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
					window.location.replace(origin + pathname + '?page=content_slider_list');
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
			url: contentcallajax.ajaxurl,
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
           	url: contentcallajax.ajaxurl,
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
				url: contentcallajax.ajaxurl,
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
