</div>
<!--/fluid-row-->
<!-- jQuery 2.0.2 -->
<script src="<?php echo base_url(); ?>media/backend/js/jquery-2.0.2.min.js"></script>
<!-- jQuery UI 1.10.3 -->
<script src="<?php echo base_url(); ?>media/backend/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>

<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>media/backend/js/bootstrap.min.js" type="text/javascript"></script>
<!-- Morris.js charts -->
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url(); ?>media/backend/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>media/backend/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>media/backend/js/jquery.validate.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>media/backend/js/AdminLTE/app.js" type="text/javascript"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>media/backend/js/AdminLTE/dashboard.js" type="text/javascript"></script>  
<script src="<?php echo base_url(); ?>media/backend/js/select-all-delete.js"></script>      

<input type="hidden" name="body_class" id="body_class" value="skin-black" />
<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>" />

<!-- The styles -->
<script type="text/javascript">
    $(document).ready(function (e) {
        jQuery("#msg_close").bind("click", function () {
            $(this).parent().remove();
        });
    });
</script>
<script>


    var class_val = sessionStorage.getItem("body_class");
    if (class_val != '' && class_val != null)
    {
        $("body").removeAttr('class');
        $("body").attr('class', class_val);
    }
</script>
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        /* $("#multiple").next().on("click",function()
         {
         $.ajax(
         {
         
         type:"Post",
         url:"<?php echo base_url(); ?>backend/change-languageversion-for-functionality",
         dataType:"json",
         data:{type:'Multiple'},
         success: function(data)
         {
         alert(data);
         window.location.href="<?php echo base_url(); ?>backend/dashboard";
         }
         });
         
         
         });
         $("#single").next().on("click",function()
         {
         $.ajax(
         {
         
         type:"Post",
         url:"<?php echo base_url(); ?>backend/change-languageversion-for-functionality",
         dataType:"json",
         data:{type:'Single'},
         success: function(data)
         {
         window.location.href="<?php echo base_url(); ?>backend/dashboard";
         }
         });
         
         });*/

    });
</script>
<script>

</script>
</body>
</html>