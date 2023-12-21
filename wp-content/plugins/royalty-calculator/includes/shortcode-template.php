<div class="imageContent-container container-fluid">
<div class="row">
        <div class="col-3 left-content">
            <h2><?php echo do_shortcode($shortcode['name']); ?></h2>
            <p><?php echo do_shortcode($shortcode['content']); ?></p>
            <a type="button" class="btn btn-primary" href="<?php echo $shortcode_mobile['button_url'] ?>">
                <?php echo $shortcode_mobile['button_text'] ?>
            </a>                                     
        </div>
        <?php       

        global $wpdb;
        $val = $shortcode['content_slider_id'];
        $shortcode_data = $wpdb->get_results("SELECT * FROM content_data WHERE id IN ($val) order by field(id,$val)", ARRAY_A);

        ?>
        <?php  if(count($shortcode_data) <= 3){  ?>
              <div class="col-12 pe-4">   
        <?php } else {  ?>
             <div class="col-12 p-0">
        <?php } ?>    
            <div class="tabs-container mobile-tab">
                <ul class="nav nav-tabs" data-bs-tabs="tabs" >
                    <li class="nav-item">
                        <a class="nav-link active tabContentOne" onclick="closeContentLightBox()" aria-current="true" data-bs-toggle="tab" href="#gridimagecontent">
                            <span>
                                <svg id="Layer_1"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" width="15px">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: none;
                                                stroke: #D9D9D9;
                                                stroke-miterlimit: 10;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="cls-1" x="1.47" y="1.47" width="12.05" height="12.05" />
                                    <line class="cls-1" x1="1.47" y1="9.51" x2="13.53" y2="9.51" />
                                    <line class="cls-1" x1="1.47" y1="5.49" x2="13.53" y2="5.49" />
                                    <line class="cls-1" x1="9.51" y1="1.47" x2="9.51" y2="13.53" />
                                    <line class="cls-1" x1="5.49" y1="1.47" x2="5.49" y2="13.53" />
                                </svg>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link tabContentTwo" onclick="openContentLightBox()" data-bs-toggle="tab" href="#">
                            <span>
                                <svg id="Layer_1"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" width="15px">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: none;
                                                stroke: #D9D9D9;
                                                stroke-miterlimit: 10;
                                                stroke-width: 1px;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="cls-1" x="1.5" y="1.5" width="12" height="12" />
                                </svg>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane" id="gridimagecontent">
                    <div class="swiper image-first-tab-style media-style">
                        <div class="swiper-wrapper">
                            <?php
                           
                            $i = 1;
                            
                            $displayTitle = array();
                            $count = count($shortcode_data);
                            if (!empty($shortcode_data)) {
                                $levelCount = 0;
                                foreach ($shortcode_data as $shortcode_data_arr) {
                                    $levelCount = $levelCount+1;
                                    $titleArray = array('title'=>$shortcode_data_arr['title'],'detail'=>$shortcode_data_arr['description']);
                                                        array_push($displayTitle,$titleArray);
                                    ?>
                                    <div class="swiper-slide">
                                        <a href="<?php echo $shortcode_data_arr['image']; ?>" data-gallery="content-image-gallery" class="slide-img content-slider-image <?php echo ($levelCount == 1) ? 'contabLightbox' : '' ?>" data-toggle="lightbox" style="background-image: url('<?php echo $shortcode_data_arr['image']; ?>');">
                                        </a>
                                    </div>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div> 
                </div>
                <div class="tab-pane active" id="contentmedia">
                    <div class="swiper-container imageContent-style">
                        <div class="swiper-wrapper">
                            <?php
                            global $wpdb;
                            $val = $shortcode_mobile['content_slider_id'];
                            $shortcode_data = $wpdb->get_results("SELECT * FROM content_data WHERE id IN ($val) order by field(id,$val)", ARRAY_A);
                            $i = 1;
                            $count = count($shortcode_data);
                            if (!empty($shortcode_data)) {
                                foreach ($shortcode_data as $shortcode_data_arr) {
                                    ?>
                                    <div class="swiper-slide">
                                        <div class="slide-img"
                                             style="background-image: url('<?php echo $shortcode_data_arr['image']; ?>');background-position: top <?php echo $shortcode_data_arr['image_position']; ?>;">
                                        </div>                
                                        <div class="image-content">
                                            <h2><?php echo $shortcode_data_arr['title']; ?></h2>
                                            <p><?php echo $shortcode_data_arr['description']; ?></p>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
							
							<?php  if(count($shortcode_data) > 3){  ?>
                            <div class="swiper-slide">
                                <div class="slide-img"
                                        style="background-image: url('');border-color:transparent !important;background-position: top <?php echo $shortcode_data_arr['image_position']; ?>;">
                                </div>
                            </div>
                             <div class="swiper-slide">
                                <div class="slide-img"
                                        style="background-image: url('');border-color:transparent !important;background-position: top <?php echo $shortcode_data_arr['image_position']; ?>;">
                                </div>
                            </div>
                        <?php } ?>
                        </div>

                        <?php  if(count($shortcode_data) > 3){  ?>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination swiper-pagination-white"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
    jQuery('.content-slider-image').click(function () {
        if(window.matchMedia("(max-width: 767px)").matches){
            var jsn = <?php echo json_encode($displayTitle); ?>;
            var titleBlockCount = 0;
            setTimeout(function(){
                jQuery('.tabContentOne').removeClass('active');
                jQuery('.tabContentTwo').addClass('active');

                let activeIndex = '';
                jQuery('.lightbox .carousel-item').each(function(i){
                    var singleTitle = jsn[i];
                    var titleBlock = '<div class="site-title-block"><h3>'+singleTitle.title+'</h3><p>'+singleTitle.detail+'</p></div>';
                    jQuery(this).append(titleBlock);
                    if(jQuery(this).hasClass("active") == true){
                        activeIndex = i;
                    }
                    titleBlockCount = titleBlockCount+1; 
                });
                let paginationList = '';
                let temppaginationList = '';
                let devideLevel = Math.ceil(titleBlockCount/5);
                let activeRow = Math.floor(activeIndex/5);
                let endCount = 1;
                let appendPagination = 0;
                for(let y=0;y<devideLevel;y++){
                     let startPoint = y*5;
                     if(startPoint > 0){
                        startPoint = startPoint-1;
                     }
                     let endPoint = endCount*5;
                     let activeListCount = 0;
                     for(let j=startPoint;j<endPoint;j++){
                        if(j<titleBlockCount){
                            let listCount = j+1;
                            if(activeIndex == j){ 
                                if(startPoint > 0){
                                    if(j > startPoint){
                                        temppaginationList += '<li class="nav-item active"><a class="nav-link disabled" onclick="activeBigLightBox('+j+','+activeListCount+')">'+listCount+'</a></li>';
                                    }                                    
                                }else{
                                    temppaginationList += '<li class="nav-item active"><a class="nav-link disabled" onclick="activeLightBox('+j+')">'+listCount+'</a></li>';                                    
                                }
                                appendPagination = 1;                               
                            }else{
                                if(startPoint > 0){
                                    if(j > startPoint){
                                        temppaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeBigLightBox('+j+','+activeListCount+')">'+listCount+'</a></li>';
                                    }    
                                }else{
                                    temppaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeLightBox('+j+')">'+listCount+'</a></li>';
                                }
                            }
                            activeListCount = activeListCount+1;
                        }                       
                    }
                    if(appendPagination == 1){
                        paginationList = temppaginationList;
                        appendPagination = 0;
                        temppaginationList = '';
                    }else{
                        temppaginationList = '';
                    }
                    endCount = endCount+1;
                }
                let paginationBlock = '<ul class="nav lightbox-pagination-ul">'+paginationList+'</ul>';
                jQuery('.lightbox-carousel').append(paginationBlock);
                let nextLi = activeIndex+1;
                let previousLi = activeIndex-1;
                var paginationCount = jQuery('#gridimagecontent .lightbox .carousel-inner .carousel-item').length-1;
                if(activeIndex == 0){
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'none');
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+paginationCount+');');
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
                }else if(activeIndex == paginationCount){
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'none');
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton(0);');    
                }else{
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'auto');
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'auto');
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
                    jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
                }
            }, 1000);
        }
    });
    function activeLightBox(active_id){
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').removeAttr('onclick');
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').removeAttr('onclick');
        let activeBlockCount = 0;
        jQuery('.lightbox .carousel-item').each(function(i){
            if(active_id == i){
                jQuery(this).addClass("active");
            }else{
                jQuery(this).removeClass('active');
            }
            activeBlockCount = activeBlockCount+1; 
        });
        jQuery('.lightbox-pagination-ul li').each(function(i){
            if(active_id == i){
                jQuery(this).addClass("active");
                jQuery(this).children("a").addClass("disabled");
            }else{
                jQuery(this).removeClass("active");
                jQuery(this).children("a").removeClass("disabled");        
            }
        });
        let activePoint = (active_id+1)%5;
        if(activePoint == 0){
            
            jQuery('.lightbox-pagination-ul').remove();
            let activeendPoint = active_id+6;
            let activestartPoint = active_id+1;
            
            let activepaginationList = '';
            let acttiveCount = 0;
            for(let j=activestartPoint;j<activeendPoint;j++){
                if(j<activeBlockCount){
                    let activelistCount = j+1;
                    if(active_id == j){
                        activepaginationList += '<li class="nav-item active" ><a class="nav-link" onclick="activeBigLightBox('+j+','+acttiveCount+')">'+activelistCount+'</a></li>';
                    }else{
                        activepaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeBigLightBox('+j+','+acttiveCount+')">'+activelistCount+'</a></li>';
                    }
                    acttiveCount = acttiveCount+1;
                }                       
            }
            
            let activepaginationBlock = '<ul class="nav lightbox-pagination-ul">'+activepaginationList+'</ul>';
            jQuery('.lightbox-carousel').append(activepaginationBlock);
        }
        let previousLi = active_id-1;
        let nextLi = active_id+1;
        var paginationCount = jQuery('#gridimagecontent .lightbox .carousel-inner .carousel-item').length-1;
        if(active_id == 0){
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'none');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+paginationCount+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
        }else if(active_id == paginationCount){
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'none');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton(0);');    
        }else{
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'auto');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'auto');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
        }
    }
    function activeBigLightBox(list_active_id,active_id){
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').removeAttr('onclick');
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').removeAttr('onclick');
        let activeBlockCount = 0;
        jQuery('.lightbox .carousel-item').each(function(i){
            if(list_active_id == i){
                jQuery(this).addClass("active");
            }else{
                jQuery(this).removeClass('active');
            }
            activeBlockCount = activeBlockCount+1; 
        });
        jQuery('.lightbox-pagination-ul li').each(function(i){
            if(active_id == i){
                jQuery(this).addClass("active");
                jQuery(this).children("a").addClass("disabled");
            }else{
                jQuery(this).removeClass("active");
                jQuery(this).children("a").removeClass("disabled");        
            }
        });
        let activePoint = (list_active_id+1)%5;
        if(activePoint == 0){
           
            jQuery('.lightbox-pagination-ul').remove();
            let activeendPoint = list_active_id+6;
            let activestartPoint = list_active_id+1;
           
            let activepaginationList = '';
            let acttiveCount = 0;
            for(let j=activestartPoint;j<activeendPoint;j++){
                if(j<activeBlockCount){
                    let activelistCount = j+1;
                    if(list_active_id == j){
                        activepaginationList += '<li class="nav-item active" ><a class="nav-link" onclick="activeBigLightBox('+j+','+acttiveCount+')">'+activelistCount+'</a></li>';
                    }else{
                        activepaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeBigLightBox('+j+','+acttiveCount+')">'+activelistCount+'</a></li>';
                    }
                    acttiveCount = acttiveCount+1;
                }                       
            }
           
            let activepaginationBlock = '<ul class="nav lightbox-pagination-ul">'+activepaginationList+'</ul>';
            jQuery('.lightbox-carousel').append(activepaginationBlock);
        }
        let previousLi = list_active_id-1;
        let nextLi = list_active_id+1;
        var paginationCount = jQuery('#gridimagecontent .lightbox .carousel-inner .carousel-item').length-1;
        if(list_active_id == 0){
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'none');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+paginationCount+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
        }else if(list_active_id == paginationCount){
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'none');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton(0);');    
        }else{
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'auto');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'auto');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
        }
    }

    function nextContentButton(active_index){
        
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').removeAttr('onclick');
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').removeAttr('onclick');
        var indx = jQuery('#gridimagecontent .lightbox .carousel-inner div.active').index();
        var arrayLen = jQuery('#gridimagecontent .lightbox .carousel-inner div.active').length-1;
        var listIndex = jQuery('#gridimagecontent .lightbox-pagination-ul li.active').index()+1;
        var nextPaginationCount = jQuery('#gridimagecontent .lightbox .carousel-inner .carousel-item').length-1;
        let nactiveBlockCount = 0;
        jQuery('.lightbox .carousel-item').each(function(i){
            if(active_index == i){
                jQuery(this).addClass("active");           
            }else{
                jQuery(this).removeClass("active");        
            }
            nactiveBlockCount = nactiveBlockCount+1; 
        });            
        jQuery('.lightbox-pagination-ul li').each(function(i){
            if(listIndex == i){
                jQuery(this).addClass("active");
                jQuery(this).children("a").addClass("disabled");
            }else{
                jQuery(this).removeClass("active");
                jQuery(this).children("a").removeClass("disabled");        
            }
        });
        let previousLi = active_index-1;
        let nextLi = active_index+1;
        let nextactivePoint = (nextLi-1)%5;
        if(nextactivePoint == 0){
           
            jQuery('.lightbox-pagination-ul').remove();
            let nactiveendPoint = active_index+5;
            let nactivestartPoint = active_index;
           
            let nactivepaginationList = '';
            let nextacttiveCount = 0;
           
            for(let j=nactivestartPoint;j<nactiveendPoint;j++){
                if(j<nactiveBlockCount){
                    let nactivelistCount = j+1;
                    if(active_index == j){
                        if(active_index > 3){
                            nactivepaginationList += '<li class="nav-item active" ><a class="nav-link" onclick="activeBigLightBox('+j+','+nextacttiveCount+')">'+nactivelistCount+'</a></li>';
                        }else{
                            nactivepaginationList += '<li class="nav-item active" ><a class="nav-link" onclick="activeLightBox('+j+')">'+nactivelistCount+'</a></li>';
                        }    
                    }else{
                        if(active_index > 3){
                            nactivepaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeBigLightBox('+j+','+nextacttiveCount+')">'+nactivelistCount+'</a></li>';
                        }else{
                            nactivepaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeLightBox('+j+')">'+nactivelistCount+'</a></li>';
                        }
                    }
                    nextacttiveCount = nextacttiveCount+1;
                }                       
            }
         
            let nextactivepaginationBlock = '<ul class="nav lightbox-pagination-ul">'+nactivepaginationList+'</ul>';
            jQuery('.lightbox-carousel').append(nextactivepaginationBlock);
        }
        if(active_index == 0){
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'none');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+nextPaginationCount+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
        }else if(active_index == nextPaginationCount){
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'none');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton(0);');    
        }else{
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'auto');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'auto');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
            jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
        }  
    }    
    function previousContentButton(active_index){
        
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').removeAttr('onclick');
        jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').removeAttr('onclick');
        var indx = jQuery('#gridimagecontent .lightbox .carousel-inner div.active').index();      
        var arrayLen = jQuery('#gridimagecontent .lightbox .carousel-inner div.active').length-1;
        var listIndex = jQuery('#gridimagecontent .lightbox-pagination-ul li.active').index()-1;
        var prePaginationCount = jQuery('#gridimagecontent .lightbox .carousel-inner .carousel-item').length-1;
        if(prePaginationCount == active_index){

        }else{
            let nactiveBlockCount = 0;    
            jQuery('.lightbox .carousel-item').each(function(i){
                if(active_index == i){
                    jQuery(this).addClass("active");           
                }else{
                    jQuery(this).removeClass("active");        
                }
                nactiveBlockCount = nactiveBlockCount+1;
            });            
            jQuery('.lightbox-pagination-ul li').each(function(i){
                if(listIndex == i){
                    jQuery(this).addClass("active");
                    jQuery(this).children("a").addClass("disabled");
                }else{
                    jQuery(this).removeClass("active");
                    jQuery(this).children("a").removeClass("disabled");        
                }
            });
            let previousLi = active_index-1;
            let nextLi = active_index+1;
            let nextactivePoint = (nextLi)%5;
            if(nextactivePoint == 0){
             
                jQuery('.lightbox-pagination-ul').remove();
                let nactiveendPoint = active_index+1;
                let nactivestartPoint = 0;
                if(active_index > 4){
                    nactivestartPoint = nextLi-5;
                }else{
                    nactivestartPoint = 0;
                }
             
                let nactivepaginationList = '';
                let nextacttiveCount = 0;
                for(let j=nactivestartPoint;j<nactiveendPoint;j++){
                    if(j<nactiveBlockCount){
                        let nactivelistCount = j+1;
                        if(active_index == j){
                            if(active_index > 4){
                                nactivepaginationList += '<li class="nav-item active" ><a class="nav-link" onclick="activeBigLightBox('+j+','+nextacttiveCount+')">'+nactivelistCount+'</a></li>';
                            }else{
                                nactivepaginationList += '<li class="nav-item active" ><a class="nav-link" onclick="activeLightBox('+j+')">'+nactivelistCount+'</a></li>';
                            }    
                        }else{
                            if(active_index > 4){
                                nactivepaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeBigLightBox('+j+','+nextacttiveCount+')">'+nactivelistCount+'</a></li>';
                            }else{
                                nactivepaginationList += '<li class="nav-item" ><a class="nav-link" onclick="activeLightBox('+j+')">'+nactivelistCount+'</a></li>';
                            }
                        }
                        nextacttiveCount = nextacttiveCount+1;
                    }                       
                }
             
                let nextactivepaginationBlock = '<ul class="nav lightbox-pagination-ul">'+nactivepaginationList+'</ul>';
                jQuery('.lightbox-carousel').append(nextactivepaginationBlock);
            }
            if(active_index == 0){
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'none');
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+prePaginationCount+');');
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
            }else if(active_index == prePaginationCount){
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'none');
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton(0);');    
            }else{
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').css('pointer-events', 'auto');
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').css('pointer-events', 'auto');
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-prev').attr('onclick', 'previousContentButton('+previousLi+');');
                jQuery('#gridimagecontent .lightbox-carousel .carousel-control-next').attr('onclick', 'nextContentButton('+nextLi+');');    
            }
        }
    }
    function closeContentLightBox(){
        jQuery('#gridimagecontent .modal').modal('hide');
        jQuery('.tabContentTwo').removeClass('active');
        jQuery('.tabContentOne').addClass('active');
		jQuery('#gridimagecontent').removeClass('fixed-height');   
    }
    function openContentLightBox(){
        jQuery("#gridimagecontent .contabLightbox")[0].click();
    }
</script>