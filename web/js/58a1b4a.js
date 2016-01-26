$(document).ready(function(){
    $('.full-address').change(function(){
        if (!$(".full-address").val()){
            $("#address-button").html('Добавить адрес');
            $('.latitude').val("");
            $('.longitude').val("");
        } else {
            $("#address-button").html('Изменить адрес');
        }
    });

    $('.save-address-button').click(function(){
        $('.ka-region').val($('[name="region"]').val());
        $('.ka-district').val($('[name="district"]').val());
        $('.ka-city').val($('[name="city"]').val());
        $('.ka-street').val($('[name="street"]').val());
        $('.ka-building').val($('[name="building"]').val());
        $('.latitude').val($('[name="latitude"]').val());
        $('.longitude').val($('[name="longitude"]').val());
        $('.full-address').val($('[name="full-address"]').val());
        $('.full-address').change();
    });

    $('#address-button').click(function(){
        $('form.js-form-address').find('.address-field').each(function(i, elem) {
            var input = $(elem);
            input.kladr('controller').clear();
        });

        $.kladr.setValues({
            region: $('.ka-region').val(),
            district: $('.ka-district').val(),
            city: $('.ka-city').val(),
            street: $('.ka-street').val(),
            building: $('.ka-building').val()
        }, 'form.js-form-address');

        $('#modal-title').append($('.short-name').val());
    });

    var input = $('form.add-hospital-form input.name')
    input.each(function () {
        if (!$(this).val()) {
            $('#address-button').addClass("disabled");
            return false;
        }
    });

    input.keyup(function () {
        var trigger = false;
        input.each(function () {
            if (!$(this).val()) {
                trigger = true;
            }
        });
        trigger ? $('#address-button').addClass("disabled") : $('#address-button').removeClass("disabled");
    });


});
