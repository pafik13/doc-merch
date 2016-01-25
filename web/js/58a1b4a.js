$(document).ready(function(){
    $('#address-button').click(function(){
        $('.js-form-address').find('input').each(function(i, elem) {
            var input = $(elem);
            input.data('initialState', input.val());
        });
        $('.full-address').data('initialState',$('.full-address').val());
        $('.latitude').data('initialState',$('.latitude').val());
        $('.longitude').data('initialState',$('.longitude').val());
        $('.short-name').data('initialState',$('.short-name').val());
    });

    $('.full-address').change(function(){
        if (!$(".full-address").val()){
            $("#address-button").html('Добавить адрес');
        } else {
            $("#address-button").html('Изменить адрес');
        }
    });

    $('.close-address-button').click(function(){
        $('.js-form-address').find('input').each(function(i, elem) {
            var input = $(elem);
            input.val(input.data('initialState'));
        });
        $('.full-address').val($('.full-address').data('initialState'));
        $('.latitude').val($('.latitude').data('initialState'));
        $('.longitude').val($('.longitude').data('initialState'));
        $('.short-name').val($('.short-name').data('initialState'));
        $('.full-address').trigger("change");
    });

    $('.save-address-button').click(function(){
        $('.short-name').val($('[name="short-name"]').val());
        $('.ka-region').val($('[name="region"]').val());
        $('.ka-district').val($('[name="district"]').val());
        $('.ka-city').val($('[name="city"]').val());
        $('.ka-street').val($('[name="street"]').val());
        $('.ka-building').val($('[name="building"]').val());
    });

    $('#address-button').click(function(){
        $('[name="short-name"]').val($('.short-name').val())
        $.kladr.setValues({
            region: $('.ka-region').val(),
            district: $('.ka-district').val(),
            city: $('.ka-city').val(),
            street: $('.ka-street').val(),
            building: $('.ka-building').val()
        }, 'form.js-form-address');
    });
});
