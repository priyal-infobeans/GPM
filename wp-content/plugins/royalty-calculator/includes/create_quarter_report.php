<?php $page_display = isset($id) ? 'Edit' : 'Add';?>
<div class="container-fluid p-5">
      <div class="row mb-3">
         <div class="col-md-12 d-flex">
            <h4 class="d-inline-block me-3 mb-0"><?php echo $page_display;?> New Royalty Calculations</h4>
         </div>
      </div>
      <form>
         <input type="hidden" name="edit_id" id="edit_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '';?>">
         <div class="row">
            <div class="column">
               <label for="review" class="col-form-label">Name Quarterly Report</label>
               <div class="col-4">
                  <input type="text" class="form-control checkRequired" value="<?php echo !empty($shortcode['quarter_report_name']) ? $shortcode['quarter_report_name'] : '';?>" maxlength="300" name="quarter_report_name" id="quarter_report_name"> <small id="msg" style="color:red;"></small>
               </div>
            </div>
            <div class="column">
               <label for="review" class="col-form-label">Select Quarter</label>
               <div class="col-4">
                  <select class="form-control" name="quarter" id="quarter">
                     <option value="q1" <?php if(!empty($shortcode['quarter']) && $shortcode['quarter'] === 'q1') echo 'selected';?>>Quarter1</option>
                     <option value="q2" <?php if(!empty($shortcode['quarter']) && $shortcode['quarter'] === 'q2') echo 'selected';?>>Quarter2</option>
                     <option value="q3" <?php if(!empty($shortcode['quarter']) && $shortcode['quarter'] === 'q3') echo 'selected';?>>Quarter3</option>
                     <option value="q4" <?php if(!empty($shortcode['quarter']) && $shortcode['quarter'] === 'q4') echo 'selected';?>>Quarter4</option>
                  </select>
               </div>
            </div>
            <div class="column">
               <label for="review" class="col-form-label">Select Year</label>
               <div class="col-4">
                  <select class="form-control" name="year" id="year">
                     <option value="2023" <?php if(!empty($shortcode['quarter_year']) && $shortcode['quarter_year'] === '2024') echo 'selected';?>>2024</option>
                     <option value="2023" <?php if(!empty($shortcode['quarter_year']) && $shortcode['quarter_year'] === '2023') echo 'selected';?>>2023</option>
                     <option value="2022" <?php if(!empty($shortcode['quarter_year']) && $shortcode['quarter_year'] === '2022') echo 'selected';?>>2022</option>
                     <option value="2021" <?php if(!empty($shortcode['quarter_year']) && $shortcode['quarter_year'] === '2021') echo 'selected';?>>2021</option>
                     <option value="2020" <?php if(!empty($shortcode['quarter_year']) && $shortcode['quarter_year'] === '2020') echo 'selected';?>>2020</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="mb-3 row">
            <div class="col-2">
            <input type="reset" class="reset-btn" value="Reset"></div>
            <div class="col-10">
               <button type="button" class="btn btn-primary" id="saveContentMain">Save & Next</button>
               <div id='loader-content' style='display:none;'>
                  <img src='<?php echo plugins_url();?>/royalty-calculator/images/loading.gif' width='32px' height='32px'>
               </div>
            </div>
         </div>
      </form>
</div>
