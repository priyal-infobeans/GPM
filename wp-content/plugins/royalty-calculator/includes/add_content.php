<div class="container-fluid p-5">
      <div class="row mb-3">
         <div class="col-md-12 d-flex">
            <?php if(isset($_GET['id'])){ ?>
            <h4 class="d-inline-block me-3 mb-0">Edit Slide</h4>
         <?php }else{ ?>

             <h4 class="d-inline-block me-3 mb-0">New Slide</h4>
        <?php } ?>
         </div>
      </div>
      <form>
         <input type="hidden" name="edit_id" id="edit_id" value="<?php if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo 0; } ?>">
         

         <div class="mb-3 row">
            <label for="review" class="col-2 col-form-label">Title</label>
            <div class="col-4">
               <input type="text" class="form-control checkRequired" value="<?php if(isset($shortcode->title)){ echo $shortcode->title;} ?>" maxlength="255" name="title" id="title"> <small id="msg" style="color:red;"></small>
            </div>
         </div>
         <div class="mb-3 row">
            <label for="review" class="col-2 col-form-label">Description</label>
            <div class="col-8">
             <textarea name="content" class="form-control summernote" value="" id="content" rows="5"><?php if(isset($shortcode->description)){ echo $shortcode->description;} ?></textarea>
          </div>
       </div>

        <div class="mb-3 row">
         <label for="review" class="col-2 col-form-label">Add Images</label>
         <div class="col-8">
          <input type="hidden" name="image_url" id="image_url" class="regular-text" value="<?php if(isset($shortcode->image)){ echo $shortcode->image; } ?>">
          <input type="button" name="upload-btn" id="upload-btn" class="btn btn-outline-primary" value="Upload Image">
          <?php if(!empty($shortcode->image)){ ?>
          <div id="src"><div class="img_wrp" id="img_wrp1"><img id="imgstatic1" src="<?php echo $shortcode->image;?>"></div></div>
       <?php }else{ ?>
         <div id="src"></div>
        <?php } ?>
       </div>

    </div>

       <div class="mb-3 row">
         <label for="review" class="col-2 col-form-label">Image position</label>
         <div class="col-4">
           <select name="image_position" class="form-control"id="image_position">
              <option value="">select position</option>
              <option value="center" <?php if(isset($shortcode->image_position)){ if($shortcode->image_position == "center"){ echo "selected";} }?>>Center</option>
              <option value="left" <?php if(isset($shortcode->image_position)){ if($shortcode->image_position == "left"){ echo "selected";} }?>>Left</option>
              <option value="right" <?php if(isset($shortcode->image_position)){ if($shortcode->image_position == "right"){ echo "selected";} }?>>Right</option>
             
            
           </select>
         </div>
      </div>

 <div class="mb-3 row">
      <div class="col-2"></div>
      <div class="col-10">
         <button type="button" class="btn btn-primary" id="savecontentdata">Submit</button>
         <div id='loader-content' style='display:none;'>
            <img src='<?php echo plugins_url();?>/testimonial-slider/images/loading.gif' width='32px' height='32px'>
         </div>
      </div>
   </div>

   <div class="mb-3 row">
      <div class="col-2"></div>
      <div class="col-4">
         <div id="add-default-msg"></div>
      </div>
   </div>
</form>
</div>
<?php 
wp_enqueue_script('jquery');
wp_enqueue_media();
?>
