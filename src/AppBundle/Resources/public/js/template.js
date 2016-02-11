/**
 * Created by dasha on 09.02.16.
 */
$(document).ready(function(){
    var presentation = $("#presentation").data("json");
    var current_category = presentation.categories[0];
    var current_subcategory = current_category.subcategories[0];
    var current_slide = current_subcategory.slides[0];

    function loadCategories(){
        presentation.categories.forEach(function(item){
            if(item.name === current_category.name){
                $("#categories").append("<a class = 'active' href='javascript:void(0);'>"+item.name+"</a>");
            } else {
                $("#categories").append("<a href='javascript:void(0);'>"+item.name+"</a>");
            }
        });
        $("#categories>a").click(function(){changeCategory($(this))});
    }
    function loadSubcategories(){
        $("#subcategories").children().remove();
        current_category.subcategories.forEach(function(item){
            if(item.name === current_subcategory.name){
                $("#subcategories").append("<a class = 'active' href='javascript:void(0);'>"+item.name+"</a>");
            } else {
                $("#subcategories").append("<a href='javascript:void(0);'>"+item.name+"</a>");
            }
        });
        $("#subcategories>a").click(function(){changeSubcategory($(this))});
    }
    function changeCategory(item){
        var name  = item.html();
        current_category = $.grep(presentation.categories, function(e){
            return e.name === name;
        })[0];
        current_subcategory = current_category.subcategories[0];
        loadSubcategories();
        current_slide = current_subcategory.slides[0];
        $("#slide").attr({src: current_slide.image});
        $("#categories>a").removeClass("active");
        item.addClass("active");
    }

    function changeSubcategory(item){
        var name  = item.html();
        current_subcategory = $.grep(current_category.subcategories, function(e){
            return e.name === name;
        })[0];
        current_slide = current_subcategory.slides[0];
        $("#slide").attr({src: current_slide.image});
        $("#subcategories>a").removeClass("active");
        item.addClass("active");
    }

    loadCategories();
    loadSubcategories();
    $("#slide").attr({src: current_slide.image});

    $("#next").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);
        var next = (id >= current_subcategory.slides.length-1) ? 0 : id+1;
        current_slide = current_subcategory.slides[next];
        $("#slide").attr({src: current_slide.image});
    });

    $("#prev").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);
        var next = (id <= 0) ? current_subcategory.slides.length-1 : id-1;
        current_slide = current_subcategory.slides[next];
        $("#slide").attr({src: current_slide.image});
    });
});
