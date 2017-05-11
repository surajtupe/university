<hr />
<footer>
    <div class="footer-links">
        <h4>General</h4>
        <ul>
            <li><a href="<?php echo base_url() ?>cms/about-us">About us</a></li>
            <li><a href="<?php echo base_url() ?>cms/careers">Careers</a></li>
            <li><a href="<?php echo base_url() ?>cms/reviews">Reviews</a></li>
            <li><a href="<?php echo base_url() ?>cms/media-rooms">Media rooms</a></li>
            <!--<li><a href="javascript:void(0);">Testimonials</a></li>-->
            <li><a href="<?php echo base_url() ?>blog">Blog</a></li>
        </ul>
    </div>
    <div class="footer-links">
        <h4>Legal</h4>
        <ul>
            <li><a href="<?php echo base_url() ?>cms/terms-of-use">Terms of use</a></li>
            <li><a href="<?php echo base_url() ?>cms/privacy-statement">Privacy statement</a></li>
            <li><a href="<?php echo base_url() ?>faqs">FAQ’s</a></li>
        </ul>
    </div>
    <div class="footer-links">
        <h4>Get in Touch</h4>
        <ul>
            <li><a href="<?php echo $global['facebook_link'] ?>" target="_blank">Facebook</a></li>
            <li><a href="<?php echo $global['twitter_link'] ?>" target="_blank">Twitter</a></li>
            <li><a href="<?php echo base_url() ?>contact-us">Contact us</a></li>
        </ul>
    </div>
    <div class="footer-links">
        <h4>Downloads</h4>
        <ul>
            <li><a href="<?php echo $global['ios_app_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/google-app.png" alt="App Image"/></a></li>
            <li><a href="<?php echo $global['android_app_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/google-android.png" alt="App Image"/></a></li>
        </ul>
    </div>
    <div class="footer-links social-links">
        <h4>Tell a friend</h4>
        <ul>
            <li><a href="<?php echo $global['facebook_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/facebbok.png" alt="Facebook"/></a></li>
            <li class="li-margin"><a href="<?php echo $global['twitter_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/twitter.png" alt="Twitter"/></a></li>
            <li><a href="<?php echo $global['Google+_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/envelope.png" alt="Email"/></a></li>
            <br />
            <li><a href="<?php echo $global['youtube_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/youtube.png" alt="You Tube"/></a></li>
            <li class="li-margin"><a href="<?php echo $global['instagram_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/instagram.png" alt="Instagram"/></a></li>
            <li><a href="<?php echo $global['linkedin_link'] ?>" target="_blank"><img src="<?php echo base_url() ?>media/front/img/linked-in.png" alt="Linked In"/></a></li>
        </ul>
    </div>
</footer>
<script>
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })
</script>
</body>
</html>
