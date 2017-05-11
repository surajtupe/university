<div class="middle-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="main-title"><span>Add Blog Post</span></h1>
                <div class="banner-login-box sign-up-box">
                    <form name="form_add_blog" id="form_add_blog" action="<?php echo base_url(); ?>blog/add-post-front" method="post" enctype="multipart/form-data"> 
                        <div class="ban-login-inpt">
                            <input name="post_title" id="post_title" type="text" placeholder="Enter blog title" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter blog title'" class="input-white border-input">
                        </div>
                        <div class="ban-login-inpt">
                            <input name="user_name" id="user_name" type="text" placeholder="Enter your name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" class="input-white border-input">
                        </div>
                        <div class="ban-login-inpt">
                            <input  id="blog_image" name="blog_image" type="file">
                        </div>
                        <div class="ban-login-inpt">
                            <select name="category_id" id="category_id" class="input-white border-input">
                                <option value=''> -Select Category- </option>
                                <?php
                                foreach ($arr_categories as $blog_category) {
                                    ?>
                                    <option class="input-white border-input" value="<?php echo $blog_category['category_id']; ?>"><?php echo $blog_category['category_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="ban-login-inpt">
                            <textarea name="post_short_description" id="post_short_description" rows="4" cols="50" class="input-white border-input"> </textarea>
                        </div>
                        <div class="ban-login-inpt">
                            <textarea name="post_content" id="post_content" rows="4" cols="50" class="input-white border-input"></textarea>
                        </div>
                        <div class="ban-login-inpt">
                            <input type="submit" name="btn_submit" class="signup-btn" id="btn_submit" value="Post">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showAnonymous()
    {
        $("#anonymous_name").css("display", "block");
    }

    function hideAnonymous()
    {
        $("#anonymous_name").css("display", "none");
    }
(function($) {
    $.fn.checkFileType = function(options) {
        var defaults = {
            allowedExtensions: [],
            success: function() {},
            error: function() {}
        };
        options = $.extend(defaults, options);

        return this.each(function() {

            $(this).on('change', function() {
                var value = $(this).val(),
                    file = value.toLowerCase(),
                    extension = file.substring(file.lastIndexOf('.') + 1);

                if ($.inArray(extension, options.allowedExtensions) == -1) {
                    options.error();
                    $(this).focus();
                } else {
                    options.success();

                }

            });

        });
    };

})(jQuery);

$(function() {
    $('#blog_image').checkFileType({
        allowedExtensions: ['jpg', 'jpeg','png','gif'],
        
        error: function() {
            $('#blog_image').replaceWith($('#blog_image').val('').clone(true));
            alert('Please upload only jpg,jpeg,png,gif type file.');
        }
    });

});
</script>