<section class="middle-section">
    <div class="container">
        <div class="mid-content">
            <div class="video-section">
                <div class="video"><img src="<?php echo base_url() ?>media/front/img/video-bg.png"/></div>
                <div class="login-btn"> <a href="<?php echo base_url() ?>sign-in" class="btn sign-btn pull-left">Sign in</a> <a href="<?php echo base_url() ?>sign-up" class="btn sign-btn pull-right">Register</a> </div>
            </div>
            <div class="testimonial-section">
                <div class="owl-carousel">
                    <?php foreach ($arr_testimonials as $testimonial) :
                        if($testimonial['testimonial_img'] != ''){
                            $testimonial_img = base_url().'media/backend/img/testimonial_image/thumbs/'.$testimonial['testimonial_img'];
                        }else{
                            $testimonial_img = base_url().'media/front/img/avatar.png';
                        }
                         
                        ?>
                        <div class="item">
                            <div class="testimonial-user">
                                <div class="testimonial-img"><img src="<?php echo $testimonial_img ?>" alt="user image" height="100%" width="100%"/></div>
                                <h3><?php echo ucfirst($testimonial['first_name']).' '.ucfirst($testimonial['last_name']); ?></h3>
                                <p><b><?php echo ucfirst(stripcslashes($testimonial['name']));?></b></p>
                                <p><?php echo ucfirst(stripcslashes($testimonial['testimonial']))?></p>
                                <p><?php echo date($global['date_format'],  strtotime($testimonial['added_date']));?></p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>