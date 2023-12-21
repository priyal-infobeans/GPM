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
         <img id="loading-image-greeting-neg" src="<?php echo plugins_url() . '/testimonial-slider/images/loading.gif'; ?>" />
      </div>
      <div id="post_content">
         <div id="page_data">
            <table class="table table-bordered" id="gal_list">
               <thead class="table-light">
                  <tr>
                     <th scope="col" width="5%"><input type="checkbox" id="checkAll"></th>
                     <th scope="col" width="20%">Quarter Name</th>
                     <!-- <th scope="col">Shortcode</th>
                     <th scope="col" width="15%">Slide Count</th> -->
                     <th scope="col" width="15%">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if(!empty($shortcode)){ 

                     foreach($shortcode as $arr){ ?>
                  <tr>
                     <td><input type="checkbox" class="get_check" name="get_check[]" id="del_<?= $arr["id"]; ?>" value="<?= $res->id; ?>"></td>
                     <td>
                        <?php echo $arr['slider_name'];?>
                     </td>
                     <!-- <td>
                        <?php echo $arr['content_shortcode'];?>
                     </td>
                     <td>
                        <?php if(!empty($arr['content_slider_id'])){
                           global $wpdb;
                           $val=explode(',',$arr['content_slider_id']);
                           $array=array();
                           foreach($val as $val_arr){
                         $testimonial_data_val = $wpdb->get_row("SELECT * FROM content_data WHERE id=".$val_arr);
                           $array[]= $testimonial_data_val->name;
                           }
                     echo count($array);
                        } ?>
                     </td> -->
                     <td>
                        <a href="?page=create_content_slider&id=<?php echo $arr["id"];?>">Edit</a> |
                           <a href="javascript:void(0)" onclick="delete_content_shortcode('<?php echo $arr["id"];?>');">Delete</a>
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
