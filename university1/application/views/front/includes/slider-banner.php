<div class="top-ban-cont-sec"  id="home" data-scroll-index="1">
    <section class="banner-slider">
        <div class="owl-carousel" id="banner">
            <?php if(count($arr_slider_banner_objects)>0) { foreach($arr_slider_banner_objects as $key => $banner) { ?>
            <div class="fullHt item" style="background-image:url(<?php echo base_url(); ?>media/front/img/slider-banner/thumbs/<?php echo $banner['banner_object_image'] ;  ?>);">
                <div class="text-caption" data-anchor-target="#banner" data-top="opacity:1;" data-top-bottom="opacity:0;">
                    <div class="container">
                        <div class="inner-banner">
                            <h1><?php echo stripslashes(ucfirst($banner['banner_object_title'])) ;  ?></h1>
                            <p><?php echo  stripcslashes(preg_replace("/[\\n\\r]+/", " ", $banner['banner_object_description_text'])) ;  ?></p>
                            <div class="apps-btn">
                                <a href="javascript:void(0)"><img src="<?php echo base_url(); ?>media/front/img/iphone-btn.png"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    <input type="hidden" id="banner_count" name="banner_count" value="<?php echo count($arr_slider_banner_objects) ; ?>">
    </section>
</div>