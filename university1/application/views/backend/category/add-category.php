<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Add Category						 
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/categories/list"><i class="fa fa-fw fa-user"></i> Manage Service Categories</a></li>
            <li class="active">	Add Service Category </li>

        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="frm_add_category"  id="frm_add_category" action="<?php echo base_url(); ?>backend/category/add-category" method="POST" >			
                        <div class="box-body">
                            <input type="hidden" value="<?php echo base_url(); ?>" id="base_url" name="base_url">
                            <div class="form-group">
                                <label for="country">Parent Category :</label>
                                <select class="form-control" name="parent_category" id="parent_category">
                                    <option value="">Select Category</option>
                                    <?php
                                    foreach ($arr_categary_list as $category) {
                                        if ($category['parent_id'] == 0) {
                                            ?>
                                            <option value="<?php echo $category['service_category_id']; ?>"><?php echo $category['category_name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Category Name<sup class="mandatory">*</sup></label>
                                <input type="text" id="category_name" name="category_name" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="User Name">Document Required For Address Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="document_required_address_change" name="document_required_address_change" class="form-control" value="0" checked> No
                                    <input type="radio" id="document_required_address_change" name="document_required_address_change" class="form-control" value="1"> Yes
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Payment Required For Address Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="payment_required_address_change" name="payment_required_address_change" class="form-control" value="0" checked> No
                                    <input type="radio" id="payment_required_address_change" name="payment_required_address_change" class="form-control" value="1"> Yes
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Document Required Mobile Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="document_required_mobile_change" name="document_required_mobile_change" class="form-control" value="0" checked> No
                                    <input type="radio" id="document_required_mobile_change" name="document_required_mobile_change" class="form-control" value="1"> Yes
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Payment Required Mobile Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="payment_required_mobile_change" name="payment_required_mobile_change" class="form-control" value="0" checked> No
                                    <input type="radio" id="payment_required_mobile_change" name="payment_required_mobile_change" class="form-control" value="1"> Yes
                                </div>
                            </div>
                            <div class="box-footer">
                                <button id="btn_submit" type="submit" name="btn_submit" class="btn btn-primary" value="Save changes">Save</button>
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image"> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?> 
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/category-manage/add-category.js"></script>