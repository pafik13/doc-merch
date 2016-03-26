$(document).ready(function(){

    var presentation = $("#presentation").data("json");
    var tempPresentation =  jQuery.extend(true, {}, presentation);

    var current_category = tempPresentation.categories[0];
    var current_subcategory = current_category.subcategories[0];
    var current_slide = current_subcategory.slides[0];

    var slide = $('#slide');
    var slideModal = $("#slide-modal");
    var editCategoryModal = $("#edit-category-modal");
    var topPanel = $('#top-panel-btns');
    var botPanel = $('#bot-panel-btns');

    var files;

    //function first(array){
    //    for(var i in array){
    //        return array[i];
    //    }
    //}

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

    function nextItem(num, array){
        return array[($.inArray(num, array) + 1) % array.length];
    }

    function prevItem(num, array){
        return array[($.inArray(num, array) - 1 + array.length) % array.length];
    }

    function loadCategories(){
        topPanel.empty();
        tempPresentation.categories.forEach(function(item){
            if(item.name === current_category.name){
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
        if(current_subcategory.slides.length==0){
            current_slide=null;
            $("#slide").attr({src: '/bundles/app/img/slides/placeholder.png'});
        }else {
            $("#slide").attr({src: current_slide.path});
        }
    }

    $(".nav-arrow-right").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);
        if(id>=current_subcategory.slides.length-1){
            current_subcategory = nextItem(current_subcategory,current_category.subcategories);
            botPanel.find('a').removeClass("active");
            botPanel.find('a:contains("'+current_subcategory.name+'")').addClass("active");
            var next = 0;
        } else {
            var next = id+1;
        }
        current_slide = current_subcategory.slides[next];
        loadSlide();

    });

    $(".nav-arrow-left").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);
        if(id <= 0){
            current_subcategory = prevItem(current_subcategory,current_category.subcategories);
            botPanel.find('a').removeClass("active");
            botPanel.find('a:contains("'+current_subcategory.name+'")').addClass("active");
            var prev = current_subcategory.slides.length-1;
        } else {
            var prev = id-1;
        }
        current_slide = current_subcategory.slides[prev];
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
        var index =
        current_category.subcategories.splice(current_category.subcategories.indexOf(current_subcategory),1);
        current_subcategory = prevItem(current_subcategory,current_category.subcategories);
        loadSubcategories();
        loadSlide();
    });

    $('#bsb-add-btn').click(function(){
        editCategoryModal.modal('show');
        editCategoryModal.find('input').attr({'data-type':'subcategory','data-action':'add'});
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

        tempPresentation.categories.forEach(function(category){
           category.subcategories.forEach(function(subcategory){
               subcategory.slides.forEach(function(slide){
                   slide.path='';
               });
           });
        });

        uploadFiles(event);
    });

    editCategoryModal.on('show.bs.modal',function(){
       $('#new-category-input').val('');
    });

    slideModal.on('show.bs.modal', function(){
        tempPresentation =  jQuery.extend(true, {}, presentation);

        current_category = tempPresentation.categories[0];
        current_subcategory = current_category.subcategories[0];
        current_slide = current_subcategory.slides[0];

        clearFiles();
        sortSlides();
        loadCategories();
        loadSubcategories();
        loadSlide();
    });

    $('#file-upload').on('change', function(e) {
        if(files === undefined){
            files = e.target.files;
        } else{
            files = $.merge( $.merge( [], files ), e.target.files );
        }


        if (!files || files.length == 0) return;
        //file = files[0];
        $(e.target.files).each(function(){
            var fileReader = new FileReader();
            fileReader.readAsDataURL(this);
            var file = this;

            fileReader.onload = function (e) {
                var number = current_subcategory.slides.length > 0 ? current_subcategory.slides[current_subcategory.slides.length-1].number+1 : 0;
                current_subcategory.slides.push({'name':file.name, 'path': e.target.result, 'number':number});
                current_slide = current_subcategory.slides[current_subcategory.slides.length-1];
                loadSlide();
            };
        });
    });

    $("#tsb-eye-btn").click(function(){
        if ($("#tsb-eye-btn").hasClass("active")){
            $("#top-panel").collapse("hide");
            $("#tsb-add-btn").attr('disabled', true);
            $("#tsb-del-btn").attr('disabled', true);
            $("#tsb-edit-btn").attr('disabled', true);
            $("#tsb-eye-btn").removeClass("active");
        } else {
            $("#top-panel").collapse("show");
            $("#top-hotspot").collapse("show");
            $("#tsb-add-btn").removeAttr('disabled');
            $("#tsb-del-btn").removeAttr('disabled');
            $("#tsb-edit-btn").removeAttr('disabled');
            $("#tsb-eye-btn").addClass("active");
        }
    });

    $("#bsb-eye-btn").click(function(){
        if ($("#bsb-eye-btn").hasClass("active")){
            $("#bottom-panel").collapse("hide");
            $("#bsb-add-btn").attr('disabled', true);
            $("#bsb-del-btn").attr('disabled', true);
            $("#bsb-edit-btn").attr('disabled', true);
            $("#bsb-eye-btn").removeClass("active");
        } else {
            $("#bottom-panel").collapse("show");
            $("#bsb-add-btn").removeAttr('disabled');
            $("#bsb-del-btn").removeAttr('disabled');
            $("#bsb-edit-btn").removeAttr('disabled');
            $("#bsb-eye-btn").addClass("active");
        }
    });

    function clearFiles(){
        var $el = $('#file-upload');
        $el.wrap('<form>').closest('form').get(0).reset();
        $el.unwrap();
        files = undefined;
    }

    function uploadPresentation(){
        var json = JSON.stringify(tempPresentation);
        jQuery.ajax({
            type:"POST",
            url: "/ajax",
            dataType: "json",
            data: {'data': json, 'id': tempPresentation.id},
            success: function(data){
                presentation = data;
            },
            error: function(){
                alert('error');
            }
        });
    }

    function uploadFiles(event) {
        if(files === undefined || files.length == 0){
            uploadPresentation();
            return;
        }
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        var data = new FormData();
        $.each(files, function(key, value)
        {
            data.append(key, value);
        });

        $.ajax({
            url: '/upload',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'html',
            processData: false,
            contentType: false,
            success: function(data, textStatus, jqXHR)
            {
                if(typeof data.error === 'undefined')
                {
                    uploadPresentation();
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
});




