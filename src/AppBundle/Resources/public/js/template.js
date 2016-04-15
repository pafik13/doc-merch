$(document).ready(function(){

    var presentation,
        mode = $("#presentation").data("page"),
        tempPresentation,
        current_category,
        current_subcategory,
        current_slide;

    if ((presentation = $("#presentation").data("json")) === undefined ){
        presentation = {'id':'','name':'','template':'','author':$("#presentation").data("user"), 'categories':[]};
    }

    tempPresentation = $.extend(true, {}, presentation);

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

    function loadTopPanel(){
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

    function loadBottomPanel(){
        botPanel.empty();
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
        if(current_subcategory != undefined){
            current_slide = current_subcategory.slides[0];
        } else {
            current_slide = undefined;
        }
    }

    function loadSlide(){
        if(current_subcategory != undefined && current_slide == null && mode != 'presentation_show') {
            $('.nav-add').removeClass('hidden');
        } else {
            $('.nav-add').addClass('hidden');
        }
        if(current_subcategory == undefined || current_subcategory.slides.length == 0 || current_slide == null ){
            current_slide = null;
            $("#slide").attr({src: '/bundles/app/img/slides/placeholder.png'});
        }else {
            $("#slide").attr({src: current_slide.web_path});
        }
    }

    function loadSidePanels(){
        if(current_category == undefined) {
            $("#tsb-del-btn, #tsb-edit-btn").attr('disabled', true);
            $("#bsb-add-btn, #bsb-del-btn, #bsb-edit-btn").attr('disabled', true);
        } else if (current_subcategory == undefined){
            $("#tsb-del-btn, #tsb-edit-btn").removeAttr('disabled');
            $("#bsb-del-btn, #bsb-edit-btn").attr('disabled', true);
        } else {
            $("#tsb-add-btn, #tsb-del-btn, #tsb-edit-btn").removeAttr('disabled');
            $("#bsb-add-btn, #bsb-del-btn, #bsb-edit-btn").removeAttr('disabled');
        }
    }

    function changeCategory(item){
        var name  = item.html();
        current_category = $.grep(tempPresentation.categories, function(e){
            return e.name === name;
        })[0];
        current_subcategory = current_category.subcategories[0];
        loadBottomPanel();
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
        //current_subcategory = current_category.subcategories[0];

        loadTopPanel();
        loadBottomPanel();
        loadSlide();
        loadSidePanels();
    });

    $('#tsb-edit-btn').click(function(){
        editCategoryModal.modal('show');
        editCategoryModal.find('input').attr({'data-type':'category','data-action':'edit'});
        editCategoryModal.find('input').val(current_category.name);
    });

    $('#bsb-del-btn').click(function(){
        current_category.subcategories.splice(current_category.subcategories.indexOf(current_subcategory),1);
        current_subcategory = prevItem(current_subcategory,current_category.subcategories);
        loadBottomPanel();
        loadSlide();
        loadSidePanels();
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
                current_subcategory = $.grep(current_category.subcategories, function(e){ return e.name == input.val(); })[0];
            }
        }
        editCategoryModal.modal("hide");
        loadTopPanel();
        loadBottomPanel();
        loadSlide();
        loadSidePanels();
    });

    $('#save-pres-btn').click(function(event){
        slideModal.modal("hide");
        presentation = $.extend(true, {}, tempPresentation);
    });

    editCategoryModal.on('show.bs.modal',function(){
       $('#new-category-input').val('');
    });

    slideModal.on('show.bs.modal', function(){
        tempPresentation =  $.extend(true, {}, presentation);
        preload();

        current_category = tempPresentation.categories[0];
        if (current_category != undefined) {
            current_subcategory = current_category.subcategories[0];
            current_slide = current_subcategory.slides[0];
            clearFiles();
            sortSlides();
            loadTopPanel();
            loadBottomPanel();
        }

        loadSlide();
        loadSidePanels();
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
            $("#top-panel, #bottom-panel").collapse("hide");
            $("#tsb-add-btn, #tsb-del-btn, #tsb-edit-btn").attr('disabled', true);
            $("#bsb-add-btn, #bsb-del-btn, #bsb-edit-btn").attr('disabled', true);
            $("#tsb-eye-btn").removeClass("active");
        } else {
            $("#top-panel, #bottom-panel").collapse("show");
            $("#tsb-add-btn, #tsb-del-btn, #tsb-edit-btn").removeAttr('disabled');
            $("#bsb-add-btn, #bsb-del-btn, #bsb-edit-btn").removeAttr('disabled');
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

        var url = mode == 'presentation_new' ? '/upload_new' : '/upload_edited';
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
        if (filenames != undefined){
            data.append('filenames',filenames.join());
        }

        $.ajax({
            url: url,
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




