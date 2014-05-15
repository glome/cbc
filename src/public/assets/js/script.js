/* variables for selectboxes*/

var mouse_on_selectbox = false;
var mouse_on_select_checkbox = false;
var mouse_on_cat_selectbox = false;
var mouse_on_cat_menu = false;
var mouse_on_search_select = false;
var mouse_on_header_profile = false;

$(document).ready(function() {

    var init = function() {
        var $container = $('#grid');

        $container.imagesLoaded(function() {
            $container.masonry({
                itemSelector: '.product'
            });
        });


        $container.infinitescroll({
            navSelector: '#page-nav', // selector for the paged navigation
            nextSelector: '#page-nav a', // selector for the NEXT link (to page 2)
            itemSelector: '.product', // selector for all items you'll retrieve
            loading: {
                finishedMsg: translations.no_more_products,
                img: '/assets/img/Mucina.gif',
                msgText: '',

            },
            state: {
                currPage: 1,
                 finishedMsg: translations.no_more_products
            }

        },
        function(newElements) {
            var $newElems = $(newElements).css({opacity: 0});
            $newElems.imagesLoaded(function() {
                $newElems.animate({opacity: 1});
                $container.masonry('appended', $newElems);
                normalizeProducts();
            });
        }
        );
    },

    updateProducts = (function(init) {
        return function() {
            var $container = $('#grid');
            $container.masonry('destroy');
            $container.empty();
            $container.addClass('loading');

            $.get(window.location.href + '?xhr', function(data) {
                $container.removeClass('loading');
                $container.html(data);
                init();
                normalizeProducts();
            });
        };
    }(init)),

    normalizeProducts = function() {
        $(".product").each(function() {
            if ($(this).isOnScreen() === true) {
                $(this).addClass("animated");
            }
        });
    };

    init();

    $('#remote-content').load('/article');

    normalizeProducts();
    $(window).on("scroll", function() {
        $(".product").each(function() {
            if ($(this).isOnScreen() === true) {
                $(this).addClass("animated");
            }
        });
    });

    /* Close open elements on click outside the element */
    $(".select-box").hover(function() {
        mouse_on_selectbox = true;
    }, function() {
        mouse_on_selectbox = false;
    });
    $(".select-checkbox").hover(function() {
        mouse_on_select_checkbox = true;
    }, function() {
        mouse_on_select_checkbox = false;
    });
    $(".cat-menu").hover(function() {
        mouse_on_cat_menu = true;
    }, function() {
        mouse_on_cat_menu = false;
    });
    $(".search-select").hover(function() {
        mouse_on_search_select = true;
    }, function() {
        mouse_on_search_select = false;
    });
    $(".header-profile").hover(function() {
        mouse_on_header_profile = true;
    }, function() {
        mouse_on_header_profile = false;
    });
    $(".cat-select-box").hover(function() {
        mouse_on_cat_selectbox = true;
    }, function() {
        mouse_on_cat_selectbox = false;
    });

    $("body").on("click", function() {
        if (mouse_on_selectbox === false) {
            $(".select-box.active").removeClass("active");
        }
        if (mouse_on_cat_menu === false) {
            $(".cat-menu.opened").removeClass("opened");
        }
        if (mouse_on_search_select === false) {
            $(".search-select.opened").removeClass("opened");
        }
        if (mouse_on_header_profile) {
            $(".header-profile.opened").removeClass("opened");
        }
        if (mouse_on_cat_selectbox === false) {
            $(".cat-select-box.opened").removeClass("opened");
        }
        if (mouse_on_select_checkbox === false) {
            $(".select-checkbox.active").removeClass("active");
        }
    });

    /* select box function */
    $(".select-box").on("click", function() {
        if ($(window).width() > 730) {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
            } else {
                $(".select-box").removeClass("active");
                $(this).addClass("active");
            }
        }
    });

    $(".select-box ul li").on("click", function() {

        if ($(window).width() > 730) {
            $(this).parent().children().removeClass("selected");
            $(this).addClass("selected");
            var txt = $(this).text();
            $(this).parent().prev(".selected").text(txt);
            $.get('/options?param=' + txt).done(function() {
                window.location = window.location.href;
            });
        } else {
            $(this).parent().children().removeClass("selected");
            $(this).addClass("selected");
        }
    });

    /* Mobile filter get width*/
    getWidth();
    $(window).on("resize", function() {
        getWidth();
    });

    /* Moboile open filter */
    $(".mob-filter-btn").on("click", function(e) {
        var target = $(this).parent(),
            rows;
        e.preventDefault();
        if ($(".header-profile").hasClass("active")) {
            $(".header-profile").removeClass("active");
        }
        if ($(".redeem-btn-enabled").hasClass("active")) {
            $(".redeem-btn-enabled").removeClass("active");
            $(".redeem-inlay.open").removeClass("open");
        }

        rows = target.children(".row");
        if (target.hasClass("mob-opened")) {
            rows.children(".mob-opened").removeClass("mob-opened");
            target.removeClass("mob-opened");
            rows.children(".lbl.col-1").removeClass("mob-opened");
        } else {
            target.addClass("mob-opened");
            rows.children(".form-item.col-1").addClass("mob-opened");
            rows.children(".lbl.col-1").addClass("mob-opened");
        }
    });

    $(".location-filter .row .lbl").on("click", function(e) {
        var selected = $(this).attr("id");
            target = $(this).parent();
        e.preventDefault();

        target.children().removeClass("mob-opened");
        target.next(".row").children().removeClass("mob-opened");

        $(this).addClass("mob-opened");
        target.next(".row").children("." + selected).addClass("mob-opened");


    });

    /* Download bar */
    var time = setTimeout(function() {
        $(".download-bar").addClass("show");
    }, 3000);

    $(".close-download-bar").on("click", function(e) {
        e.preventDefault();
        $(".download-bar").removeClass("show");
        $.get('/options/download/1');
    });

    /* Header profile button */
    $(".header-profile-btn").on("click", function(e) {
        if ($(window).width() < 769) {
            e.preventDefault();
            if ($(".redeem-btn-enabled").hasClass("active")) {
                $(".redeem-btn-enabled").removeClass("active");
                $(".redeem-inlay.open").removeClass("open");
            }

            e.preventDefault();
            if ($(this).parent().hasClass("active")) {
                $(this).parent().removeClass("active");
            } else {
                if ($(".location-filter").hasClass("mob-opened")) {
                    $(".location-filter").removeClass("mob-opened");
                }
                $(this).parent().addClass("active");
            }
        }
    });

    /* INDEX page - about tabs */
    $(".tab-menu li a").on("click", function(e) {


        var target = $(this).attr("href");
        $(".tab-menu li").removeClass("active");
        $(".tab-content .tab").removeClass("active");
        $(this).parent().addClass("active");
        $(target).addClass("active");


    });

    if(window.location.hash !== "") {
        var a = window.location.hash;
        $(".tab-menu li").removeClass("active");
        $(".tab-content .tab").removeClass("active");
        $(".tab-menu li a[href='" + a + "']").parent().addClass("active");
        $(a).addClass("active");

    }


    /* PROFILE page tabs */
    $(".profile-tabs ul li a").on("click", function(e) {
        e.preventDefault();
        $(".profile-tabs ul li").removeClass("active");
        $(".profile-tab-content .tab").removeClass("active");

        var target = $(this).attr("href");
        $(this).parent().addClass("active");
        $("." + target).addClass("active");
    });


    /* selectbox with checkbox */
    $(".select-checkbox").on("click", function() {
        if ($(window).width() > 730) {
            $(this).toggleClass("active");
        }

    });

    $(".select-checkbox ul li").on("click", function(e) {

        var txt = $(this).text();
        if ($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            $.get('/options?del=' + txt);
        } else {
            $(this).addClass("selected");
            $.get('/options?param=' + txt);
        }

        var selecteditems = "";
        $(this).parent().children(".selected").each(function() {
            selecteditems = selecteditems + $(this).text() + ", ";
        });
        if (selecteditems === "") {
            selecteditems = "Choose location";
            $(".checkbox-content").text(selecteditems);
        } else {
            $(".checkbox-content").text(selecteditems);
        }
    });

    /* Shop by category select */
    $('.cat-menu .title').on("click", function(e) {
        var target = $(this).parent();
        e.preventDefault();

        if ($(window).width() > 730) {
            if (target.hasClass("opened")) {
                target.removeClass("opened");
                $(".cat-menu ul li").removeClass("mob-opened");
            } else {
                target.addClass("opened");
            }
        }
        if ($(window).width() < 731) {
            target.toggleClass("mob-opened");
        }
    });

    $(".cat-menu ul li span").on("click", function(e) {
        e.preventDefault();
        if ($(this).parent().hasClass("mob-opened")) {
            $(this).parent().removeClass("mob-opened");
        } else {
            $(".cat-menu ul li.mob-opened").removeClass("mob-opened");
            $(this).parent().addClass("mob-opened");
        }

    });

    $(".accordion-dropdown li .item").on("click", function(e) {
        e.preventDefault();
        if ($(this).parent().hasClass("opened")) {
            $(this).parent().removeClass("opened");
        } else {
            $(".accordion-dropdown li").removeClass("opened");
            $(this).parent().addClass("opened");
        }
    });

    $(".cat-select-box .selected-item").on("click", function(e) {
        e.preventDefault();

        if ($(this).parent().hasClass("opened")) {
            $(".accordion-dropdown li").removeClass("opened");
            $(this).parent().removeClass("opened");
        } else {
            $(this).parent().addClass("opened")
        }
    });

    /* Flexslider */
    $(".slider").flexslider({
        directionNav: false,
        controlNav: true
    });
    $(".cat-slider").flexslider({
        directionNav: false,
        controlNav: true
    });

    /* SEARCH */
    $(".search-btn").on("click", function() {
        if ($(window).width() < 731) {
            $(this).parent().toggleClass("mob-opened");
        }
    });

    $(".search-select .selected").on("click", function(e) {
        e.preventDefault();
        $(this).parent().toggleClass("opened");
    });

    /* Add to wishlist */
    $("body").on("click", ".add-to-wishlist-btn, .add-to-wishlist", function(e) {
        var counter = $('#wish-counter'),
                indicator = $('#wish-indicator'),
                total = parseInt(counter.text()) || 0;
        e.preventDefault();
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $.get(this.href.replace('wish', 'spurn'));
            total -= 1;
        } else {
            $(this).addClass("active");
            $.get(this.href);
            total += 1;
        }
        if (total === 1) {
            indicator.addClass('amount')
        }
        if (total === 0) {
            indicator.removeClass('amount')
            total = '';
        }

        counter.text(total);
        indicator.text(total);
    });

    $('.delete-from-wishlist').on('click', function(e) {
        var parent = this.parentNode,
                counter = $('#wish-counter'),
                indicator = $('#wish-indicator'),
                total = parseInt(indicator.text()) || 0;
        e.preventDefault();
        $.get(this.href);
        $(parent).fadeOut(200, function() {
            $(parent).remove();
        });
        total -= 1;
        console.log(parseInt(indicator.text()));
        if (total === 0) {
            indicator.removeClass('amount')
            total = '';
            $('#wish-notice').removeClass('hidden');
        }
        counter.text(total);
    });

    /* MOBILE Cashback history - order */
    $(".cashback-history .order .order-title").on("click", function(e) {
        e.preventDefault();
        $(this).parent().toggleClass("opened");
    });

    $(".search-select ul li a").on("click", function(e) {
        var txt = $(this).text(),
            counter = 0;
        e.preventDefault();
        $.get('/options/search/' + $(this.parentNode).index());
        $(this).parent().parent().children().removeClass("selected");
        $(this).parent().addClass("selected");
        $(".search-select span.selected").text(txt);
    });

    /* FILTER SIDEBAR */
    /*
     $(".filter-cat .filter-content-wrap ul li a").on("click", function(e) {
     e.preventDefault();

     if (!$(this).parent().hasClass("selected")) {
     $(".filter-cat .filter-content-wrap ul li").removeClass("selected");
     $(this).parent().addClass("selected");
     }
     });
     */
    $(".filter-brand .filter-content-wrap ul li a, .filter-retailer .filter-content-wrap ul li a").on("click", function(e) {
        var $this = $(this);
        var href = this.href;

        if ($this.parent().hasClass("selected")) {
            $this.parent().removeClass("selected");
            if ($this.parent().siblings('.selected').length === 0) {
                $this.parents('ul').children().first().addClass("selected");
                if (href.indexOf('clear') == -1) {
                    href += '&clear';
                }
            }
        } else {
            if ($this.hasClass('action-reset')) {
                $this.parents('ul').children().removeClass("selected");
            } else {
                $this.parents('ul').children().first().removeClass("selected");
            }
            $this.parent().addClass("selected");
        }

        $.get(href, updateProducts);

        e.preventDefault();
    });

    $('#action-reset').on('click', function(e) {
        var targets = $('#retailer-list li');
        e.preventDefault();
        $.get(this.href);
        targets.removeClass('selected');
        targets.first().addClass('selected');
        updateProducts();
    });

    if ($(window).width() > 730) {
        $(".filter-content-wrap").jScrollPane({
            horizontalDragMaxWidth: 0
        });
    }

    /* FOOTER MENU */
    $(".footer-menu ul .col .title").on("click", function(e) {
        e.preventDefault();

        if ($(this).parent().hasClass("mob-opened")) {
            $(this).parent().removeClass("mob-opened");
        } else {
            $(".footer-menu ul .col").removeClass("mob-opened");
            $(this).parent().addClass("mob-opened");
        }
    });

    /* INDEX MOST POPULAR CATEGORIES */
    $(".popular-product-cat .container ul li a").on("click", function(e) {
        e.preventDefault();
        if ($(window).width() > 730) {
            var target = $(this).attr("href");
            $(".popular-product-cat .container ul li").removeClass("selected");
            $(".popular-product-cat .cat-companies ul").removeClass("selected");
            $(this).parent().addClass("selected");
            $(".popular-product-cat .cat-companies ." + target).addClass("selected");
            $(".popular-product-cat .cat-companies ul.selected").css({
                "left": 0
            });
        }
    });

    $(".product-cat-nav-right").on("click", function(e) {
        e.preventDefault();

        var item = $(".popular-product-cat .product-cat .container ul li.selected");
        var nextItem = item.next();
        var target = nextItem.children().attr("href");
        if (nextItem.length > 0) {
            item.removeClass("selected");
            nextItem.addClass("selected");
            $(".product-cat-nav-left").removeClass("disabled");
            if (nextItem.next().length < 1) {
                $(this).addClass("disabled");
            }
            $(".popular-product-cat .cat-companies ul").removeClass("selected");
            $(".popular-product-cat .cat-companies ." + target).addClass("selected");
            $(".popular-product-cat .cat-companies ul.selected").css({
                "left": 0
            });
        }
    });

    $(".product-cat-nav-left").on("click", function(e) {
        e.preventDefault();

        var item = $(".popular-product-cat .product-cat .container ul li.selected");
        var nextItem = item.prev();
        var target = nextItem.children().attr("href");
        if (nextItem.length > 0) {
            item.removeClass("selected");
            nextItem.addClass("selected");
            if (nextItem.prev().length < 1) {
                $(this).addClass("disabled");
            }
            $(".product-cat-nav-right").removeClass("disabled");
            $(".popular-product-cat .cat-companies ul").removeClass("selected");
            $(".popular-product-cat .cat-companies ." + target).addClass("selected");
            $(".popular-product-cat .cat-companies ul.selected").css({
                "left": 0
            });
        }
    });

    $(".cat-carousel-nav .nav-right").on("click", function(e) {
        e.preventDefault();

        var contentWidth = $(".content-width").width();
        var itemWidth = $(".cat-companies .container ul li").width() + parseInt($(".cat-companies .container ul li").css("margin-left")) + parseInt($(".cat-companies .container ul li").css("margin-right"));
        var position = parseInt($(".popular-product-cat .cat-companies ul.selected").css("left"));
        var maxleft = (contentWidth - itemWidth) * (-1);
        if (position > maxleft) {
            var new_position = position - itemWidth;
            $(".popular-product-cat .cat-companies ul.selected").css({
                "left": new_position
            });
        }
    });
    $(".cat-carousel-nav .nav-left").on("click", function(e) {
        e.preventDefault();

        var contentWidth = $(".content-width").width();
        var itemWidth = $(".cat-companies .container ul li").width() + parseInt($(".cat-companies .container ul li").css("margin-left")) + parseInt($(".cat-companies .container ul li").css("margin-right"));
        var position = parseInt($(".popular-product-cat .cat-companies ul.selected").css("left"));
        var maxleft = (contentWidth - itemWidth) * (-1);
        if (position < 0) {
            var new_position = position + itemWidth;
            $(".popular-product-cat .cat-companies ul.selected").css({
                "left": new_position
            });
        }
    });

    $(window).on("resize", function() {
        if ($(window).width() < 731) {
            $(".popular-product-cat .cat-companies ul.selected").css({
                "left": 0
            });
        }
    });

    /* FIXED sidebar */
    /*
     $(window).on("scroll",function(){
     var top = $(".products-page").offset().top;
     var footer = $(".page-footer").offset().top;
     var windowHeight = $(window).height();
     var top2 = footer - windowHeight;
     var scrolled = $(window).scrollTop();
     if(top < scrolled && top2 > scrolled) {
     $(".filter-sidebar").addClass("fixed");
     } else {
     if(top > scrolled || top2 < scrolled){
     $(".filter-sidebar").removeClass("fixed");
     }
     if(top2 < scrolled) {
     $(".filter-sidebar").removeClass("absolute");
     }


     }

     console.log(scrolled);
     });

     */
    $(window).on("scroll", function() {
        var scrolled = $(window).scrollTop();
        var html = "<a href='' class='nav-up'>" + translations.to_top + "</a>";
        if (scrolled > 1000) {
            if ($(".nav-up").length < 1) {
                $("body").append(html);
            }
        } else {
            $(".nav-up").remove();

        }
    });

    $("body").on("click", ".nav-up", function(e) {
        e.preventDefault();
        $("html,body").animate({
            "scrollTop": 0
        }, 500);
        console.log("bu");
    });

    /* Overlay */
    $(".buy-now-btn").on("click", function(e) {
        e.preventDefault();
        var link = $(this).attr("href");

        $(".overlay2").addClass("open");

        window.open(link, '_blank');

    });

    $(".close-overlay, .overlay-bg").on("click", function(e) {
        e.preventDefault();
        $(".overlay").removeClass("open");
    });

    /* Filter mobile */
    $(".filter-title").on("click", function(e) {
        e.preventDefault();
        if ($(this).parent().hasClass("mob-opened")) {
            $(this).parent().removeClass("mob-opened");
        } else {
            $(".filter-title").parent().removeClass("mob-opened");
            $(this).parent().addClass("mob-opened");
        }
    });

    /* options bar */
    $(".options-bar .order .label").on("click", function() {
        if ($(window).width() < 731) {
            $(this).parent().toggleClass("mob-opened");
        }
    });

    $(".options-bar .order .select-box ul li").on("click", function() {
        if ($(window).width() < 731) {
            if (!$(this).hasClass("selected")) {
                $(this).parent().children().removeClass("selected");
                $(this).addClass("selected");
            }
        }
    });

    /* SEARCH AUTOCOMPLETE */
    $("#search-input").autocomplete({
        source: '/search/autocomplete',
        minLength: 3,
        appendTo: ".search-form"
    });

    /* Aurum IT paraksts */
    $(".aurumit").on("mouseenter", function() {
        $("html, body").animate({scrollTop: $(document).height() + 30}, 500);
    });

    /* Masonry grid */
    /*
     new AnimOnScroll(document.getElementById('grid'), {
     minDuration: 0.4,
     maxDuration: 0.7,
     viewportFactor: 0.01
     });
     */

    /* Form validation */
    $("body").on("submit",".contact-form", function() {
        var form = $(this);
        var msg = $("textarea.comments", form).val();
        var html = "<div class='error'>* " + translations.form_error + "</div>";
        if (msg.length === 0) {
            if (msg.length === 0) {
                $("textarea.comments", form).addClass("error-input");

                if ($("textarea.comments", form).next(".error").length > 0) {
                    $("textarea.comments", form).next(".error").remove();
                }
                $("textarea.comments", form).after(html);
            } else {
                $("textarea.comments", form).removeClass("error-input");
                if ($("textarea.comments", form).next(".error").length > 0) {
                    $("textarea.comments", form).next(".error").remove();
                }
            }

            return false;
        } else {
            $.post(
               '/', form.serialize(),
                function(data){
                  form.addClass('hidden');
                  form.prev().removeClass('hidden');
                }
              );
            return false;
        }

    });
});

/* SELECT BOX */
(function() {

    if ($(window).width() > 740) {
        $("select").each(function() {
            var selected = $(this).children("option:selected").val();
            var options = "";
            $(this).children("option").each(function() {
                if ($(this).val() != selected) {
                    options = options + "<li class='" + $(this).val() + "'>" + $(this).val() + "</li>";
                }

            });

            var html = "<div class='select-box'>"
                    + "<span class='selected'>" + selected + "</span>"
                    + "<ul>" + options + "</ul>"
                    + "</div>";
            $(this).parent().append(html);
        });
    }
}());

/* MOBILE get full width*/
function getWidth() {
    if ($(window).width() < 730) {
        var content_width = $(".wrap").width();
        $(".location-filter .row").width(content_width);
        $(".header-profile .profile-menu").width(content_width);
        $(".search-form").width(content_width);
        $(".filter-content-wrap").width(content_width);
    } else {
        $(".location-filter .row").removeAttr("style");
        $(".header-profile .profile-menu").removeAttr("style");
        $(".search-form").removeAttr("style");
    }

}

// JQUERY VIEWPORT PLUGIN
$.fn.isOnScreen = function() {

    var win = $(window);

    var viewport = {
        top: win.scrollTop(),
        left: win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

};



