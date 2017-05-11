<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            <?php if (isset($edit_id) && $edit_id != '') {
                ?>
                Update Role Setting
            <?php } else { ?>
                Add New Role 
            <?php } ?>	 
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>backend/role/list"><i class="fa fa-gear"></i> Manage Roles</a></li>
            <li class="active"><?php if (isset($edit_id) && $edit_id != '') { ?>
                    Update Role Setting
                <?php } else { ?>
                    Add New Role 
                <?php } ?>	
            </li>

        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <form name="frm_role" role="form"  id="frm_role" action="<?php echo base_url(); ?>backend/role/add" method="POST" >
                        <?php
                        if (isset($edit_id) && $edit_id != '') {
                            ?>
                            <input type="hidden" name="frm_type" id="frm_type" value="edit" />
                            <input type="hidden" name="old_role_name" id="old_role_name" value="<?php
                            if (isset($arr_role['role_name'])) {
                                echo str_replace('"', '&quot;', stripslashes($arr_role['role_name']));
                            }
                            ?>" />
                                   <?php
                               } else {
                                   ?>
                            <input type="hidden" name="frm_type" id="frm_type" value="add" />
                            <input type="hidden" name="old_role_name" id="old_role_name" value="" />
                            <?php
                        }
                        ?>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="Role Name">Role Name <sup class="mandatory">*</sup></label>
                                <input class="form-control" type="text" dir="ltr"   name="role_name" id="role_name" value="<?php
                                if (isset($arr_role['role_name'])) {
                                    echo str_replace('"', '&quot;', stripslashes($arr_role['role_name']));
                                }
                                ?>" />

                            </div>
                            <div class="form-group">
                                <label for="parametername">Choose Role Privileges:- <sup class="mandatory">*</sup></label><br />
                                <?php foreach ($arr_privileges as $key => $privilege) {
                                    ?>
                                    <input class="form-control" type="checkbox" dir="ltr" readonly  name="role_privileges[]" id="role_privileges" value="<?php echo $privilege['privileges_id'] ?>"   <?php
                                    if (isset($edit_id) && $edit_id != '') {
                                        if (in_array($privilege['privileges_id'], $arr_role_privileges)) {
                                            ?> checked="checked" <?php
                                               }
                                           }
                                           ?>/> <?php echo ucwords($privilege['privilege_name']); ?><br />
                                       <?php } ?>  
                                <div id="pre_div"> </div>
                            </div>


                            <div class="box-footer">
                                <?php if ($edit_id != '') { ?>
                                    <button type="submit" name="btn_submit" class="btn btn-primary" value="Save changes" id="btnSubmit">Save changes</button>
                                <?php } else { ?>
                                    <button type="submit" name="btn_submit" class="btn btn-primary" value="Save" id="btnSubmit">Save</button>
                                <?php } ?>
                                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                                <img src="<?php echo base_url(); ?>media/front/img/loader.gif" style="display: none;" id="loding_image">
                            </div>

                    </form>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer'); ?>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/role-manage/add-edit-role.js"></script>
