$(document).ready(function(){
    var presentation = $("#presentation").data("json");
    var current_category = presentation.categories[0];
    var current_subcategory = current_category.subcategories[0];
    var current_slide = current_subcategory.slides[0];
    sortSlides();

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

    function nextSlide(){
        var ids = $.map(current_subcategory.slides,function(n,i){
            return n.queue;
        });
        var highest = Math.max.apply(Math, ids);
        var lowest = Math.min.apply(Math, ids);

        var id = current_slide.queue;

    }

    function nextSubcategory(num){
        var p = current_category.subcategories;
        return p[($.inArray(num, p) + 1) % p.length];
    }

    function prevSubcategory(num){
        var p = current_category.subcategories;
        return p[($.inArray(num, p) - 1 + p.length) % p.length];
    }

    function loadCategories(){
        presentation.categories.forEach(function(item){
            if(item.name === current_category.name){
                $("#categories").append("<a id='"+ item.id+"' class = 'active' href='javascript:void(0);'>"+item.name+"</a>");
            } else {
                $("#categories").append("<a id='"+ item.id+"' href='javascript:void(0);'>"+item.name+"</a>");
            }
        });
        $("#categories>a").click(function(){changeCategory($(this))});
    }
    function loadSubcategories(){
        $("#subcategories").children().remove();
        current_category.subcategories.forEach(function(item){
            if(item.name === current_subcategory.name){
                $("#subcategories").append("<a id='"+ item.id+"' class = 'active' href='javascript:void(0);'>"+item.name+"</a>");
            } else {
                $("#subcategories").append("<a id='"+ item.id+"' href='javascript:void(0);'>"+item.name+"</a>");
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

    $(".nav-arrow-right").click(function(){
        var id = $.inArray(current_slide, current_subcategory.slides);
        if(id>=current_subcategory.slides.length-1){
            current_subcategory = nextSubcategory(current_subcategory);
            $("#subcategories>a").removeClass("active");
            $('#subcategories>a#'+current_subcategory.id).addClass("active");
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
            current_subcategory = prevSubcategory(current_subcategory);
            $("#subcategories>a").removeClass("active");
            $('#subcategories>a#'+current_subcategory.id).addClass("active");
            var next = current_subcategory.slides.length-1;
        } else {
            var next = id-1;
        }
        current_slide = current_subcategory.slides[next];
        $("#slide").attr({src: current_slide.image});
    });
});
