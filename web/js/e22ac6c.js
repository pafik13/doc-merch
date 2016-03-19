$(document).ready(function(){

    var presentation = $("#presentation").data("json");
    var tempPresentation =  jQuery.extend(true, {}, presentation);

    var current_category = first(tempPresentation.categories);
    var current_subcategory = first(current_category.subcategories);
    var current_slide = first(current_subcategory.slides);

    var slideModal = $("#slide-modal");
    var editCategoryModal = $("#edit-category-modal");
    var topPanel = $('#top-panel-btns');
    var botPanel = $('#bot-panel-btns');

    function first(array){
        for(var i in array){
            return array[i];
        }
    }

    function printObject(o, indent) {
        var out = '';
        if (typeof indent === 'undefined') {
            indent = 0;
        }
        for (var p in o) {
            if (o.hasOwnProperty(p)) {
                var val = o[p];
                out += new Array(4 * indent + 1).join(' ') + p + ': ';
                if (typeof val === 'object') {
                    if (val instanceof Date) {
                        out += 'Date "' + val.toISOString() + '"';
                    } else {
                        out += '{\n' + printObject(val, indent + 1) + new Array(4 * indent + 1).join(' ') + '}';
                    }
                } else if (typeof val === 'function') {

                } else {
                    out += '"' + val + '"';
                }
                out += ',\n';
            }
        }
        return out;
    }

    function sortSlides(){
        current_subcategory.slides.sort(function (a, b) {
            a= a.queue;
            b= b.queue;

            if(a > b) {
                return 1;
            } else if(a < b) {
                return -1;
            } else {
                return 0;
            }
        });
    }

    //function nextSlide(){
    //    var ids = $.map(current_subcategory.slides,function(n,i){
    //        return n.queue;
    //    });
    //    var highest = Math.max.apply(Math, ids);
    //    var lowest = Math.min.apply(Math, ids);
    //
    //    var id = current_slide.queue;
    //}

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
        loadSlide();
        botPanel.find('a').removeClass("active");
        item.addClass("active");
    }

    function loadSlide(){
        if(current_subcategory.slides.length==0){
            current_slide=null;
            $("#slide").attr({src: '/bundles/app/img/slides/placeholder.png'});
        }else {
            current_slide = current_subcategory.slides[0];
            $("#slide").attr({src: current_slide.image});
        }
    }


    //$("#slide").attr({src: current_slide.image});

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
        $("#slide").attr({src: current_slide.image});
    });

    $(".nav-arrow-left").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);
        if(id <= 0){
            current_subcategory = prevItem(current_subcategory,current_category.subcategories);
            botPanel.find('a').removeClass("active");
            botPanel.find('a:contains("'+current_subcategory.name+'")').addClass("active");
            var next = current_subcategory.slides.length-1;
        } else {
            var next = id-1;
        }
        current_slide = current_subcategory.slides[next];
        $("#slide").attr({src: current_slide.image});
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

    $('#save-pres-btn').click(function(){
        slideModal.modal("hide");
        //presentation = tempPresentation;

        var json = JSON.stringify(tempPresentation);
        jQuery.ajax({
            type:"POST",
            url: "/ajax",
            dataType: "json",
            data: {'data': json, 'id': tempPresentation.id},
            success: function(data){
                alert(printObject(data));
                presentation = data;
            },
            error: function(){
                alert('error');
            }
        });

    });

    editCategoryModal.on('show.bs.modal',function(){
       $('#new-category-input').val('');
    });

    slideModal.on('show.bs.modal', function(){
        tempPresentation =  jQuery.extend(true, {}, presentation);

        current_category = first(tempPresentation.categories);
        current_subcategory = first(current_category.subcategories);
        current_slide = first(current_subcategory.slides);

        sortSlides();
        loadCategories();
        loadSubcategories();
        loadSlide();
    });

//$(".top-panel .collapse").collapse('hide');
//    var lastHover;
//
//    function coll() {
//        if ((new Date() - lastHover) > 3000) {
//            $("#top-panel").collapse('hide');
//        }
//    }

    //$("#top-panel").collapse();
    //$("#top-hotspot").collapse();

    //$("#top-panel").hover(function() {
    //    lastHover = +new Date();
    //}, function() {
    //    lastHover = +new Date();
    //    setTimeout(coll, 3100);
    //    //$("#demo").stop(true,true).delay(2000).collapse('hide');
    //});



    //$("#tsb-add-btn").click(function() {
    //    if ($(this).attr('disabled')) {
    //        return;
    //    };
    //    var btn = "<label class=\"btn btn-primary\"><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\" checked> New Rad 1</label>"
    //    $("#top-panel-btns").append(btn);
    //    if ($("#top-panel-btns label").size() == 2) {
    //        $("#top-empty-btn").addClass("hide");
    //        $("#top-hotspot").removeClass("hide");
    //    }
    //});




    //$("#tsb-del-btn").click(function() {
    //    if ($(this).attr('disabled')) {
    //        return;
    //    };
    //    //$("#top-panel-btns div").first().remove();
    //    $("#top-panel-btns label.active").remove();
    //
    //    if ($("#top-panel-btns label").size() == 1) {
    //        //alert($("#top-empty-btn").attr('class'));
    //        $("#top-empty-btn").removeClass("hide");
    //        $("#top-hotspot").addClass("hide");
    //    };
    //
    //});

    //$("#bsb-add-btn").click(function() {
    //    alert($(this).attr('disabled'));
    //    if ($("#tsb-add-btn").attr('disabled')) {
    //        return;
    //    };
    //    var btn = "<label class=\"btn btn-primary\"><input type=\"radio\" name=\"options\" id=\"option1\" autocomplete=\"off\" checked> New Rad 1</label>"
    //    $("#bot-panel-btns").append(btn);
    //
    //});

    //$("#bsb-del-btn").click(function() {
    //    //$("#top-panel-btns div").first().remove();
    //    $("#bot-panel-btns label.active").remove();
    //});

    $("#top-hotspot").click(function() {
        //$("#demo").stop(true,true).delay(200).slideDown(300);
        $("#top-panel").collapse('show');
    });

    $("#tsb-eye-btn").click(function(){
        if ($("#tsb-eye-btn").hasClass("active")){
            $("#top-hotspot").collapse("hide");
            $("#top-panel").collapse("hide");
            $("#top-panel label.active").removeClass("active");
            $("#tsb-add-btn").attr('disabled', true);
            $("#tsb-del-btn").attr('disabled', true);
            $("#tsb-eye-btn").removeClass("active");
        } else {
            $("#top-panel").collapse("show");
            $("#top-hotspot").collapse("show");
            $("#tsb-add-btn").removeAttr('disabled');
            $("#tsb-del-btn").removeAttr('disabled');
            $("#tsb-eye-btn").addClass("active");
        }
    });

    //$("#top-empty-btn").click(function(){
    //    $("#tsb-add-btn").click();
    //});
//$("#dsa").hover(function () {
//	$("#dsa").stop(true,true).delay(500).collapse('show');
//}, function () {
//  $("#dsa").stop(true,true).delay(500).collapse('hide');
//});
//$("#").hover(function () {
//	$("#dsa").collapse('toggle');

});




