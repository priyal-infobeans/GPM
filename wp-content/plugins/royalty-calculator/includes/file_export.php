<?php $result = $wpdb->get_row("SELECT * FROM royalty_report WHERE id=".$_GET['report_id'],ARRAY_A);?>
<div class="container-fluid p-5">
    <div class="row mb-3">
         <div class="col-12">
            <h4 class="d-inline-block me-3 mb-0">Congratulations ! Royalty Calculation "<?php echo $_GET['file'];?>" for Quarter '<?php echo ucfirst($result['quarter']);?>' Year '<?php echo $result['quarter_year'];?>' is successfully completed.</h4>
         </div>
    </div>
    <div class="row">
        <button type="button" id="final_sheet" class="btn btn-sm btn-primary" onclick="report_download('<?php echo $_GET['file']?>',<?php echo $_GET['report_id'];?>)">Download Final Sheet</button>
    </div>
    <div id="exportResult"></div>
</div>