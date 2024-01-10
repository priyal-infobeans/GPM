<div id="myDiv" style="display:none;">
   <img id="loading-image-greeting-neg" src="<?php echo plugins_url() . '/royalty-calculator/images/loading.gif'; ?>" />
</div>
<div class="container-fluid p-5 tmp_preview_list">
      <div class="row mb-3">
         <div class="col-6 d-flex">
            <h4 class="d-inline-block me-3 mb-0">Edit Sales Report</h4>
         </div>
         <div class="col-6 text-end pt-2">
            <a href="?page=content_list&preview_id=<?php echo $_GET['preview_id'];?>" class="btn btn-sm btn-primary">Main Listing</a>
         </div>
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
                     <th scope="col" width="10%">Action</th>
                  </tr>
               </thead>
               <tbody class="list-body">
                  <?php if(!empty($export_record)){ 
                     foreach($export_record as $arr){
                        ?>
                  <tr data-id="<?php echo $arr['id'];?>">
                     <td>
                        <?php echo $arr['id'];?>
                     </td>
                     <td class="editableColumns">
                        <?php echo $arr['plays'];?>
                     </td>
                     <td class="editableColumns">
                        <?php echo $arr['loads'];?>
                     </td>
                     <td class="editableColumns">
                        <?php echo $arr['name'];?>
                     </td>
                     <td class="editableColumns">
                        <?php echo $arr['unique_viewers'];?>
                     </td>
                     <td>
                        <a href="javascript:void(0);" class="editValues">Edit</a>
                        <!-- <a href="javascript:void(0)" onclick="delete_content_data('<?php echo $arr["id"];?>');">Delete</a> -->
                     </td>
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
   </div>
   <div class="col-6 pt-2">
         <button type="button" id="save_next" class="btn btn-sm btn-dark" onclick="window.location.href='<?php echo admin_url();?>admin.php?page=logs_request&preview_id=<?php echo $_GET['preview_id']?>'">View Change Logs</button>
         <button type="button" id="search_in_excel" class="btn btn-sm btn-dark" onclick="search_in_excel(<?php echo $report_id?>, <?php echo $shortcode[0]['id']?>)">Search In Excel</button>
   </div>
   <input type='hidden' name="report_id" id="report_id" value="<?php echo $_GET['preview_id']?>">
   <input type='hidden' name="report_name" id="report_name" value="<?php echo $table_name;?>">