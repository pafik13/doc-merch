$(document).ready(function(){
    $('#address-button').click(function(){
        $('form').find('.address-field').each(function(i, elem) {
            var input = $(elem);
            input.data('initialState', input.val());
        });
    });

    $('.full-address').change(function(){
        if (!$(".full-address").val()){
            $("#address-button").html('Добавить адрес');
        } else {
            $("#address-button").html('Изменить адрес');
        }
    });

    $('.close-address-button').click(function(){
        $('form').find('.address-field').each(function(i, elem) {
            var input = $(elem);
            input.val(input.data('initialState'));
        });
        $('.full-address').trigger("change");
    });
});
