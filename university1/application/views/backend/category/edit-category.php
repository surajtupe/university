<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Update Category						 
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/categories/list"><i class="fa fa-fw fa-user"></i> Manage Service Categories</a></li>
            <li class="active">	Update Service Category </li>

        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="frm_edit_category"  id="frm_edit_category" action="<?php echo base_url(); ?>backend/category/edit-category/<?php echo base64_encode($arr_categary[0]['category_detail_id']); ?>" method="POST">
                        <input type="hidden" value="<?php echo $arr_categary[0]['category_detail_id']; ?>" name="category_detail_id" id="category_detail_id">
                        <input type="hidden" value="<?php echo base_url(); ?>" id="base_url" name="base_url">
                        <input type="hidden" value="<?php echo $arr_categary[0]['service_category_id']; ?>" name="category_id" id="category_id">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="country">Parent Category :</label>
                                <select class="form-control" name="parent_category" id="parent_category">
                                    <option value="0">No Parent</option>
                                    <?php
                                    foreach ($arr_categary_list as $category) {
                                        if ($category['parent_id'] == 0) {
                                            ?>
                                            <option <?php if ($arr_categary[0]['parent_id'] == $category['service_category_id']) { ?> selected="selected" <?php } ?> value="<?php echo $category['service_category_id']; ?>"><?php echo $category['category_name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Category Name<sup class="mandatory">*</sup></label>
                                <input type="text" id="category_name" name="category_name" class="form-control" value="<?php echo $arr_categary[0]['category_name']; ?>">
                                <input type="hidden" value="<?php echo $arr_categary[0]['category_name']; ?>" id="old_category_name" name="old_category_name">   			 
                            </div>
                            <div class="form-group">
                                <label for="User Name">Document Required For Address Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="document_required_address_change" name="document_required_address_change" class="form-control" value="0" <?php echo ($arr_categary[0]['document_required_address_change'] == '0') ? 'checked' : '' ?>> No
                                    <input type="radio" id="document_required_address_change" name="document_required_address_change" class="form-control" value="1" <?php echo ($arr_categary[0]['document_required_address_change'] == '1') ? 'checked' : '' ?>> Yes
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Payment Required For Address Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="payment_required_address_change" name="payment_required_address_change" class="form-control" value="0" <?php echo ($arr_categary[0]['payment_required_address_change'] == '0') ? 'checked' : '' ?>> No
                                    <input type="radio" id="payment_required_address_change" name="payment_required_address_change" class="form-control" value="1" <?php echo ($arr_categary[0]['payment_required_address_change'] == '1') ? 'checked' : '' ?>> Yes
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Document Required Mobile Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="document_required_mobile_change" name="document_required_mobile_change" class="form-control" value="0" <?php echo ($arr_categary[0]['document_required_mobile_change'] == '0') ? 'checked' : '' ?>> No
                                    <input type="radio" id="document_required_mobile_change" name="document_required_mobile_change" class="form-control" value="1" <?php echo ($arr_categary[0]['document_required_mobile_change'] == '1') ? 'checked' : '' ?>> Yes
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="User Name">Payment Required Mobile Change : <sup class="mandatory">*</sup></label>
                                <div class="form-group">
                                    <input type="radio" id="payment_required_mobile_change" name="payment_required_mobile_change" class="form-control" value="0" <?php echo ($arr_categary[0]['payment_required_mobile_change'] == '0') ? 'checked' : '' ?>> No
                                    <input type="radio" id="payment_required_mobile_change" name="payment_required_mobile_change" class="form-control" value="1" <?php echo ($arr_categary[0]['payment_required_mobile_change'] == '1') ? 'checked' : '' ?>> Yes
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" name="btnUpdate" class="btn btn-primary" value="Save changes">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--[sortable body]--> 
            </div>
        </div>
        <!--[sortable table end]--> 
        <!--[include footer]-->
        </div><!--/#content.span10-->
        </div><!--/fluid-row-->
        <?php $this->load->view('backend/sections/footer.php'); ?>  
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/category-manage/edit-category.js"></script>