$(document).ready(function(){

    var presentation = $("#presentation").data("json"),
        mode = $("#presentation").data("page"),
        tempPresentation =  jQuery.extend(true, {}, presentation),
        current_category,
        current_subcategory,
        current_slide;

    var slide = $('#slide');
    var slideModal = $("#slide-modal");
    var editCategoryModal = $("#edit-category-modal");
    var topPanel = $('#top-panel-btns');
    var botPanel = $('#bot-panel-btns');

    var files;
    var filenames;

    function randString () {
        return Math.random().toString(36).substr(2); // remove `0.`
    }

    function sortSlides(){
        current_subcategory.slides.sort(function (a, b) {
            a= a.number;
            b= b.number;

            if(a > b) {
                return 1;
            } else if(a < b) {
                return -1;
            } else {
                return 0;
            }
        });
    }

    function preload(){
        tempPresentation.categories.forEach(function(category){
            category.subcategories.forEach(function(subcategory){
                subcategory.slides.forEach(function(slide){
                    var image = new Image();
                    image.src = slide.web_path;
                });
            });
        });
    }

    function nextItem(num, array){
        return array[($.inArray(num, array) + 1) % array.length];
    }

    function prevItem(num, array){
        return array[($.inArray(num, array) - 1 + array.length) % array.length];
    }

    function loadCategories(){
        topPanel.empty();
        tempPresentation.categories.forEach(function(item){
            if(current_category == undefined || item.name === current_category.name){
                topPanel.append("<a class = 'btn btn-primary active' href='javascript:void(0);'>"+item.name+"</a>"
                );
            } else {
                topPanel.append("<a class = 'btn btn-primary' href='javascript:void(0);'>"+item.name+"</a>"
                );
            }
        });
        topPanel.find('a').click(function(){changeCategory($(this))});
    }

    function loadSubcategories(){
        botPanel.children().remove();
        current_category.subcategories.forEach(function(item){
            if(item.name === current_subcategory.name){
                botPanel.append("<a class = 'btn btn-primary active' href='javascript:void(0);'>"+item.name+"</a>"
                );
            } else {
                botPanel.append("<a class = 'btn btn-primary' href='javascript:void(0);'>"+item.name+"</a>"
                );
            }
        });
        botPanel.find('a').click(function(){changeSubcategory($(this))});
        current_slide = current_subcategory.slides[0];
    }

    function changeCategory(item){
        var name  = item.html();
        current_category = $.grep(tempPresentation.categories, function(e){
            return e.name === name;
        })[0];
        current_subcategory = current_category.subcategories[0];
        loadSubcategories();
        loadSlide();
        topPanel.find('a').removeClass("active");
        item.addClass("active");
    }

    function changeSubcategory(item){
        var name  = item.html();
        current_subcategory = $.grep(current_category.subcategories, function(e){
            return e.name === name;
        })[0];
        current_slide = current_subcategory.slides[0];
        loadSlide();
        botPanel.find('a').removeClass("active");
        item.addClass("active");
    }

    function loadSlide(){
        if(current_category == undefined || current_subcategory.slides.length == 0 || current_slide === null ){
            $('.nav-add').removeClass('hidden');
            $('.nav-delete').addClass('hidden');
            current_slide = null;
            $("#slide").attr({src: '/bundles/app/img/slides/placeholder.png'});
        }else {
            $('.nav-add').addClass('hidden');
            $('.nav-delete').removeClass('hidden');
            $("#slide").attr({src: current_slide.web_path});
        }
    }

    $(".nav-arrow-right").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);

        if (current_slide === null || (id>=current_subcategory.slides.length-1 && mode == 'presentation_show')){
            current_subcategory = nextItem(current_subcategory,current_category.subcategories);
            botPanel.find('a').removeClass("active");
            botPanel.find('a').filter(function() {
                return $(this).text() === current_subcategory.name;
            }).addClass("active");
            var next = 0;
            current_slide = current_subcategory.slides[next];
        } else if(id>=current_subcategory.slides.length-1) {
            current_slide = null;
        }  else {
            var next = id+1;
            current_slide = current_subcategory.slides[next];
        }
        loadSlide();

    });

    $(".nav-arrow-left").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);

        if(id === 0 || current_subcategory.slides.length == 0) {
            current_subcategory = prevItem(current_subcategory,current_category.subcategories);
            botPanel.find('a').removeClass("active");
            botPanel.find('a').filter(function() {
                return $(this).text() === current_subcategory.name;
            }).addClass("active");
            //botPanel.find('a:contains("'+current_subcategory.name+'")').addClass("active");
            current_slide = null;
        } else if (current_slide === null){
            var prev = current_subcategory.slides.length-1;
            current_slide = current_subcategory.slides[prev];
        } else {
            var prev = id-1;
            current_slide = current_subcategory.slides[prev];
        }

        loadSlide();
    });

    $('.nav-delete').click(function(){
        current_subcategory.slides.splice(current_subcategory.slides.indexOf(current_slide),1);
        current_slide = prevItem(current_slide, current_subcategory.slides);
        loadSlide();
    });

    $('#tsb-add-btn').click(function(){
        editCategoryModal.modal('show');
        editCategoryModal.find('input').attr({'data-type':'category','data-action':'add'});
        editCategoryModal.find('input').val('');
    });

    $('#tsb-del-btn').click(function(){
        tempPresentation.categories.splice(tempPresentation.categories.indexOf(current_category),1);
        current_category = prevItem(current_category, tempPresentation.categories);
        current_subcategory = current_category.subcategories[0];

        loadCategories();
        loadSubcategories();
        loadSlide();
    });

    $('#tsb-edit-btn').click(function(){
        editCategoryModal.modal('show');
        editCategoryModal.find('input').attr({'data-type':'category','data-action':'edit'});
        editCategoryModal.find('input').val(current_category.name);
    });

    $('#bsb-del-btn').click(function(){
        current_category.subcategories.splice(current_category.subcategories.indexOf(current_subcategory),1);
        current_subcategory = prevItem(current_subcategory,current_category.subcategories);
        loadSubcategories();
        loadSlide();
    });

    $('#bsb-add-btn').click(function(){
        editCategoryModal.modal('show');
        editCategoryModal.find('input').attr({'data-type':'subcategory','data-action':'add'});
        editCategoryModal.find('input').val('');
    });

    $('#bsb-edit-btn').click(function(){
        editCategoryModal.modal('show');
        editCategoryModal.find('input').attr({'data-type':'subcategory','data-action':'edit'});
        editCategoryModal.find('input').val(current_subcategory.name);
    });

    $('#save-category-btn').click(function(){
        var input = $('#category-input');

        if(input.attr('data-action') == 'edit'){
            if(input.attr('data-type') == 'category'){
                current_category.name = input.val();
            } else {
                current_subcategory.name = input.val();
            }
        } else {
            if(input.attr('data-type')=='category'){
                tempPresentation.categories.push({'name':input.val(), 'subcategories':[{'name':'Подгруппа', 'slides':[]}]});
                current_category = $.grep(tempPresentation.categories, function(e){ return e.name == input.val(); })[0];
                current_subcategory = current_category.subcategories[0];
            } else {
                current_category.subcategories.push({'name':input.val(), 'slides':[]});
            }
        }
        editCategoryModal.modal("hide");
        loadCategories();
        loadSubcategories();
    });

    $('#save-pres-btn').click(function(event){
        slideModal.modal("hide");
        presentation = jQuery.extend(true, {}, tempPresentation);
    });

    editCategoryModal.on('show.bs.modal',function(){
       $('#new-category-input').val('');
    });

    slideModal.on('show.bs.modal', function(){
        tempPresentation =  jQuery.extend(true, {}, presentation);

        preload();

        current_category = tempPresentation.categories[0];
        if (current_category != undefined) {
            current_subcategory = current_category.subcategories[0];
            current_slide = current_subcategory.slides[0];
            clearFiles();
            sortSlides();
            loadCategories();
            loadSubcategories();
        }

        loadSlide();
    });

    $('#file-upload').on('change', function(e) {

        var tempLength;
        if(files === undefined || files.length == 0){
            tempLength = 0;
            files = jQuery.extend(true, {}, e.target.files);
            filenames = [];
        } else{
            tempLength = files.length;
            files = $.merge( $.merge( [], files ), e.target.files );
        }

        if (!files || files.length == 0) return;
        //file = files[0];
        $.each(e.target.files, function(key, value) {
            var fileReader = new FileReader();
            fileReader.readAsDataURL(this);
            var file = this;

            fileReader.onload = function (e) {
                var number = current_subcategory.slides.length > 0 ? current_subcategory.slides[current_subcategory.slides.length-1].number+1 : 0;
                var name = randString() +randString();
                filenames.push(name);
                current_subcategory.slides.push({'name':name, 'web_path': e.target.result, 'number':number});
                current_slide = current_subcategory.slides[current_subcategory.slides.length-1];
                loadSlide();
            };
        });
        $('#file-upload').val('');
    });

    $("#tsb-eye-btn").click(function(){
        if ($("#tsb-eye-btn").hasClass("active")){
            $("#top-panel").collapse("hide");
            $("#tsb-add-btn").attr('disabled', true);
            $("#tsb-del-btn").attr('disabled', true);
            $("#tsb-edit-btn").attr('disabled', true);
            $("#bottom-panel").collapse("hide");
            $("#bsb-add-btn").attr('disabled', true);
            $("#bsb-del-btn").attr('disabled', true);
            $("#bsb-edit-btn").attr('disabled', true);
            $("#tsb-eye-btn").removeClass("active");
        } else {
            $("#top-panel").collapse("show");
            $("#top-hotspot").collapse("show");
            $("#tsb-add-btn").removeAttr('disabled');
            $("#tsb-del-btn").removeAttr('disabled');
            $("#tsb-edit-btn").removeAttr('disabled');
            $("#bottom-panel").collapse("show");
            $("#bsb-add-btn").removeAttr('disabled');
            $("#bsb-del-btn").removeAttr('disabled');
            $("#bsb-edit-btn").removeAttr('disabled');
            $("#tsb-eye-btn").addClass("active");
        }
    });

    function clearFiles(){
        var $el = $('#file-upload');
        $el.wrap('<form>').closest('form').get(0).reset();
        $el.unwrap();
    }

    //function uploadPresentation(){
    //    tempPresentation.name = $('#appbundle_presentation_name').val();
    //    tempPresentation.template = $('#appbundle_presentation_template').val();
    //    var json = JSON.stringify(tempPresentation);
    //
    //    jQuery.ajax({
    //        type:"POST",
    //        url: "/ajax",
    //        dataType: "json",
    //        data: {'data': json, 'id': tempPresentation.id},
    //        success: function(data){
    //            presentation = data;
    //        },
    //        error: function(){
    //            //alert('error');
    //            console.log('error');
    //        }
    //    });
    //}

    function uploadFiles(event) {
        event.stopPropagation();
        event.preventDefault();

        var data = new FormData($('form')[0]);
        if(files != undefined || files > 0){
            $.each(files, function(key, value)
            {
                data.append(key, value);
            });
        }

        tempPresentation.name = $('#appbundle_presentation_name').val();
        tempPresentation.template = $('#appbundle_presentation_template').val();
        var json = JSON.stringify(tempPresentation);
        data.append('json',json);
        data.append('filenames',filenames.join());

        $.ajax({
            url: '/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data, text, jqXHR)
            {
                if(typeof data.error === 'undefined')
                {
                    window.location.pathname = data.redirect_url;
                    //uploadPresentation();
                }
                else
                {
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                console.log('ERRORS: ' + textStatus);
            }
        });
    }

    $('#submit-form-btn').click(function(){
        tempPresentation.categories.forEach(function(category){
           category.subcategories.forEach(function(subcategory){
               subcategory.slides.forEach(function(slide){
                   slide.path='';
                   slide.web_path='';
                   //slide.name = randString() + randString();
               });
           });
        });

        uploadFiles(event);
    });
});




