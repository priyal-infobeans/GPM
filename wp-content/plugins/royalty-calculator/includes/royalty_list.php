<div class="container-fluid p-5">
      <div class="row mb-3">
         <div class="col-6 d-flex">
            <h4 class="d-inline-block me-3 mb-0">Royalty Calculations</h4>
         </div>
         <!-- <div class="col-6 text-end pt-2">
            <a href="?page=create_content_slider" class="btn btn-sm btn-primary">Create Slider</a>
                <button type="button" id="delete_all_gal" onclick="delete_multiple_content();" class="btn btn-danger btn-sm">Delete Selected</button>
            </div> -->
      </div>
      <div class="myDiv" style="display:none;">
         <img id="loading-image-greeting-neg" src="<?php echo plugins_url() . '/royalty-calculator/images/loading.gif'; ?>" />
      </div>
      <div id="post_content">
         <div id="page_data">
            <table class="table table-bordered" id="gal_list">
               <thead class="table-light">
                  <tr>
                     <th scope="col" width="20%">Quarter Name</th>
                     <th scope="col" width="15%">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if(!empty($shortcode)){ 
                     foreach($shortcode as $arr){ ?>
                  <tr>
                     <td>
                        <?php echo $arr['quarter_report_name'];?>
                     </td>
                     <td>
                        <a href="?page=create_quarter_report&id=<?php echo $arr["id"];?>">Edit</a> |
                           <a href="javascript:void(0)" onclick="delete_selected_report('<?php echo $arr["id"];?>');">Delete</a>
                     </td>
                  </tr>
                  <?php } 
               }else{ ?>
                  <tr>
                     <td colspan="2" class="text-center">No Records</td>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
