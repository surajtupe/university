<section class="middle-section">
    <div class="container">
        <div class="mid-section">
            <div class="head">
                <h2>Profile</h2>
            </div>
            <form id="frm_complete_profile" name="frm_complete_profile" method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="col-xs-5 col-sm-3 code padding-right-0">
                        <label>Title </label>
                        <div class="select-box">
                            <select class="form-control" name="title" id="title">
                                <option value="mr">Mr.</option>
                                <option value="ms">Ms.</option>
                                <option value="mrs">Mrs.</option>
                            </select>      
                        </div>
                    </div>
                    <div class="col-xs-7 col-sm-9">
                        <label>FIRST NAME</label>
                        <input class="form-control" placeholder="FIRST NAME" id="first_name" name="first_name" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">MIDDLE NAME</label>
                    <div class="col-xs-12">
                        <input placeholder="MIDDLE NAME" class="form-control" name="middle_name" id="middle_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">LAST NAME</label>
                    <div class="col-xs-12">
                        <input placeholder="LAST NAME" class="form-control" name="last_name" id="last_name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">E-MAIL</label>
                    <div class="col-xs-12">
                        <input placeholder="E-MAIL" class="form-control" id="user_email" name="user_email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12">DATE OF BIRTH</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="select-box">
                            <select class="form-control" name="day" id="day">
                                <option value="">DD</option>
                                <?php
                                $maxDays = date('t');
                                for ($day = 1; $day <= $maxDays; ++$day) {
                                    ?>
                                    <option value="<?php echo $day ?>"><?php echo $day ?></option>
                                <?php } ?>
                            </select></div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="select-box">
                            <select class="form-control"  name="month" id="month">
                                <option value="">MM</option>
                                <?php
                                for ($month = 1; $month <= 12; ++$month) {
                                    ?> 
                                    <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="select-box">
                            <select class="form-control" name="year" id="year">
                                <option value="">YYYY</option>
                                <?php
                                $year_conter = date('Y') - 50;
                                for ($year = 1; $year <= 50; ++$year) {
                                    ?>  
                                    <option value="<?php echo $year_conter ?>"><?php echo $year_conter ?></option>
                                    <?php
                                    $year_conter++;
                                }
                                ?>
                            </select></div>
                    </div>
                </div>
                <div class="text-center offset-top-25 offset-bot-15">
                    <button type="submit" class="btn blue-btn" name="btn_submit" id="btn_submit">Submit</button>
                    <div id="loader" style="display: none" class="three-quarters-loader">Loading…</div>
                </div>
            </form>

        </div>


    </div>
</section>

