<?php $this->load->view('backend/sections/header'); ?>
<?php $this->load->view('backend/sections/top-nav.php'); ?>
<?php $this->load->view('backend/sections/leftmenu.php'); ?>
<aside class="right-side">
    <section class="content-header">

        <h1>
            Edit City in Multi-Languge</li>     
        </h1>            
        <ol class="breadcrumb">

            <li> <a href="<?php echo base_url(); ?>backend/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>  </li>
            <li> <a href="<?php echo base_url(); ?>backend/cities"><i class="fa fa-fw fa-home"></i> Manage Cities</a></li>
            <li>Edit City in Multi-Languge</li>

        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">   

                    <form name="frm_change_lang"  id="frm_change_lang" action="<?php echo base_url(); ?>backend/city-change-language/<?php echo $arr_city_list[0]['city_id_fk']; ?>" method="POST" >

                        <input type="hidden" value="<?php echo base_url(); ?>" id="base_url" name="base_url">
                        <input type="hidden"  id="old_city_name" name="old_city_name">
                        <input type="hidden" value="<?php echo $arr_city_list[0]['city_id_fk'] ?>" id="city_id_fk" name="city_id_fk">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parametername">Select Language<sup class="mandatory">*</sup></label>
                                <select  name="lang_id" class="form-control" id="lang_id" onChange="getCityName(this.value, '<?php echo $arr_city_list['0']['city_id_fk']; ?>');">
                                    <option value="">Select Language</option>
                                    <?php foreach ($arr_get_language as $languages) { ?>
                                        <option value="<?php echo $languages['lang_id'] ?>" ><?php echo $languages['lang_name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                            <div class="control-group" style="display:none" id="city_div">

                            </div>

                            <div class="control-group" style="display:none" id="status_div">

                            </div>

                            <div class="box-footer">
                                <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary" value="Save Changes">Save Changes</button>

                                <button type="reset" name="cancel" class="btn" onClick="window.top.location = '<?php echo base_url(); ?>backend/cities';">Cancel</button>
                            </div>
                   
                </div> </form>   
            </div>
        </div>
        </div>
        </div>
        <?php $this->load->view('backend/sections/footer.php'); ?>
        <script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/city-manage/change-language.js"></script>

        <script type="text/javascript">
                           
                                    function getCityName(value, city_id_fk) 
                                         {
                                              var lang_id = value;
                                              var city_id_fk = city_id_fk;
                                              if(value!='')
                                              {
                                                    //get Language name of 
                                                       $.ajax({
                                                             type: "POST",
                                                             url: '<?php echo base_url(); ?>backend/city/get-city-name',
                                                             data: {
                                                                 'lang_id': lang_id,
                                                                 'city_id_fk': city_id_fk
                                                             }, 
                                                             success: function (msg)   {
                                                               $("#old_city_name").val(msg);
                                                            }
                                                         });
                                             }
                                              
                                                $.ajax({
                                                    type: "POST",
                                                    url: '<?php echo base_url(); ?>backend/city/get-all-city-names',
                                                    data: {
                                                        'lang_id': lang_id,
                                                        'city_id_fk': city_id_fk
                                                    },
                                                    success: function (msg)   {

                                                        if (msg != 'false') {
                                                            $("#city_div").css("display", "block");
                                                            $("#city_div").html(msg);
                                                        }
                                                        else {
                                                            $("#city_div").css("display", "block");
                                                        }
                                                    }
                                                });
                                       
                                     }


        </script>