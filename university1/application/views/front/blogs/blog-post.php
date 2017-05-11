<script src="<?php echo base_url(); ?>media/front/js/blog-post.js"></script>
<script>
    var SITE_PATH = '<?php echo base_url(); ?>';
    setInputP('<?php echo $post_id; ?>');
</script>
<section class="middle-section">
    <div class="container">
        <?php
        if ($this->session->userdata('blog_comment')) {
            ?>
            <div class="msg_close alert alert-success">
                <?php echo $this->session->userdata('blog_comment'); ?>
                <button class="close" id="msg_close" name="msg_close" data-dismiss="alert" type="button">x</button>
            </div>
            <?php
        }
        $this->session->unset_userdata('blog_comment');
        ?>
        <div class="mid-section">
            <div class="head">
                <h2>Blog Detail</h2>
            </div>
        </div>
        <div class="row">
            <div class="blog-det-cont">
                <?php
                foreach ($blog_posts as $post) {
                    ?>
                    <div class="col-md-8">
                        <div class="blog-det-left">
                            <h2><?php echo ucfirst($post["post_title"]); ?></h2>
                            <div style="background-image:url(<?php
                            if ($post['blog_image'] != '') {
                                echo base_url() . 'media/backend/img/blog_image/thumbs/' . $post['blog_image'];
                            } else {
                                echo base_url() . 'media/front/img/blog1.png';
                            }
                            ?>);" class="blog-img-det"></div>
                            <p><?php echo stripslashes(ucfirst($post["post_short_description"])); ?></p>
                            <p><?php echo stripslashes(ucfirst($post["post_content"])); ?></p>

                            <div class="links">
                                <span><i class="glyphicon glyphicon-tag"></i> <a href="javascript:void(0)"><?php echo ucfirst($post["category_name"]); ?></a></span>
                                <span><i class="fa fa-calendar"></i> <a href="javascript:void(0)"><?php echo date("F d, Y", strtotime($post["posted_on"])); ?></a></span>
                                <span><i class="fa fa-user"></i> <a href="javascript:void(0)"><?php echo ucfirst($post["first_name"]) . " " . ucfirst($post["last_name"]); ?></a></span>
                                <span><i class="fa fa-comment"></i> <a href="javascript:void(0)"><?php echo $comment_count; ?> Comments</a></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if (count($post_comments) > 0) {
                        foreach ($post_comments as $comment) {
                            ?>
                            <div class="blog-de-media">
                                <div class="media">
                                    <div class="media-left">
                                        <div class="medi-blog-img">
                                            <?php if (isset($comment['profile_picture']) && $comment['profile_picture'] != '') { ?>
                                                <img src="<?php echo base_url(); ?>media/front/img/user-profile-pictures/thumb/<?php echo $comment['profile_picture']; ?>" alt="Image Not Available"/>
                                            <?php } else { ?>
                                                <img src="<?php echo base_url(); ?>media/front/img/avatar.png" alt="Image Not Available"/>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading"><?php
                                            if (isset($post_id)) {
                                                echo $comment["commented_by"];
                                            }
                                            ?> </h4>
                                        <p><?php echo nl2br(stripslashes($comment["comment"])); ?></p>
                                        <div class="medi-date"><?php echo date("d<\s\up>S</\s\up> F, Y", strtotime($comment["comment_on"])); ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo 'Comments not found.';
                    }
                    ?>
                </div>
                <div class="col-md-4">
                    <a href="<?php echo base_url()?>blog" class="pull-right back-btn">BACK</a>
                    <div class="blog-det-right">
                        <h2>leave comment</h2>
                        <form name="frmComments" id="frmComments" method="post">
                            <div class="form-group">
                                <input type="type" placeholder="Enter your name here" value="<?php echo $user_session['first_name'] ? $user_session['first_name'] . ' ' . $user_session['last_name'] : '' ?>" name="posted_by" id="posted_by" class="form-control">
                            </div>
                            <div class="form-group">
                                <textarea rows="" class="form-control" name="inputComment" id="inputComment" placeholder="Write your comments here..."></textarea>
                            </div>
                            <input type="hidden" id="user_id" name="user_id"  value="<?php echo $user_session['user_id'] ? $user_session['user_id'] : ''; ?>"/>
                            <button id="btn_post_comment" class="btn blue-btn" name="btn_post_comment" class="btn signup-btn">Post your comment</button> 
                            <img name="btn_loader" id="btn_loader" style="display: none;" src="<?php echo base_url() ?>media/front/img/loader.gif"/>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</section>