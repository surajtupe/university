<section class="middle-section">
    <div class="container">
        <div class="mid-section">
            <div class="head">
                <h2>FAQ'S</h2>
            </div>
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-xs-12">Search</label>
                    <div class="col-xs-12 search-icon">
                        <input class="form-control" placeholder="Search" id="faq_search" name="faq_search">
                    </div>
                </div>
            </form>
            <div id="accordion" class="panel-group accordion">
                <?php
                if (!empty($faq_question_details)) {
                    $count = 1;
                    foreach ($faq_question_details as $faq) {
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title" id="question">
                                    <a href="#collapse<?php echo $count ?>" data-parent="#accordion" data-toggle="collapse">
                                        <i class="switch fa fa-plus"></i>
                                        <?php echo ucfirst($faq['question']); ?>
                                    </a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse" id="collapse<?php echo $count ?>">
                                <div class="panel-body">
                                    <?php echo ucfirst($faq['answer']); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        $count++;
                    }
                } else {
                    echo "No FAQ's Found";
                }
                ?>
                 <?php if ($create_links != '') { ?>
                <div class="text-center pagination-box">
                    <?php echo $create_links; ?>
                </div>
            <?php }
            ?>
            </div>
           

        </div>


    </div>
</section>
<style>
    .pagination-section{
        padding:10px 30px;
    }
    .paginationPara a{
        color: #fff;
        display:inline-block;
        height:30px;
        min-width:30px;
        font-size:14px;
        font-weight:bold;
        padding:0 12px;
        vertical-align: middle;
        border-radius:5px;
        -moz-border-radius:5px;
        -webkit-border-radius:5px;
        box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -moz-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -webkit-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        background:#414141;
        margin:2px;
        line-height:30px;

    }
    .paginationPara strong{
        color: #fff;
        display:inline-block;
        height:30px;
        width:30px;
        font-size:14px;
        font-weight:bold;
        padding:0 12px;
        border-radius:5px;
        -moz-border-radius:5px;
        -webkit-border-radius:5px;
        box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -moz-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        -webkit-box-shadow:0px 0px 3px rgba(0,0,0,0.3);
        background:#33CAFF;
        margin:2px;
        line-height:30px;
    }
</style>
<script type="text/javascript">
    $("#faq_search").keyup(function() {
        var search_keyword = $("#faq_search").val();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>search-faq',
            data: {
                'search_keyword': search_keyword
            },
            dataType: 'json',
            success: function(msg) {
                var str = '';
                if (msg != '' && msg != 'undefined') {
                    var counter = 1;
                    $.each(msg, function(key, value) {
                        str += '<div class="panel panel-default">';
                        str += '<div class="panel-heading">';
                        str += '<h4 class="panel-title" id="question">';
                        str += '<a href="#collapse' + counter + '" data-parent="#accordion" data-toggle="collapse">';
                        str += '<i class="switch fa fa-plus"></i>' + value.question + '</a>';
                        str += '</h4>';
                        str += '</div>';
                        str += '<div class="panel-collapse collapse" id="collapse' + counter + '">';
                        str += '<div class="panel-body">' + value.answer + '</div>';
                        str += '</div>';
                        str += '</div>';
                        counter++;
                    });
                } else {
                    str += 'No Match Found.<br>';
                    str += '<a href="<?php echo base_url(); ?>contact-us">Add own question.</a>';
                }
                $('#accordion').html(str);
            }
        });
    });
</script>
<style>
    .mid-section a:hover {
        color: #3993B3;
    }
</style>