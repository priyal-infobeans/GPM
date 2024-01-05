<?php if(isset($_GET['id'])) {
   // load edit view.
   require_once( 'content_list_edit.php');
} else { ?>
<div id="myDiv" style="display:none;">
   <img id="loading-image-greeting-neg" src="<?php echo plugins_url() . '/royalty-calculator/images/loading.gif'; ?>" />
</div>
<div class="container-fluid p-5 tmp_preview_list">
      <div class="row mb-3">
         <div class="col-6 d-flex">
            <h4 class="d-inline-block me-3 mb-0">Sales Report</h4>
         </div>
         <?php
         $class_default = 'btn-dark';
         $class_active = 'btn-success';
         if (isset($btn_value)){
            ?>
            <div class="col-6 text-end pt-2">
               <button type="button" id="vimeo_list" onclick="previewList('<?php echo $shortcode[0]['upload_vimeo'];?>','vimeo');" class="btn btn-sm <?php if($btn_value == 'vimeo') { echo $class_active; } else {echo $class_default;}?>">Vimeo Uploaded File</button>
               <button type="button" id="price_list" onclick="previewList('<?php echo $shortcode[0]['upload_price'];?>','price');" class="btn btn-sm <?php if($btn_value == 'price') { echo $class_active; } else {echo $class_default;}?>">Price List</button>
               <button type="button" id="sales_list" onclick="previewList('<?php echo $shortcode[0]['upload_sales'];?>','sales');" class="btn btn-sm <?php if($btn_value == 'sales') { echo $class_active; } else {echo $class_default;}?>">Sales List</button>
               <input type="hidden" name="report_id" id="report_id" value="<?php echo $report_id;?>">
            </div>
         <?php } else {
         ?>
            <div class="col-6 text-end pt-2">
               <button type="button" id="vimeo_list" onclick="previewList('<?php echo $shortcode[0]['upload_vimeo'];?>','vimeo');" class="btn btn-sm btn-success">Vimeo Uploaded File</button>
               <button type="button" id="price_list" onclick="previewList('<?php echo $shortcode[0]['upload_price'];?>','price');" class="btn btn-sm btn-dark">Price List</button>
               <button type="button" id="sales_list" onclick="previewList('<?php echo $shortcode[0]['upload_sales'];?>','sales');" class="btn btn-sm btn-dark">Sales List</button>
               <input type="hidden" name="report_id" id="report_id" value="<?php echo $report_id;?>">
            </div>
         <?php }?>
      </div>
      
      <div id="post_content">
         <div id="page_data">
            <table class="table table-bordered" id="gal_list">
               <thead class="table-light">
                  <tr>
                     <th scope="col" width="5%">ID</th>
                     <th scope="col" width="10%">Plays</th>
                     <th scope="col" width="10%">Loads</th>
                     <th scope="col" width="30%">Name</th>
                     <th scope="col" width="10%">Viewers</th>
                     <!-- <th scope="col" width="10%">Action</th> -->
                  </tr>
               </thead>
               <tbody class="list-body">
                  <?php if(!empty($export_record)){ 
                     foreach($export_record as $arr){
                        ?>
                  <tr>
                     <td>
                        <?php echo $arr['id'];?>
                     </td>
                     <td>
                        <?php echo $arr['plays'];?>
                     </td>
                     <td>
                        <?php echo $arr['loads'];?>
                     </td>
                     <td>
                        <?php echo $arr['name'];?>
                     </td>
                     <td>
                        <?php echo $arr['unique_viewers'];?>
                     </td>
                     <!-- <td>
                           <a href="?page=create_content&id=<?php echo $arr["id"];?>">Edit</a> |
                           <a href="javascript:void(0)" onclick="delete_content_data('<?php echo $arr["id"];?>');">Delete</a>
                     </td> -->
                  </tr>
                  <?php } 
               }else{ ?>
                  <tr>
                     <td colspan="6" class="text-center">No Records</td>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
      <div class="col-6 text-end pt-2">
         <button type="button" id="edit_list" class="btn btn-sm btn-dark" onclick="update_report(<?php echo $report_id;?>, <?php echo $shortcode[0]['id'];?>)">Edit</button>
         <button type="button" id="save_next" class="btn btn-sm btn-primary" onclick="window.location.href='<?php echo admin_url();?>admin.php?page=file_export&file=<?php echo $table_name?>&report_id=<?php echo $report_id?>'">Save & Next</button>
      </div>
   </div>
   <?php }?>
   
   <!-- export_report('<?php echo $table_name;?>',<?php echo $report_id;?>); -->