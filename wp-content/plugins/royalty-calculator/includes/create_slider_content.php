<?php $slide = 'Create'; ?>

<div class="container-fluid p-5">
    <div class="row mb-3">
        <div class="col-md-12 d-flex">
            <h4 class="d-inline-block me-3 mb-0"><?php echo $slide; ?> Slider</h4>
        </div>
    </div>
    <form>
        <input type="hidden" name="edit_id" id="edit_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : 0; ?>">


        <div class="mb-3 row">
            <label for="review" class="col-2 col-form-label">Title</label>
            <div class="col-4">
                <input type="text" class="form-control checkRequired" value="<?php echo!empty($shortcode_content['title']) ? $shortcode_content['title'] : ''; ?>" maxlength="" name="title" id="title"> <small id="msg" style="color:red;"></small>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="review" class="col-2 col-form-label">Description</label>
            <div class="col-8">
                <textarea name="content" class="form-control summernote" id="description" rows="5"><?php echo!empty($shortcode_content['description']) ? $shortcode_content['description'] : ''; ?></textarea>
            </div>
        </div>

        <?php if (empty($data)) { ?>
            <div class="mb-3 row">
                <label for="review" class="col-2 col-form-label">Assign Slider</label>
                <div class="col-10">
                    <span>You need to add a slider content for creating a slider</span>
                    <a type="button" class="btn btn-primary" href="?page=create_content" id="">Add Slider Content</a>
                </div>
            </div>
        <?php } else { ?>

            <div class="mb-3 row">
                <label for="review" class="col-2 col-form-label">Assign Slider Content</label>
                <div class="col-10">
                    <div class="row">
                        <?php
                        if (!empty($shortcode_content['slider_id'])) {
                            $content_id = $shortcode_content['slider_id'];
                            $content_id_arr = explode(',', $content_id);
                            $j = 1;
                            $count = count($data);
                            foreach ($data as $data_arr) {
                                ?>
                                <div class="col-4 d-flex">

                                    <input type="checkbox" class="form-control mt-1 me-3 assign" value="<?php echo $data_arr['id']; ?>" <?php
                                           if (in_array($data_arr['id'], $content_id_arr)) {
                                               echo "checked";
                                           }
                                           ?> name="assign_slider" id="assign_slider">
                                    <label for="review" class="col-form-label p-0"> <?php echo $data_arr['gal_slider_name']; ?></label>
                                </div>
            <?php $j++;
        }
    } else { ?>
        <?php foreach ($data as $data_arr) { ?>
                                <div class="col-4 d-flex">
                                    <input type="checkbox" class="form-control mt-1 me-3 assign" value="<?php echo $data_arr['id']; ?>" name="assign_slider" id="assign_slider">
                                    <label for="review" class="col-form-label p-0"><?php echo $data_arr['gal_slider_name']; ?> </label>
                                </div>
        <?php } ?>

    <?php }
} ?>
                    <div id="msg_select"></div>
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-2"></div>
            <div class="col-10">
                <button type="button" class="btn btn-primary" id="saveContentSlider">Submit</button>
                <div id='loader-content' style='display:none;'>
                    <img src='<?php echo plugins_url(); ?>/testimonial-slider/images/loading.gif' width='32px' height='32px'>
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