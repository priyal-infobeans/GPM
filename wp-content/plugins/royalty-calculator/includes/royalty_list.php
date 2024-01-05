<div class="container-fluid p-5">
      <div class="row mb-3">
         <div class="col-6 d-flex">
            <h4 class="d-inline-block me-3 mb-0">Royalty Calculations</h4>
         </div>
         <div class="col-6 text-end pt-2">
            <a href="?page=upload_report_data" class="btn btn-sm btn-primary">+Pre Data Collections</a>
            </div>
      </div>
      <div class="myDiv" style="display:none;">
         <img id="loading-image-greeting-neg" src="<?php echo plugins_url() . '/royalty-calculator/images/loading.gif'; ?>" />
      </div>
      <div id="post_content">
         <div id="page_data">
            <table class="table table-bordered" id="gal_list">
               <thead class="table-light">
                  <tr>
                     <th scope="col" width="5%">ID</th>
                     <th scope="col" width="20%">Name</th>
                     <th scope="col" width="20%">Year</th>
                     <th scope="col" width="20%">Quarter</th>
                     <th scope="col" width="20%">Status</th>
                     <th scope="col" width="15%">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if(!empty($shortcode)){ 
                     foreach($shortcode as $arr){ ?>
                  <tr>
                     <td>
                        <?php echo $arr['id'];?>
                     </td>
                     <td>
                        <?php echo $arr['quarter_report_name'];?>
                     </td>
                     <td>
                        <?php echo ucfirst($arr['quarter']);?>
                     </td>
                     <td>
                        <?php echo $arr['quarter_year'];?>
                     </td>
                     <td>
                        <?php echo ucfirst($arr['status']);?>
                     </td>
                     <td>
                        <a href="?page=create_quarter_report&id=<?php echo $arr["id"];?>">Edit</a> |
                           <a href="javascript:void(0)" onclick="delete_selected_report('<?php echo $arr["id"];?>');">Delete</a>
                     </td>
                  </tr>
                  <?php } 
               }else{ ?>
                  <tr>
                     <td colspan="5" class="text-center">No Records</td>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
