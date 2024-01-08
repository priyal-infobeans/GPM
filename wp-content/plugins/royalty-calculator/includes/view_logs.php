<div class="container-fluid p-5">
    <div class="row mb-3">
    <div class="col-6 d-flex">
        <h4 class="d-inline-block me-3 mb-0">View Logs</h4>
    </div>
    <div class="col-6 text-end pt-2">
        <a href="?page=content_list&preview_id=<?php echo $_GET['preview_id'];?>" class="btn btn-sm btn-primary">Main Listing</a>
    </div>
    </div>
    <div id="post_content">
        <div id="page_data">
        <table class="table table-bordered" id="gal_list">
            <thead class="table-light">
                <tr>
                    <th scope="col" width="5%">ID</th>
                    <th scope="col" width="10%">Name</th>
                    <th scope="col" width="40%">Logs</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($view_logs)){ 
                    foreach($view_logs as $arr){ ?>
                <tr>
                    <td>
                    <?php echo $arr['id'];?>
                    </td>
                    <td>
                    <?php echo $arr['report_name'];?>
                    </td>
                    <td>
                    <?php
                        $logs_array = json_decode($arr['change_log']);
                        $name = $logs_array->name;
                        $loads = $logs_array->loads;
                        $plays = $logs_array->plays;
                        $viewers = $logs_array->viewers;
                    ?>
                    <ul>
                        <li><?php echo trim($name);?></li>
                        <li><?php echo trim($loads);?></li>
                        <li><?php echo trim($plays);?></li>
                        <li><?php echo trim($viewers);?></li>
                    </ul>
                    </td>
                </tr>
                <?php } 
            }else{ ?>
                <tr>
                    <td colspan="3" class="text-center">No Records</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
   </div>
