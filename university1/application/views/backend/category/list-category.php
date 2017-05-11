<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            Manage Service Categories
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Manage Service Categories</li>

        </ol>
    </section>
    <section class="content">
        <?php
        $msg = $this->session->userdata('msg');
        ?>
        <?php if ($msg != '') { ?>
            <div class="msg_box alert alert-success">
                <button type="button" class="close" data-dismiss="alert" id="msg_close" name="msg_close">X</button>
                <?php
                echo $msg;
                $this->session->unset_userdata('msg');
                ?>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">
                            <form name="frm_category" id="frm_category" action="<?php echo base_url(); ?>backend/categories/list" method="post">
                                <table class="table table-bordered table-striped dataTable" id="example1" aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th width="7%" >
                                                <?php if (count($arr_categary_list) > 0) { ?>
                                        <center>Select <br><input type="checkbox" name="check_all" id="check_all"  class="select_all_button_class" value="select all" /></center>
                                        <?php
                                    }
                                    ?> 
                                    <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Category Name">Category Name</th>
                                    <!--<th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Parameter Value">Category Description</th>-->
                                    <th  class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Action">Action</th>
                                    <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                        ?>		
                                        <th  class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-sort="ascending" aria-label="Multilanguage">MultiLanguage</th>
                                    <?php } ?>		
                                    </tr>
                                    </thead>


                                    <tbody>
                                        <?php
                                        foreach ($arr_categary_list as $row) {
                                            ?>
                                            <tr>
                                                <td align="left">
                                        <center>
                                            <?php if ($row['parent_id'] != '0') { ?> 
                                                <input name="checkbox[]" class="case" type="checkbox" id="checkbox" value="<?php echo $row['category_detail_id']; ?>" />
                                            <?php } ?>
                                        </center>

                                        </td>
                                        <td  align="left"><?php echo stripslashes($row['category_name']); ?></td>
                                        <td  align="left">
                                            <?php if ($row['parent_id'] != '0') { ?> 
                                                <a class="btn btn-info" title="Edit Category" href="<?php echo base_url(); ?>backend/category/edit-category/<?php echo base64_encode($row['category_detail_id']); ?>"><i class="icon-edit icon-white"></i>Edit</a>
                                            <?php } ?> 
                                        </td>
                                        <?php if ($this->config->item('is_multi_language') == 'Yes') {
                                            ?>		
                                            <td>
                                                <a class="btn btn-primary" title="Multi Languge" href="<?php echo base_url(); ?>backend/change-category-language/<?php echo base64_encode($row['category_detail_id']); ?>"><i class="icon-edit icon-white"></i>Multi Language</a>
                                            </td>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                    <?php if (count($arr_categary_list) > 0) { ?>
                                        <tfoot>
                                        <th colspan="7">
                                            <input type="submit" id="btn_delete_all" name="btn_delete_all" class="btn btn-danger" onClick="return deleteConfirm();"  value="Delete Selected">
                                            <a id="add_new_category" name="add_new_category" href="<?php echo base_url(); ?>backend/category/add-category" class="btn btn-primary pull-right" >Add New Category </a>
                                        </th>
                                        </tfoot>
                                    <?php } else { ?> 
                                        <tfoot>
                                        <th colspan="7">
                                            <a id="add_new_category" name="add_new_category" href="<?php echo base_url(); ?>backend/category/add-category" class="btn btn-primary pull-right" >Add New Category </a>
                                        </th>
                                        </tfoot>
                                    <?php } ?>
                                </table>
                            </form>
                        </div>
                    </div>
                    <!--[sortable body]--> 

                </div>
            </div>

            <!--[sortable table end]--> 

            <!--[include footer]-->
        </div><!--/content.span10-->

        </div><!--/fluid-row-->
        <?php $this->load->view('backend/sections/footer.php'); ?>
