<div class="container-fluid p-5">
      <div class="row mb-3">
         <div class="col-6 d-flex">
            <h4 class="d-inline-block me-3 mb-0">All Slides</h4>
         </div>
         <div class="col-6 text-end pt-2">
            <a href="?page=create_content" class="btn btn-sm btn-primary">Add Content</a>
                <button type="button" id="delete_all_gal" onclick="delete_multiple_contentdata();" class="btn btn-danger btn-sm">Delete Selected</button>
            </div>
      </div>
      <div class="myDiv" style="display:none;">
         <img id="loading-image-greeting-neg" src="<?php echo plugins_url() . '/testimonial-slider/images/loading.gif'; ?>" />
      </div>
      <div id="post_content">
         <div id="page_data">
            <table class="table table-bordered" id="gal_list">
               <thead class="table-light">
                  <tr>
                     <th scope="col" width="5%"><input type="checkbox"></th>
                     <th scope="col" >Assigned Slider(s)</th>
                     <th scope="col" width="20%">Title</th>
                     <th scope="col">Description</th>
                     <th scope="col" width="10%">Image</th>
                     <th scope="col" width="10%">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php if(!empty($shortcode)){ 

                     foreach($shortcode as $arr){ ?>
                  <tr>
                      <td><input type="checkbox" class="get_check" name="get_check[]" id="del_<?= $arr["id"]; ?>" value="<?= $res->id; ?>"></td>
                     <td>
                        <?php 
                           
                           global $wpdb;
                          
                           $content_slider = $wpdb->get_results("SELECT * FROM content_slider",ARRAY_A);

                           echo '<ul>';
                           foreach($content_slider as $val){
                              
                              $explode = explode(',', $val['content_slider_id']);

                              if (in_array($arr['id'], $explode)) { 
                                 echo '<li>'.$val['slider_name'].'</li>';     
                              }
                              
                           }
                           echo '</ul>';

                        ?>
                        </ul>
                     </td>
                    
                     <td>
                        <?php echo $arr['title'];?>
                     </td>
                     <td>
                        <?php echo $arr['description'];?>
                     </td>
                     <td>
                       <img src="<?php echo $arr['image'];?>" style="width:70px;height:70px;">
                     </td>
                     
                     <td>
                        <a href="?page=create_content&id=<?php echo $arr["id"];?>">Edit</a> |
                           <a href="javascript:void(0)" onclick="delete_content_data('<?php echo $arr["id"];?>');">Delete</a>
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
