<section class="middle-section">
    <div class="container">
        <div class="mid-section">
            <div class="head">
                <h2>BLOGS</h2>
            </div></div>
        <div class="row">
            <?php
            if (isset($blog_posts)) {
                foreach ($blog_posts as $post) {
                    ?>
                    <div class="col-md-4">
                        <a href="<?php echo base_url(); ?>blog/<?php echo $post["page_url"]; ?>">
                            <div class="blog-outer">
                                <div class="media">
                                    <div class="media-left">
                                        <div class="left-date"><?php echo date("d,M", strtotime($post["posted_on"])); ?></div>
                                    </div>
                                    <div class="media-body clearfix">
                                        <h4 class="media-heading"><?php echo ucfirst($post["post_title"]); ?></h4>
                                        <div><i class="fa fa-comment-o"></i> <?php echo $post["comment_count"] ?> Comments</div>
                                        <div style="background-image:url(<?php
                                        if ($post['blog_image'] != '') {
                                            echo base_url() . 'media/backend/img/blog_image/thumbs/' . $post['blog_image'];
                                        } else {
                                            echo base_url() . 'media/front/img/blog1.png';
                                        }
                                        ?>); height: 200px; width: 100%;" class="img-div-blog"></div>
                                        <p><?php
                                            if (strlen($post["post_short_description"]) > 80) {
                                                echo substr($post["post_short_description"], 0, 60) . '....';
                                            } else {
                                                echo stripslashes($post["post_short_description"]);
                                            }
                                            ?></p>
                                        <button class="btn blue-btn" type="button" >Read More</button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="text-center">
                    <img src="<?php echo base_url() ?>media/front/img/DataNotFound.jpg" class="text-center" />
                </div>
            <?php }
            ?>
        </div>
        <?php if ($create_links != '') { ?>
            <div class="text-center pagination-box">
                <?php echo $create_links; ?>
            </div>
        <?php }
        ?>
    </div>
</section>
<style>
    .pagination-section{
        padding:10px 30px;
    }
    .paginationPara a{
        color: #fff;
        display:inline-block;
        height:30px;
        min-width:30px;
        font-size:14px;
        font-weight:bold;
        padding:0 12px;
        vertical-align: middle;
        border-radius:5px;
        -moz-border-radius:5px;
        -webkit-border-radius:5px;
        box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -moz-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -webkit-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        background:#414141;
        margin:2px;
        line-height:30px;

    }
    .paginationPara strong{
        color: #fff;
        display:inline-block;
        height:30px;
        width:30px;
        font-size:14px;
        font-weight:bold;
        padding:0 12px;
        border-radius:5px;
        -moz-border-radius:5px;
        -webkit-border-radius:5px;
        box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -moz-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -webkit-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        background:#33CAFF;
        margin:2px;
        line-height:30px;
    }
</style>