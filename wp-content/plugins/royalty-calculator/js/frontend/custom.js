var $ = jQuery;
$(document).ready(function () {
    if (window.matchMedia("(max-width: 767px)").matches) {
        // The viewport is less than 768 pixels wide
//        alert("This is a mobile device.");
        $('#contentmedia').removeClass('active');
        $('#gridimagecontent').addClass('active');
        $('.lightbox').css('display','none');
        $('.imageContent-style .swiper-button-next').click(function (e) {
            $(".imageContent-container .left-content").css({"z-index": "", "opacity": ""});
        });
        var swiper2 = new Swiper(".imageContent-style-mobile", {
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '"><span>' + (index + 1) + "</span></span>";
                },
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            }
        });
        
        $("#gridimagecontent .swiper-slide").on("click", function () {
            setTimeout(function(){
                if(window.matchMedia("(max-width: 767px)").matches){
                    var image_grid_div = jQuery('.lightbox').remove();
                    image_grid_div.appendTo('#gridimagecontent');
                    var image_overlay_div = jQuery('.modal-backdrop').remove();
                    image_overlay_div.appendTo('#gridimagecontent');
                    jQuery('body').css({"overflow":"","padding-right":""});
                    jQuery('.lightbox ').css({"padding-right":""});
					jQuery('#gridimagecontent').addClass('fixed-height');   
                }
            },180);    
        });
    }
//    else{
//        // The viewport is at least 768 pixels wide
////        alert("This is a tablet or desktop.");
//    }
    var swiper = new Swiper(".image-first-tab-style", {
        slidesPerView: "4",
        // centeredSlides: true,
        spaceBetween: 15,
        autoHeight: true,
        allowTouchMove: false,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        grid: {
            rows: 3,
        },
        breakpoints: {
            320: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            1100: {
                slidesPerView: 4,
                spaceBetween: 15,
            }
        }
    });
    var swiper = new Swiper(".imageContent-style", {
        slidesPerView: "auto",
        centeredSlides: false,
        spaceBetween: 20,
        autoHeight: true,
        allowTouchMove: false,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
            480: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 4,
                spaceBetween: 15,
            }
        }
    });
//        

    $(".imageContent-style .swiper-slide:first-child").addClass("offset-md-3");
    $('.imageContent-style .swiper-button-next').click(function (e) {
        $(".imageContent-container .left-content").css({"z-index": "1", "opacity": "0"});
        //var swiper = new Swiper(".imageContent-style", {
        //centeredSlides: true,
        //breakpoints: {
        //768: {
        // slidesPerView: 4,
        // }
        //}
        //});
    });
    $('.imageContent-style .swiper-button-prev').click(function (e) {
        var str = $('.imageContent-style .swiper-slide-active').attr('aria-label');
        if (str.substring(0, str.lastIndexOf(" / ") + 1) == "2 ") { //$('.swiper-button-prev').attr('tabindex') == -1
            console.log(str.substring(0, str.lastIndexOf(" / ") + 1));
            $(".imageContent-container .left-content").css({"z-index": "2", "opacity": "1"});
        } else {
            $(".imageContent-container .left-content").css({"z-index": "1", "opacity": "0"});
        }
    });
    $('.imageContent-container .swiper-pagination-bullets span').click(function (e) {
        // console.log(jQuery('.swiper-pagination-bullet-active').attr('aria-label'));
        // console.log(jQuery(this).attr('aria-label'),'========',jQuery( this ).hasClass( "swiper-pagination-bullet-active" ));
        if (($(this).attr('aria-label') == 'Go to slide 1') && ($('.swiper-pagination-bullet-active').attr('aria-label') == 'Go to slide 2' )) {
            $(".imageContent-container .left-content").css({"z-index": "2", "opacity": ""});
        } else{
            $(".imageContent-container .left-content").css({"z-index": "2", "opacity": "0"});
        }
    });

});
$('.imageContent-style .swiper-button-next').click(function (e) {
    $(".imageContent-style .swiper-slide:first-child").removeClass("offset-md-3");
});
$('.imageContent-style .swiper-button-prev').click(function (e) {
    $(".imageContent-style .swiper-slide:first-child").addClass("offset-md-3");
});
