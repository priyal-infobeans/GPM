var $ = jQuery;
var url = window.location.href;
var pathname = window.location.pathname;
var origin = window.location.origin;
$(document).on('click', '#checkAll', function (){
	$('input:checkbox').not(this).prop('checked', this.checked);
});

	$(document).on('click', '#saveContentMain', function ()
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
$(document).on('change', '#vimeo_report_id, #sales_report_id, #price_report_id', function ()
 {
	var file = this.files[0];

	if (file) {
		parseAndImportFile(file);
	}
});
$(document).on('click', '#savecontentdata', function ()
	{
		var report_type = $("#report_type").val();
		var report_name = $("#report_name").val();
		var edit_id=$("#edit_id").val();
		var vimeo = $('#vimeo_report_id').prop('files')[0]; 
		var sales = $('#sales_report_id').prop('files')[0];
		var price = $('#price_report_id').prop('files')[0];
		var form_data = new FormData();                  
		form_data.append('vimeo', vimeo);
		form_data.append('sales', sales);
		form_data.append('price', price);
		form_data.append('action', 'addcontentdata');
		form_data.append('report_id', report_type);
		form_data.append('report_name', report_name);
		form_data.append('edit_id', edit_id);
		$.ajax({
			url: royaltycallajax.ajaxurl,
			dataType: 'text',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			beforeSend: function ()
			{
				$("#loader-content").css('display', '');
			},
			success: function(response){
				var options = JSON.parse(response);
					if (options.status == "success")
					{
						window.location.replace(origin + pathname + '?page=content_list&preview_id='+options.preview);
					}
			}
		});
	});
 
function parseAndImportFile(file){
	var report_type = $("#report_type").val();
	var formData = new FormData();
                formData.append('file', file);
				formData.append('report_id', report_type);
				formData.append('action', 'handle_ajax_xlsx_submission');
                $.ajax({
                    url: royaltycallajax.ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
					beforeSend: function ()
					{
						$("#loader-content").css('display', '');
						$('#savecontentdata').attr("disabled", true) 
					},
                    success: function (response) {
                        $('#importResult').html(response);
						$("#loader-content").css('display', 'none');
						$("#savecontentdata").removeAttr('disabled');
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
}
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
