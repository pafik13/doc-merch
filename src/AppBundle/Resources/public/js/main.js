$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
});

$(document).ready(function(){

    $('#my, #other').DataTable( {
            "paging":   false,
            "info": false,
            "searching": false,
            "order": [[ 6, "asc" ]]
        }
    );
    $('#territories').DataTable({
        "paging":   false,
        "info": false,
        "searching": false,
        "order": [[ 0, "asc" ]]
    });
    $("table tbody>tr.inactive").addClass('hidden');
    $("input#myCheckbox").on('change', function(){
        if ($(this).is(':checked'))
            $("table#my tbody>tr.inactive").removeClass('hidden');
        else
            $("table#my tbody>tr.inactive").addClass('hidden');
    });
    $("input#otherCheckbox").on('change', function(){
        if ($(this).is(':checked'))
            $("table#other tbody>tr.inactive").removeClass('hidden');
        else
            $("table#other tbody>tr.inactive").addClass('hidden');
    });
});