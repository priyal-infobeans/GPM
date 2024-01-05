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
				action: 'add_report_data',
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
	var edit_id = $("#edit_id").val();
	var vimeo = $('#vimeo_report_id').prop('files')[0]; 
	var sales = $('#sales_report_id').prop('files')[0];
	var price = $('#price_report_id').prop('files')[0];
	var form_data = new FormData();                  
	form_data.append('vimeo', vimeo);
	form_data.append('sales', sales);
	form_data.append('price', price);
	form_data.append('action', 'add_content_data');
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
$(document).on('click', '.editValues', function ()
{
	$(this).parents('tr').find('td.editableColumns').each(function() {
	  var html = $(this).html();
	  var input = $('<input class="editableColumnsStyle" type="text" />');
	  input.val(html);
	  $(this).html(input);
	});
	var row = $(this).closest('tr');
    var rowId = row.data('id');
	$(this).parents('tr').find('td:last-child').append('<button class="saveBtn" data-id="' + rowId + '">Save</button>');
	$(this).closest('tr').find('.editValues').hide();
});
// Add click event to the "Save" button (dynamic binding)
$(document).on('click', '.saveBtn', function () {
	var report_id = $('#report_id').val();
	var report_name = $('#report_name').val();
	// Get the parent row
	var row = $(this).closest('tr');
	var rowId = $(this).data('id');
	var updatedData = {};

	// Iterate through each cell in the row
	row.find('td').each(function () {
		var cell = $(this);
		var fieldName = cell.index(); // Use the column index as the field name
		var updatedContent = cell.find('input').val();
		 // Update the updatedData object
		 updatedData[fieldName] = updatedContent;
		// Replace the input field with the updated content
		cell.html(updatedContent);
	});
	// Show the "Edit" button
	row.find('.editValues').show();
	
	 // Send AJAX request to update data on the server
	 $.ajax({
		url: royaltycallajax.ajaxurl,
		method: 'POST',
		data: { 
			id: rowId,
			data: updatedData,
			action: 'update_report_logs',
			report_id: report_id,
			report_name: report_name,
		},
		success: function (response) {
			console.log('Data updated successfully:', response);
			// $(this).closest('tr').find('.saveBtn').hide();
			row.find('.saveBtn').hide();
		},
		error: function (xhr, status, error) {
			console.error('Error updating data:', xhr.responseText);
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
function previewList(file_type, btn_val)
{
	$("#myDiv").css('display', 'block');
	$('.tmp_preview_list').css('opacity', '0.5');
	var report_id = $('#report_id').val();
	// AJAX Request
	$.ajax({
	url: royaltycallajax.ajaxurl,
	method: 'POST',
	data: { 
		action: 'preview_list',
		report_id: report_id,
		file_type: file_type,
		btn_value: btn_val,
	},
	success: function(response){
		$("#myDiv").css('display', 'none');
		$('.tmp_preview_list').css('opacity', '');
		$('.tmp_preview_list').html(response);
	}
	});
}

function update_report(report_id, mapping_id){
	window.location.replace(origin + pathname + '?page=content_list&preview_id='+report_id+'&id='+mapping_id);
}

function export_report(name, id){
	//window.location.replace(origin + pathname + '?page=file_export&report_id='+id);
	$.ajax({
		url: royaltycallajax.ajaxurl,
		method: 'post',
		data: { 
			action: 'export_share_report',
			report_name: name,
			report_id: id,
		},                     
		beforeSend: function ()
		{
			$("#loader-content").css('display', '');
		},
		success: function(response){
			// console.log(response);
			//window.location.replace(origin + pathname + '?page=file_export&report_id='+id);
			// var options = JSON.parse(response);
				// if (options.status == "success")
				// {
				// 	window.location.replace(origin + pathname + '?page=content_list&preview_id='+options.preview);
				// }
		}
	});
}
function report_download(name, id){
	$.ajax({
		url: royaltycallajax.ajaxurl,
		method: 'post',
		data: { 
			action: 'export_share_report',
			report_name: name,
			report_id: id,
		},                     
		beforeSend: function ()
		{
			$("#loader-content").css('display', '');
		},
		success: function(response){
			$('#exportResult').html(response);
			// console.log(response);
			//window.location.replace(origin + pathname + '?page=file_export&report_id='+id);
			// var options = JSON.parse(response);
				// if (options.status == "success")
				// {
				// 	window.location.replace(origin + pathname + '?page=content_list&preview_id='+options.preview);
				// }
		}
	});
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
