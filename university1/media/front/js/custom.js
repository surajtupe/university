
$(document).ready(function() {
    jQuery(window).bind("load", function() {
        var footer = jQuery("#Footer_main");
        var pos = footer.position();
        var height = jQuery(window).height();
        height = height - pos.top;
        height = height - footer.height();
        if (height > 0) {
            footer.css({'margin-top': height + 'px'});
        }
    })

    $('.accordion').on('shown.bs.collapse', function(e) {
        $(e.target).parent().addClass('active_acc');
        $(e.target).prev().find('.switch').removeClass('fa-plus');
        $(e.target).prev().find('.switch').addClass('fa-minus');
    });
    $('.accordion').on('hidden.bs.collapse', function(e) {
        $(e.target).parent().removeClass('active_acc');
        $(e.target).prev().find('.switch').addClass('fa-plus');
        $(e.target).prev().find('.switch').removeClass('fa-minus');
    });


    var myVar;
    function myFunction() {
        myVar = setTimeout(function() {
            $('.alert').slideUp(1000);
        }, 5000);
    }
    function myStopFunction() {
        clearTimeout(myVar);
    }

    setTimeout(function() {
        $('.alert').slideDown(1000);
        myFunction();
    }, 500);
    $('.alert').mouseover(function() {
        myStopFunction();
    });
    $('.alert').mouseout(function() {
        myFunction();
    });
    $('.alert .close').click(function() {
        $('.alert').slideUp(1000);
    });
});


//window.onresize = function(event) {
//    applyOrientation();
//}




