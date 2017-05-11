<!--<body class="inner-pages inner-section">
    <header>
        <div class="text-center logo-bg">
            <a href="<?php echo base_url();?>"><img src="<?php echo base_url() ?>media/front/img/inner-logo.png"/></a>
            <p>UPDATE YOUR ADDRESS SMARTLY
                <?php if (isset($user_account) && $user_account['user_id'] != '') { ?>
                    <a class="pull-right signout" href="<?php echo base_url() ?>logout">Sign out</a>
                <?php } ?>
            </p>
        </div>
    </header>-->
    <section class="middle-section">
        <div class="container">
            <h2 class="text-uppercase"><?php echo stripslashes($arr_cms[0]['page_title']); ?></h2>
            <p><?php echo stripslashes($arr_cms[0]['page_content']); ?></p>     
        </div>
    </section>