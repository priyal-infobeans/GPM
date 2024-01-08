<div class="container-fluid p-5 upload-report">
      <div class="row mb-3">
         <div class="col-md-12 d-flex">
            <?php if(isset($_GET['id'])){ ?>
            <h4 class="d-inline-block me-3 mb-0">Edit Royalty Calculations</h4>
         <?php }else{ ?>
             <h4 class="d-inline-block me-3 mb-0">Royalty Calculations</h4>
        <?php } ?>
         </div>
      </div>
      <form method="post" name="upload_report" enctype="multipart/form-data">
         <input type="hidden" name="edit_id" id="edit_id" value="<?php if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo 0; } ?>">
         <div class="mb-3 row">
            <label for="review" class="col-2 col-form-label">Select Report</label>
            <div class="col-4">
               <select name="report_type" id="report_type">
                  <?php foreach($shortcode as $data) {?>
                  <option value="<?php echo $data['id']?>"><?php echo $data['quarter_report_name']?></option>
                  <?php }?>
               </select>
            </div>
         </div>
         <div class="mb-3 row">
            <label for="vimeo_report" class="col-2 col-form-label">Upload Vimeo Report</label>
            <div class="col-8">
             <input type="file" name="vimeo_report" id="vimeo_report_id">
            </div>
         </div>

         <div class="mb-3 row">
            <label for="sales_report" class="col-2 col-form-label">Upload Sales Report</label>
            <div class="col-8">
             <input type="file" name="sales_report" id="sales_report_id">
            </div>
         </div>
         <div class="mb-3 row">
            <label for="price_report" class="col-2 col-form-label">Upload Price Report</label>
            <div class="col-8">
             <input type="file" name="price_report" id="price_report_id">
            </div>
         </div>
         <div id="importResult"></div>
      <div class="mb-3 row">
         <div class="col-2"></div>
            <div class="col-10">
               <button type="button" class="btn btn-primary" id="savecontentdata">Submit</button>
               <div id='loader-content' style='display:none;'>
                  <img src='<?php echo plugins_url();?>/royalty-calculator/images/loading.gif' width='32px' height='32px'>
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
