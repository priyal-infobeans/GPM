<div class="container-fluid p-5">
      <div class="row mb-3">
         <div class="col-6 d-flex">
            <h4 class="d-inline-block me-3 mb-0">Sales Report</h4>
         </div>
         <!-- <div class="col-6 text-end pt-2">
            <a href="?page=create_content" class="btn btn-sm btn-primary">Add Content</a>
            <button type="button" id="delete_all_gal" onclick="delete_multiple_contentdata();" class="btn btn-danger btn-sm">Delete Selected</button>
         </div> -->
      </div>
      <div class="myDiv" style="display:none;">
         <img id="loading-image-greeting-neg" src="<?php echo plugins_url() . '/testimonial-slider/images/loading.gif'; ?>" />
      </div>
      <div id="post_content">
         <div id="page_data">
            <table class="table table-bordered" id="gal_list">
               <thead class="table-light">
                  <tr>
                     <!-- <th scope="col" width="5%"><input type="checkbox"></th> -->
                     <th scope="col" width="5%">ID</th>
                     <th scope="col" width="10%">Plays</th>
                     <th scope="col" width="10%">Loads</th>
                     <th scope="col" width="30%">Name</th>
                     <th scope="col" width="10%">Viewers</th>
                     <th scope="col" width="10%">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if(!empty($export_record)){ 

                     foreach($export_record as $arr){ 
                        // echo '<pre>';print_r($arr);
                        ?>
                  <tr>
                     <!-- <td><input type="checkbox" class="get_check" name="get_check[]" id="del_<?= $arr["id"]; ?>" value="<?= $res->id; ?>"></td> -->
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
                     <td>
                           <!-- <a href="?page=create_content&id=<?php echo $arr["id"];?>">Edit</a> |
                           <a href="javascript:void(0)" onclick="delete_content_data('<?php echo $arr["id"];?>');">Delete</a> -->
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
