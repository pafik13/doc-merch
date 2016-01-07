/**
 * Created by dasha on 07.01.16.
 */
$(document).ready(function(){
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
