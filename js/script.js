$(function () {
    new WOW().init();

    $(".cat__list-item").on("click", function () {
        var dataCat = $(this).data("cat");
        $(".cat__list-item.active").removeClass("active");
        $(this).addClass("active");
        $(".cat__images-item.active").removeClass("active");
        $(".cat__images-item[data-cat='" + dataCat + "']").addClass("active");
    });


    if ($(".motorization").length > 0) {
        $(".motorization").slick({
            arrows: false,
            variableWidth: true,
            infinite: false
        });
    }

    if ($(window).scrollTop() > 0) {
        $("header").addClass("onscroll");
    } else {
        $("header").removeClass("onscroll");
    }
    $(window).scroll(function () {
        if ($(window).scrollTop() > 0) {
            $("header").addClass("onscroll");
        } else {
            $("header").removeClass("onscroll");
        }
    });


    $(".control__tab-item").on("click", function () {
        var dataControl = $(this).data("control");
        $(".control__tab-item.active").removeClass("active");
        $(this).addClass("active");
        $(".control__body-item.active").removeClass("active");
        $(".control__body-item[data-control='" + dataControl + "']").addClass("active");
    });


    if ($(".works__list").length > 0) {
        $(".works__list").slick({
            arrows: false,
            variableWidth: true
        });
    }


    $(".about__tab-item").on("click", function () {
        var dataTab = $(this).data("tab");
        $(".about__tab-item.active").removeClass("active");
        $(this).addClass("active");
        $(".about__content-item.active").removeClass("active");
        $(".about__content-item[data-tab='" + dataTab + "']").addClass("active");
    });


    if ($(".showcase-list").length > 0) {
        $(".showcase-list").slick({
            dots: true,
            autoplay: true,
            autoplaySpeed: 10000,
            responsive: [
                {
                    breakpoint: 900,
                    settings: {
                        arrows: false
                    }
                }
            ]
        });
    }


    $("a[href*='#']").on("click", function () {
        $("html, body").animate({
            scrollTop: $($.attr(this, 'href')).offset().top
        }, 1000);
        return false;
    });


    var menuTab;
    $(".menu__item").on("mouseover", function () {
        if ($(document).width() > 1023) {
            if ($(this).hasClass("active"))
                return;
            $(".menu__item.active").removeClass("active");
            $(this).addClass("active");
        }
    });

    $(".menu__item").on("click", function (e) {
        if ($(document).width() <= 1023
            && $(this).find(".menu__sub").length > 0
            && !$(".menu__sub-toggle").is(e.target)
            && $(".menu__sub-toggle").has(e.target).length === 0) {
            e.preventDefault();
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
            } else {
                $(this).addClass("active");
            }
        }
    });


    var scrollWidth = getScrollbarWidth();

    function getScrollbarWidth() {

        var outer = document.createElement('div');
        outer.style.visibility = 'hidden';
        outer.style.overflow = 'scroll';
        outer.style.msOverflowStyle = 'scrollbar';
        document.body.appendChild(outer);

        var inner = document.createElement('div');
        outer.appendChild(inner);

        var scrollbarWidth = (outer.offsetWidth - inner.offsetWidth);

        outer.parentNode.removeChild(outer);

        return scrollbarWidth;
    }

    $(".menu-toggle").on("click", function () {
        $("body").removeClass("open-search");
        $("body").addClass("open-menu").css("padding-right", scrollWidth);
        $("header").css("padding-right", scrollWidth);
    });


    $(".search-toggle").on("click", function () {
        $("body").removeClass("open-menu");
        $("body").addClass("open-search").css("padding-right", scrollWidth);
        $("header").css("padding-right", scrollWidth);
    });


    $(".menu__sub-toggle").on("click", function () {
        $(this).toggleClass("open-sub");
    });

    $(".aside-close").on("click", function () {
        $("body").removeClass("open-menu");
        $("body").removeClass("open-search");
        $("body").css("padding-right", 0);
        $("header").css("padding-right", 0);
    });


    $("body").on("click", function (e) {
        if ($("body").hasClass("open-menu")) {
            var div = $(".aside");
            if (!div.is(e.target)
                && !$(".menu-toggle").is(e.target)
                && $(".menu-toggle").has(e.target).length === 0
                && div.has(e.target).length === 0) {
                $("body").removeClass("open-menu");
            }
        }
        if ($("body").hasClass("open-search")) {
            var div = $(".aside");
            if (!div.is(e.target)
                && !$(".search-toggle").is(e.target)
                && $(".search-toggle").has(e.target).length === 0
                && div.has(e.target).length === 0) {
                $("body").removeClass("open-search");
            }
        }
    });


    if ($(".cat-curtains").length > 0) {
        $(".cat-curtains").slick({
            arrows: false,
            variableWidth: true,
            infinite: false
        });
    }

    // For Filter
    if ($('#filters').length > 0) {
        $('.filter-control').on('change', function (e) {
            const el = e.target;

            const preloader = `
                <div class="overlay">
                    <div class="preloader">
                        ${([1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map(() => '<div></div>')).join('')}
                    </div>
                </div>
            `;
            $('.hide-element').append(preloader);
            setTimeout(() => {
                location.href = $(el).data("filter-link");
            }, 1000);
        });
    }

    $(document).on('click', '.lp-card__option.lp-card__option--large', function () {
        $(this).parent('.lp-card__options').children('.lp-card__option').removeClass('d-none');
        $(this).fadeOut();
    });

    // Tabs
    $(document).on('click', '.si-tabs__caption', function () {
        $('.si-tabs__caption')
            .removeClass('si-tabs__caption--active')
            .eq($(this).index())
            .addClass('si-tabs__caption--active');

        $('.si-tabs__content')
            .attr('hidden', true)
            .eq($(this).index())
            .removeAttr('hidden');
    });

    // Gallery for single prod
    if ($('.flex-control-thumbs').length > 0) {
        $('.flex-control-thumbs')
            .wrap('<div class="flex-control-thumbs-wrap"></div>')
            .addClass('swiper-wrapper');

        $('.flex-control-thumbs li').addClass('swiper-slide');
    }

    if (document.querySelector('.flex-control-thumbs-wrap')) {

        const swiper = new Swiper('.flex-control-thumbs-wrap', {
            slidesPerView: 3,
            direction: 'horizontal',
            spaceBetween: 15,
            breakpoints: {
                576: {
                    direction: 'vertical',
                },

            }
        });

    }


});
