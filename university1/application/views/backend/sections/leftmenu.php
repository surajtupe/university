<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left info">
                <p>Hello, <?php echo $user_account['first_name'] . " " . $user_account['last_name']; ?></p>
                <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i> Logged In</a>
            </div>
        </div>
        <?php if ($user_account['role_id'] == 1) { ?>
            <ul class="sidebar-menu">
                <li <?php if ($this->uri->segment(2) == 'dashboard') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/dashboard">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li <?php if ($this->uri->segment(2) == 'global-settings') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/global-settings/list" >
                        <i class="fa fa-gear"></i> <span>Manage Global Settings</span> 
                    </a>
                </li>
                <li <?php if ($this->uri->segment(2) == 'role') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/role/list">
                        <i class="fa fa-fw fa-eye"></i> <span>Manage Roles</span> 
                    </a>
                </li>
                <li <?php if ($this->uri->segment(2) == 'admin') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/admin/list">
                        <i class="fa fa-fw fa-user"></i> <span>Manage Admin</span> 
                    </a>
                </li>
                <li class="treeview <?php if ($this->uri->segment(2) == 'user') { ?> active<?php } ?>" <?php if ($this->uri->segment(2) == 'user') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/user/list">
                        <i class="fa fa-fw fa-user"></i>
                        <span>Manage Users</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo base_url(); ?>backend/user/list"><i class="fa fa-angle-double-right"></i> View Users</a></li>
                    </ul>
                </li>
                <li  <?php if ($this->uri->segment(2) == 'email-template' || $this->uri->segment(2) == 'edit-email-template') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/email-template/list">
                        <i class="fa fa-fw fa-envelope"></i> <span>Manage Email Templates</span> 
                    </a>
                </li>
                <li <?php if ($this->uri->segment(2) == 'cms') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/cms">
                        <i class="fa fa-fw fa-file-text"></i> <span>Manage CMS</span> 
                    </a>
                </li>
                <li  <?php if ($this->uri->segment(2) == 'contact-us') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/contact-us">
                        <i class="fa fa-fw fa-phone"></i> <span>Manage Contact us</span> 
                    </a>
                </li>
                <li <?php if ($this->uri->segment(2) == 'faqs') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/faqs/list">
                        <i class="fa fa-fw fa-question"></i> <span>Manage FAQ's</span> 
                    </a>
                </li>

                <li <?php if ($this->uri->segment(2) == 'categories') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/categories/list">
                        <i class="fa fa-fw fa-list"></i> <span>Manage Service Categories</span> 
                    </a>
                </li>

                <li  class="treeview <?php if ($this->uri->segment(2) == 'organization' || $this->uri->segment(2) == 'document') { ?>active<?php } ?>" <?php if ($this->uri->segment(2) == 'advertises') { ?> class="active" <?php } ?>>
                    <a href="#">
                        <i class="fa fa-fw fa-rss"></i>
                        <span>Manage Organization</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li  <?php if ($this->uri->segment(2) == 'document') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/document/list"><i class="fa fa-fw fa-list"></i> Document List</a></li>
                        <li  <?php if ($this->uri->segment(2) == 'organization') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/organization/list"><i class="fa fa-fw fa-list"></i> Organization List</a></li>

                    </ul>
                </li>

                <li class="treeview <?php if ($this->uri->segment(2) == 'newsletter' || $this->uri->segment(2) == 'newsletter-subscriber') { ?>active<?php } ?>" <?php if ($this->uri->segment(2) == 'newsletter') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/newsletter/list">
                        <i class="fa fa-fw fa-envelope"></i>
                        <span>Manage Newsletters</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu"> <li <?php if ($this->uri->segment(2) == 'newsletter') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/newsletter/list"><i class="fa fa-fw fa-list"></i> View Newsletters</a></li>

                    </ul>
                </li>
                <li <?php if ($this->uri->segment(2) == 'countries') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/countries">
                        <i class="fa fa-fw fa-home"></i> <span>Manage Countries</span> 
                    </a>
                </li>
                <li <?php if ($this->uri->segment(2) == 'states') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/states">
                        <i class="fa fa-fw fa-home"></i> <span>Manage States</span> 
                    </a>
                </li>
                <li <?php if ($this->uri->segment(2) == 'cities') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/cities">
                        <i class="fa fa-fw fa-home"></i> <span>Manage Cities</span> 
                    </a>
                </li><!--
                <li  <?php if ($this->uri->segment(2) == 'slider-banner') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/slider-banner/list-sliders-banners">
                        <i class="fa fa-fw fa-rotate-right"></i> <span>Manage Slider Banner</span> 
                    </a>
                </li>
                -->
                <li  class="treeview <?php if ($this->uri->segment(2) == 'blog' || $this->uri->segment(2) == 'blog-category') { ?>active<?php } ?>" <?php if ($this->uri->segment(2) == 'advertises') { ?> class="active" <?php } ?>>
                    <a href="#">
                        <i class="fa fa-fw fa-rss"></i>
                        <span>Manage Blogs</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li  <?php if ($this->uri->segment(3) == 'blog-category') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/blog/blog-category"><i class="fa fa-fw fa-list"></i> Blog Categories</a></li>
                        <li  <?php if ($this->uri->segment(2) == 'blog' && $this->uri->segment(3) != 'blog-category') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/blog"><i class="fa fa-fw fa-list"></i> View Blogs </a></li>

                    </ul>
                </li>
                <li <?php if ($this->uri->segment(2) == 'testimonial') { ?> class="active" <?php } ?>>
                    <a href="<?php echo base_url(); ?>backend/testimonial/list">
                        <i class="fa fa-fw fa-retweet"></i> <span>Manage Testimonials</span> 
                    </a>
                </li>
            </ul>
            <?php
        } else {

            $arr_privileges = $this->common_model->getRecords('trans_role_privileges', 'privilege_id', array("role_id" => $user_account['role_id']));
            if (count($arr_privileges) > 0) {
                foreach ($arr_privileges as $privilege) {
                    $user_privileges[] = $privilege['privilege_id'];
                }
            }
            $arr_login_admin_privileges = $user_privileges;

            $user_account['user_privileges'] = ($user_privileges);
            if (count($arr_login_admin_privileges) > 0) {
                ?>
                <ul class="sidebar-menu">
                    <li <?php if ($this->uri->segment(2) == 'dashboard') { ?> class="active" <?php } ?>>
                        <a href="<?php echo base_url(); ?>backend/dashboard">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <?php
                    foreach ($arr_login_admin_privileges as $privilage) {
                        switch ($privilage) {

                            case 1:
                                ?>
                                <li class="treeview <?php if ($this->uri->segment(2) == 'user') { ?> active<?php } ?>" <?php if ($this->uri->segment(2) == 'user') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/user/list">
                                        <i class="fa fa-fw fa-user"></i>
                                        <span>Manage Users</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="<?php echo base_url(); ?>backend/user/list"><i class="fa fa-angle-double-right"></i> View Users</a></li>
                                    </ul>
                                </li>
                                <?php
                                break;
                            case 2:
                                ?>
                                <li  <?php if ($this->uri->segment(2) == 'email-template' || $this->uri->segment(2) == 'edit-email-template') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/email-template/list">
                                        <i class="fa fa-fw fa-envelope"></i> <span>Manage Email Templates</span> 
                                    </a>
                                </li>
                                <?php
                                break;
                            case 3:
                                ?>
                                <li <?php if ($this->uri->segment(2) == 'cms') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/cms">
                                        <i class="fa fa-fw fa-file-text"></i> <span>Manage CMS</span> 
                                    </a>
                                </li>
                                <?php
                                break;
                            case 4:
                                ?>
                                <li  <?php if ($this->uri->segment(2) == 'contact-us') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/contact-us">
                                        <i class="fa fa-fw fa-phone"></i> <span>Manage Contact us</span> 
                                    </a>
                                </li>
                                <?php
                                break;
                            case 5:
                                ?>
                                <li <?php if ($this->uri->segment(2) == 'faqs') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/faqs/list">
                                        <i class="fa fa-fw fa-question"></i> <span>Manage FAQ's</span> 
                                    </a>
                                </li>
                                <?php
                                break;
                            case 6:
                                ?>
                                <li class="treeview <?php if ($this->uri->segment(2) == 'newsletter' || $this->uri->segment(2) == 'newsletter-subscriber') { ?>active<?php } ?>" <?php if ($this->uri->segment(2) == 'newsletter') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/newsletter/list">
                                        <i class="fa fa-fw fa-envelope"></i>
                                        <span>Manage Newsletters</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu"> <li <?php if ($this->uri->segment(2) == 'newsletter') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/newsletter/list"><i class="fa fa-fw fa-list"></i> View Newsletters</a></li>

                                                                                                                                                                        <!--<li <?php if ($this->uri->segment(2) == 'newsletter-subscriber') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/newsletter-subscriber/list"><i class="fa fa-fw fa-user"></i> Manage Newsletters Subscribers</a></li>-->

                                    </ul>
                                </li>
                                <?php
                                break;
                            case 7:
                                ?>
                                <li <?php if ($this->uri->segment(2) == 'countries') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/countries">
                                        <i class="fa fa-fw fa-home"></i> <span>Manage Countries</span> 
                                    </a>
                                </li>
                                <?php
                                break;
                            case 8:
                                ?>
                                <li <?php if ($this->uri->segment(2) == 'states') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/states">
                                        <i class="fa fa-fw fa-home"></i> <span>Manage States</span> 
                                    </a>
                                </li>
                                <?php
                                break;
                            case 9:
                                ?>
                                <li <?php if ($this->uri->segment(2) == 'cities') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/cities">
                                        <i class="fa fa-fw fa-home"></i> <span>Manage Cities</span> 
                                    </a>
                                </li>
                                <?php
                                break;
                            case 10:
                                ?>
                                <li  class="treeview <?php if ($this->uri->segment(2) == 'blog' || $this->uri->segment(2) == 'blog-category') { ?>active<?php } ?>" <?php if ($this->uri->segment(2) == 'advertises') { ?> class="active" <?php } ?>>
                                    <a href="#">
                                        <i class="fa fa-fw fa-rss"></i>
                                        <span>Manage Blogs</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li  <?php if ($this->uri->segment(3) == 'blog-category') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/blog/blog-category"><i class="fa fa-fw fa-list"></i> Blog Categories</a></li>
                                        <li  <?php if ($this->uri->segment(2) == 'blog' && $this->uri->segment(3) != 'blog-category') { ?> class="active" <?php } ?>><a href="<?php echo base_url(); ?>backend/blog"><i class="fa fa-fw fa-list"></i> View Blogs </a></li>

                                    </ul>
                                </li>
                                <?php
                                break;
                            case 11:
                                ?>
                                <li <?php if ($this->uri->segment(2) == 'testimonial') { ?> class="active" <?php } ?>>
                                    <a href="<?php echo base_url(); ?>backend/testimonial/list">
                                        <i class="fa fa-fw fa-retweet"></i> <span>Manage Testimonials</span> 
                                    </a>
                                </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <?php
            }
        }
        ?>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Right side column. Contains the navbar and content of the page -->
