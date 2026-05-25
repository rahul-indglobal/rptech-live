// JavaScript Document
//var $ = jQuery.noConflict();
// app/design/frontend/Namespace/themename/web/js/main.js
require(['jquery', 'bootstrap'], function ($) {
    function menuClose() {
        if (window.matchMedia("(max-width: 991px)").matches) {

            $(document).mouseup(function (e)
            {
                var container = $(".mb-menu-overlay");
                if (!container.is(e.target) && container.has(e.target).length === 0)
                {
                    $('.magemenu-menu').removeClass('show');
                    $('body').removeClass('nav-open');
                    $('.btn-menu').children('.ico-toggle').removeClass('fa-times').addClass('fa-bars');
                }
            });
        }
    }
    $(document).ready(function () {
        function inputcheck() {
            console.log('inputcheck function-defined')
            $('input.form-control').each(function () {
                if ($(this).val().length > 0)
                {
                    $(this).parent('.form-group').addClass('input--filled');
                } else
                {
                    $(this).parent('.form-group').removeClass('input--filled');
                }
            });

            $('input._has-datepicker').each(function () {
                if ($(this).val().length > 0)
                {
                    $(this).parent('.form-group').addClass('input--filled');
                } else
                {
                    $(this).parent('.form-group').removeClass('input--filled');
                }
            });
        }
        menuClose();
        inputcheck();
        if(window.innerWidth < 768){
            var mmenu = `<div id="common_ham_menu" class="menu-btn-1">
                            <span></span>
                        </div>`
            $('header').append(mmenu);
            $(document).on("click","#common_ham_menu",function(event) {
                //alert("click");
                event.preventDefault();
                $('body').toggleClass('menu-active');
            });
        }
        /* customer dashboard mobile nav */
        $('.mobile-nav').on('click', function () {
            $(this).toggleClass('active');
            $(this).parent('.account-nav').find('#account-nav').toggleClass('active');
        });

		$("#globalbrands").click(function() {
			$('html,body').animate({
				scrollTop: $(".aur-brand").offset().top
			},'slow');
		});
        $('.increaseQty, .decreaseQty').on("click", function () {
            var $this = $(this);
            var ctrl = ($(this).attr('id').replace('-upt', '')).replace('-dec', '');
            var currentQty = $("#cart-" + ctrl + "-qty").val();
            if ($this.hasClass('increaseQty')) {
                if (currentQty < 99) {
                    var newAdd = parseInt(currentQty) + parseInt(1);
                    $("#cart-" + ctrl + "-qty").val(newAdd);
                }
            } else {
                if (currentQty > 1) {
                    var newAdd = parseInt(currentQty) - parseInt(1);
                    $("#cart-" + ctrl + "-qty").val(newAdd);
                }
            }
        });

        $('.brands-sec .btn-va').on('click', function () {
            $(this).toggleClass('close');
            $(this).parents('.brands-sec').find('.brand').toggleClass('hidden');
            $(this).parents('.brands-sec').find('.brand-view-all').toggleClass('hidden');
        });

        $('.mb-filter button').on('click', function () {
            $('.filters-wrapper .filter-options').show();
        });

        $('.filters-wrapper .btn-filter-close').on('click', function () {
            $('.filters-wrapper .filter-options').hide();
        });

        $('.wrap-menu .dropdown-item > .m-link').append('<span class="toggle-submenu fa fa-plus"></span>');


        /*$('[data-toggle=collapse]').click(function (event) {
            $(this).toggleClass(function() {
              if ( $( this ).attr('aria-expanded') == 'false' ) {
                $( this ).attr('aria-expanded','true');
                //$('#sidebarNavMain').addClass('collapse in');
              } else {
                $( this ).attr('aria-expanded','false');
                //$('#sidebarNavMain').removeClass('in');
              }
                return "collapsed";
            });
        });

        $('.sidebar-sm .btn-menu').click(function (event) {
            if ($('.btn-menu').attr('data-cat') == 'cl') {
                $('.btn-menu').attr('aria-expanded','true');
                $('#sidebarNavMain').addClass('collapse in');
                $('.btn-menu').attr('data-cat','ls');
            } else {
                $('.btn-menu').attr('aria-expanded','false');
                $('#sidebarNavMain').removeClass('in');
                $('.btn-menu').attr('data-cat','cl');
              }
                return "collapsed";
        });*/
        $('[data-ref=topmenu]').click(function (event) {
            $(this).toggleClass(function() {
              if ( $( this ).attr('aria-expanded') == 'false' ) {
                $( this ).attr('aria-expanded','true');
                $('#sidebarNavMain').addClass('collapse in');
              } else {
                $( this ).attr('aria-expanded','false');
                $('#sidebarNavMain').removeClass('in');
              }
                return "collapsed";
            });
        });

        $('.closeWrap').click(function (event) {
            $('[data-toggle=collapse]').addClass('collapsed').attr('aria-expanded','false');
            $('#sidebarNavMain').removeClass('in');
        });

        $('.navigation .btn-menu').click(function (event) {
            event.stopPropagation();
            $(this).find('.ico-toggle').toggleClass('fa-bars fa-times');
            $('body').toggleClass('nav-open');
            $(this).parents('.navigation').find('.magemenu-menu').toggleClass('show');
            $(this).parents('.navigation').find('.toggle-submenu').removeClass('fa-minus').addClass('fa-plus');
            $(this).parents('.navigation').find('.submenu').removeClass('open');
        });

        $(document).click(function (e) {
            var container = $(".panel-collapse.in");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $( '[data-ref=topmenu]' ).attr('aria-expanded','false').addClass('collapsed');
                $('#sidebarNavMain').removeClass('in');
                /*$('.magemenu-menu').removeClass('show');
                $('body').removeClass('nav-open');
                $('.btn-menu').children('.ico-toggle').removeClass('fa-times').addClass('fa-bars');*/
            }
        });

        $('.level-top > .m-link .toggle-submenu').on('click', function () {
            //$(this).parent('.m-link').toggleClass('active');
            $(this).parents('.dropdown-item').siblings().find('.toggle-submenu').removeClass('fa-minus').addClass('fa-plus');
            $(this).parents('.dropdown-item').siblings().find('.submenu').removeClass('open');
            $(this).toggleClass('fa-plus fa-minus');
            $(this).parent('.m-link').next('.submenu').toggleClass('open');
        });

        $('body').on('click', '.level0.open .toggle-submenu', function () {
            $(this).parents('.parent').siblings().find('.toggle-submenu').removeClass('fa-minus').addClass('fa-plus');
            $(this).parents('.parent').siblings().find('.submenu').removeClass('open');
            $(this).toggleClass('fa-plus fa-minus');
            $(this).parent('.m-link').next('.submenu').toggleClass('open');
        });

        var shrinkHeader = 100;
        $(window).scroll(function () {
            var scroll = getCurrentScroll();
            if (scroll >= shrinkHeader) {
                $('header').addClass('shrink');
            } else {
                $('header').removeClass('shrink');
            }
        });

        $('.form-group .form-control').on('change keyup', function (e) {
            if ($(this).val().length > 0)
            {
                $(this).parent('.form-group').addClass('input--filled');
            } else
            {
                $(this).parent('.form-group').removeClass('input--filled');
            }
        });

        $(document).on('change keyup', '#dob', function (e) {
            if ($(this).val().length > 0)
            {
                $(this).parent('.form-group').addClass('input--filled');
            } else
            {
                $(this).parent('.form-group').removeClass('input--filled');
            }
        });

//      $('.form-group ._has-datepicker').on('change keyup', function (e) {
//                    console.log("lalita")
//          if ($(this).val().length > 0)
//          {
//              $(this).parent('.form-group').addClass('input--filled');
//          } else
//          {
//              $(this).parent('.form-group').removeClass('input--filled');
//          }
//      });


        $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).parent().siblings().removeClass('open');
            $(this).parent().toggleClass('open');
        });


        /* cart */

//      $('.cart-row .btn-plus').click(function (e) {
//          e.preventDefault();
//          var $this = $(this),
//              $input = $this.prev('input'),
//              $parent = $input.closest('.quantity'),
//              newValue = parseInt($input.val()) + 1;
//          $input.val(newValue);
//      });
//
//      $('.cart-row .btn-minus').click(function (e) {
//          e.preventDefault();
//          var $this = $(this),
//              $input = $this.next('input'),
//              $parent = $input.closest('.quantity'),
//              newValue = parseInt($input.val()) - 1;
//          if (newValue >= 1) {
//              $input.val(newValue);
//          }
//      });

        // This button will increment the value
        $('[data-quantity="plus"]').click(function (e) {
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            fieldName = $(this).attr('data-field');
            // Get its current value
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());
            // If is not undefined
            if (!isNaN(currentVal) && currentVal < 100) {
                // Increment
                $('input[name=' + fieldName + ']').val(currentVal + 1);
            } else {
                // Otherwise put a 0 there
                $('input[name=' + fieldName + ']').val(0);
            }
        });
        // This button will decrement the value till 0
        $('[data-quantity="minus"]').click(function (e) {
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            fieldName = $(this).attr('data-field');
            // Get its current value
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());
            // If it isn't undefined or its greater than 0
            if (!isNaN(currentVal) && currentVal > 0) {
                // Decrement one
                $('input[name=' + fieldName + ']').val(currentVal - 1);
            } else {
                // Otherwise put a 0 there
                $('input[name=' + fieldName + ']').val(0);
            }
        });

        /* Products Page */

        // price filter

        if ($('#input-select').length) {
            var select = document.getElementById('input-select');

            // Append the option elements
            for (var i = 0; i <= 500; i++) {

                var option = document.createElement("option");
                option.text = i;
                option.value = i;

                select.appendChild(option);
            }
        }

        $(".toggle-filter").on('click', function () {
            $(this).next(".mydiv").toggle();
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
        });

        var enqModal = document.getElementById('enquiryModal');

        $('.btn-enq').click(function () {
            $('#enquiryModal').show();
        });
        $('#enquiryModal .close').click(function () {
            $('#enquiryModal').hide();
        });

        window.onclick = function (event) {
            if (event.target == enqModal) {
                enqModal.style.display = "none";
            }
        }

        /* checkout page  */
        $('#addNewAddress').click(function () {
            $('.add-address-form').toggle();
            $(this).find('.add-plus').toggleClass('fa-plus fa-minus');
        });

        $('.banner-slider').on('setPosition', function () {
            //    jbResizeSlider();

        });

        $('.filter-options-title').click(function() {
            $(this).next('.filter-options-content').slideToggle();
            $(this).toggleClass('openFilterTtl');
        });



        function closeAllSelect(elmnt) {
            var x, y, i, arrNo = [];
            x = document.getElementsByClassName("select-items-my");
            y = document.getElementsByClassName("select-selected-my");
            for (i = 0; i < y.length; i++) {
                if (elmnt == y[i]) {
                    arrNo.push(i)
                } else {
                    y[i].classList.remove("select-arrow-active-my");
                }
            }
            for (i = 0; i < x.length; i++) {
                if (arrNo.indexOf(i)) {
                    x[i].classList.add("select-hide-my");
                }
            }
        }

        $('[data-toggle="tooltip"]').tooltip();
    });

    /** Equalheight **/
    function equalheight() {
        $(".equalheight_wrapper").each(function () {
            var maxheight = 0;
            $(this).find(".equalheight").each(function () {
                if (maxheight < ($(this).innerHeight())) {
                    maxheight = $(this).innerHeight();
                }
            });
            $(this).find(".equalheight").css("min-height", maxheight);

        });

    }

    function  footeradj() {
        var footerHgt = $('footer').height();
        $('footer').css({'margin-top': -footerHgt});
        $('.main-wrapper').css({'padding-bottom': footerHgt + 20});
    }

    function changedPosition() {
        var doc_Height = $(document).height();
        var reg_height = $('.reg-wrapper').height();
        var ver_height = $('.verify-wrapper').height();
        var log_height = $('.login-page .login-wrapper').height();
        var b2blog_height = $('.b2b_login_page .b2b-log-wrapper').height();

        if (doc_Height <= reg_height) {
            $('.registration-section').addClass('position-relative');
        } else {
            $('.registration-section').removeClass('position-relative');
        }

        if (doc_Height <= ver_height) {
            $('.verify-section').addClass('position-relative');
        } else {
            $('.verify-section').removeClass('position-relative');
        }

        if (doc_Height <= log_height) {
            $('.login-page .login-section').addClass('position-relative');
        } else {
            $('.login-page .login-section').removeClass('position-relative');
        }

        if (doc_Height <= b2blog_height) {
            $('.b2b_login_page .b2b-login-section').addClass('position-relative');
        } else {
            $('.b2b_login_page .b2b-login-section').removeClass('position-relative');
        }

    }

    function menuMobile() {
        var $check_w = $(window).width();
        if ($check_w <= 992) {
            $('.navigation .wrap-menu').addClass('nb_mobile');
        }
    }

    $(window).on('load', function () {
        /* Equalheight */
        equalheight();
        footeradj();
        changedPosition();
        menuClose();
        //  jbResizeSlider();
        // inputcheck();

        if($.fn.mCustomScrollbar) {
            $(".customScroll, .sidebar .filter-options-content .items").mCustomScrollbar({
                theme: "inset-dark",
                scrollButtons: {enable:true}
            });
        }
    });


    $(window).on('resize', function () {
        equalheight();
        footeradj();
        changedPosition();
        menuClose();
        //inputcheck();
        // jbResizeSlider();
    });
    function getCurrentScroll() {
        return window.pageYOffset || document.documentElement.scrollTop;
    }
});

require(['jquery', 'nouislider'], function ($, noUiSlider) {
    $(document).ready(function () {
        if ($('#input-select').length) {
            var select = document.getElementById('input-select');

            // Append the option elements
            for (var i = 0; i <= 500; i++) {

                var option = document.createElement("option");
                option.text = i;
                option.value = i;

                select.appendChild(option);
            }
        }
        if ($('#html5').length) {
            var html5Slider = document.getElementById('html5');

            noUiSlider.create(html5Slider, {
                start: [0, 500],
                connect: true,
                range: {
                    'min': 0,
                    'max': 500
                }
            });
        }

        if ($('#input-number').length) {
            var inputNumber = document.getElementById('input-number');

            html5Slider.noUiSlider.on('update', function (values, handle) {

                var value = values[handle];

                if (handle) {
                    inputNumber.value = value;
                } else {
                    select.value = Math.round(value);
                }
            });

            select.addEventListener('change', function () {
                html5Slider.noUiSlider.set([this.value, null]);
            });

            inputNumber.addEventListener('change', function () {
                html5Slider.noUiSlider.set([null, this.value]);
            });
        }
    });
});

require([
    'jquery',
    'slick'
], function ($) {
    $(document).ready(function () {
        $('.offerzone-slider').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            infinite: false,
            autoplay: false,
            slidesToShow: 5,
            slidesToScroll: 5,
            prevArrow: '<div class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>',
            nextArrow: '<div class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>',
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 360,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
        $('.product-row.offer-slider').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            infinite: false,
            autoplay: true,
            slidesToShow: 5,
            slidesToScroll: 1,
            prevArrow: '<div class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>',
            nextArrow: '<div class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>',
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
        $('.product-row').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            infinite: false,
            autoplay: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            prevArrow: '<div class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>',
            nextArrow: '<div class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>',
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1100,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 601,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        /** Banner Slider **/
        $('.banner-slider').not('.slick-initialized').slick({
            dots: true,
            arrows: false,
            infinite: false,
            autoplay: true,
            slidesToShow: 1,
            adaptiveHeight: false,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        dots: false
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        $('.swiper').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            infinite: true,
            autoplay: true,
            slidesToShow: 1,
            adaptiveHeight: false,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        dots: false,
                        arrows: true,
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
        /*Brand Logo*/
        $('.brand').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            infinite: true,
            autoplay: true,
            slidesToShow: 7,
            prevArrow: '<div class="slick-prev"><i class="fa fa-caret-left" aria-hidden="true"></i></div>',
            nextArrow: '<div class="slick-next"><i class="fa fa-caret-right" aria-hidden="true"></i></div>',
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1100,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 376,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    //  $('.main-slider').not('.slick-initialized').slick({
    //      slidesToShow: 1,
    //      slidesToScroll: 1,
    //      arrows: true,
    //      prevArrow: '<div class="slick-prev"><span class="icon-left-arrow"></span></div>',
    //      nextArrow: '<div class="slick-next"><span class="icon-right-arrow-1"></span></div>',
    //      autoplay: true,
    //      fade: true,
    //      asNavFor: '.slider-nav',
    //      responsive: [
    //          {
    //              breakpoint: 640,
    //              settings: {
    //                  arrows: false,
    //                  vertical: false,
    //                  slidesToShow: 1,
    //                  slidesToScroll: 1
    //              }
    //          }
    //      ]
    //  });
        $('.slider-nav').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '<div class="slick-prev"><i class="fa fa-angle-left"></i></div>',
            nextArrow: '<div class="slick-next"><i class="fa fa-angle-right"></i></div>',
            //asNavFor: '.main-slider',
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            infinite: true,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        arrows: true
                    }
                }
            ]
        });
        $('.similar-products-slider').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            infinite: false,
            autoplay: true,
            slidesToShow: 5,
            slidesToScroll: 1,
            prevArrow: '<div class="slick-prev"><span class="icon-left-arrow"></span></div>',
            nextArrow: '<div class="slick-next"><span class="icon-right-arrow-1"></span></div>',
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        $('.similar-products-bought-slider').not('.slick-initialized').slick({
            dots: false,
            arrows: true,
            infinite: false,
            autoplay: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            prevArrow: '<div class="slick-prev"><span class="icon-left-arrow"></span></div>',
            nextArrow: '<div class="slick-next"><span class="icon-right-arrow-1"></span></div>',
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        setTimeout(function () {
            $('.recent-viewed-products .product-items').slick({
                dots: false,
                arrows: true,
                infinite: true,
                autoplay: true,
                slidesToShow: 6,
                slidesToScroll: 1,
                slide: 'li',
                prevArrow: '<div class="slick-prev"><span class="icon-left-arrow"></span></div>',
                nextArrow: '<div class="slick-next"><span class="icon-right-arrow-1"></span></div>',
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });

        }, 3000);
    });
});
require([
    'jquery',
    'jquery/validate',
    'slimscroll',
    'fancybox',
    'elevateZoom',
    'dataTables',
    'responsivejquerydatatables'

], function ($, slimscroll, fancybox, elevateZoom, DataTable, responsivejquerydatatables) {

    $(document).ready(function () {
        $('#my-orders-table').DataTable({
            responsive: true
        });
        $('.my-reviews-table, #multiship-addresses-table').DataTable({
            responsive: true
        });
        $('#testwish').DataTable({
            responsive: true,
            "columnDefs": [
                {
                    "targets": [3],
                    "visible": false
                }
            ]
        });

        function scrolbarHeight() {
            //      var heading_border_height = $(".arrivals_product .heading_border").height();
            $(".product_equalheight_wrapper").each(function () {
                // debugger
                var featured_product_height = $(this).find(".product-row").height();
                //var arrivals_sec_height = $(".arrivals-sec").height();
                $(this).find(".arrivals-sec").height(featured_product_height);
                $('.arrivals-sec', $(this)).slimScroll({
                    height: featured_product_height
                });

            });

            /*$('.slim-scroll').slimScroll({
                    height: '100%'
                });*/
        }


        $("#age,#experience").on("keypress keyup", function() {
                if ($(this).val() == '0') {
                    $(this).val('');
                }
            });
            $('ul.tab-head li a').click(function() {
                $(this).parent("li").addClass("active").siblings("li").removeClass("active");
                $('html, body').animate({
                    scrollTop: $($(this).attr('href')).offset().top
                }, 500);
                return false;
            });


            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            if ($("form[name='feedbackfrm']").length) {
                $("form[name='feedbackfrm']").validate({
                    // Specify validation rules
                    rules: {
                        // The key name on the left side is the name attribute
                        // of an input field. Validation rules are defined
                        // on the right side
                        type: "required",
                        name: "required",
                        description: "required",
                        phoneno: {
                            required: true,
                            number: true
                        },
                        email: {
                            required: true,
                            // Specify that email should be validated
                            // by the built-in "email" rule
                            email: true
                        },
                        mobileno: {
                            number: true
                        }
                    },
                    // Specify validation error messages
                    messages: {
                        type: "Please select type",
                        name: "Please enter your name",
                        description: "Please enter description",
                        phoneno: {
                            required: "Please enter phone number",
                            number: "Please enter numbers only"
                        },
                        email: "Please enter a valid email address",
                        mobileno: {
                            number: "Pleae enter numbers only"
                        }
                    },
                    errorPlacement: function(error, element) {
                        if (element.attr("name") == "type") {
                            error.appendTo('#error_type');
                        } else if (element.attr("name") == "name") {
                            error.appendTo('#error_name');
                        } else if (element.attr("name") == "description") {
                            error.appendTo('#error_description');
                        } else if (element.attr("name") == "email") {
                            error.appendTo('#error_email');
                        } else if (element.attr("name") == "phoneno") {
                            error.appendTo('#error_phoneno');
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    // Make sure the form is submitted to the destination defined
                    // in the "action" attribute of the form when valid
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            }
            /*
            if ($("form[name='careerfrm']").length) {
                $("form[name='careerfrm']").validate({
                    // Specify validation rules
                    rules: {
                        candidate_name: "required",
                        mobileno: {
                            required: true,
                            number: true
                        },
                        cand_email: {
                            required: true,
                            email: true
                        },
                        experience: {
                            number: true
                        },
                        age: {
                            number: true
                        },
                        resume: {
                            required: true
                        }
                    },
                    // Specify validation error messages
                    messages: {
                        candidate_name: "Please enter your name",
                        mobileno: {
                            required: "Please enter mobile number",
                            number: "Please enter numbers only"
                        },
                        cand_email: "Please enter a valid email address",
                        experience: {
                            number: "Please enter number "
                        },
                        age: {
                            number: "Please enter number "
                        },
                        resume: {
                            required: "Please upload resume "
                        }
                    },
                    errorPlacement: function (error, element) {
                        if (element.attr("name") == "candidate_name") {
                            error.appendTo('#error_candname');
                        } else if (element.attr("name") == "mobileno") {
                            error.appendTo('#error_candmob');
                        } else if (element.attr("name") == "cand_email") {
                            error.appendTo('#error_candemail');
                        } else if (element.attr("name") == "cand_email") {
                            error.appendTo('#error_candemail');
                        } else if (element.attr("name") == "experience") {
                            error.appendTo('#error_candexp');
                        } else if (element.attr("name") == "age") {
                            error.appendTo('#error_candage');
                        } else if (element.attr("name") == "resume") {
                            error.appendTo('#error_resume');
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    // Make sure the form is submitted to the destination defined
                    // in the "action" attribute of the form when valid
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
            }
            */
        $(window).on('load', function () {
            scrolbarHeight();
        });
        $(window).on('resize', function () {
            scrolbarHeight();
        });

        var enqModal = document.getElementById('enquiryModal');

        $('.btn-enq').click(function () {
            if ($('body').hasClass('catalog-product-view')) {
                var getID = 'main-content';
            } else {
                var getID = $(this).attr('id');
            }
            var lt = $('.' + getID).find('.product-title a').length;
            if (lt) {
                var pName = $('.' + getID).find('.product-title a').html();
            } else {
                var pName = $('.' + getID).find('.product-title').html();
            }
            pName = $.trim(pName);
            var pSku = $('.' + getID).find('.prosku').html();
            pSku = $.trim(pSku);
            $('#enquiryModal').find('#product_sku').val(pSku);
            $('#enquiryModal').find('#product_name').val(pName);
            /* $('#enquiryModal').find('#proName').html(pName); */
			if($("body").hasClass("page-product-configurable")) {
                console.log('no change for configurable');
            }else{
                $('#enquiryModal').find('#proName').html(pName);
			}
            $('#enquiryModal').show();
			var isNvidia = $(this).data("value");
			if(isNvidia == 1 && isNvidia !== ""){
				$('#enquiryModal').find('#isnvidia').show();
			}
            var isDGX = $(this).data("isdgx");
            if(isDGX == 1 && isDGX !== ""){
                $('#enquiryModal').find('#company_name').show();
            }
            console.log('Nvidia====='+isNvidia);
            console.log('isDGX====='+isDGX);
        });
        $('#enquiryModal .close').click(function () {
			$('#enquiryModal').find('#isnvidia').hide();
			$('#enquiryModal').find('#company_name').hide();
            $('#sendEnquiryForm')[0].reset();
            $('#enquiryModal').find('#product_sku').val('');
            $('#enquiryModal').find('#product_name').val('');
            $('#enquiryModal').find('#proName').html('');
            $('#enquiryModal').hide();
        });

        $(document).on('click', '.closeMsg', function () {
            $(this).parent().parent('.txtMsg').remove();
        });

        window.onclick = function (event) {
            if (event.target == enqModal) {
				$('#enquiryModal').find('#isnvidia').hide();
				$('#enquiryModal').find('#company_name').hide();
                $('#sendEnquiryForm')[0].reset();
                $('#enquiryModal').find('#product_sku').val('');
                $('#enquiryModal').find('#product_name').val('');
                $('#enquiryModal').find('#proName').html('');
                enqModal.style.display = "none";
            }
        }
        $('#sendEnquiryForm').on('submit', function (e) {
            e.preventDefault();
            $('.preloader').show();
            var baseUrl = $('#enquiryModal').find('#baseUrl').val();
            $.ajax({
                type: 'post',
                url: baseUrl + 'mysmsmodule/index/enquiry',
                data: $('form#sendEnquiryForm').serialize(),
                success: function (res) {
                    if (res.message == 'Success') {
                        $('#sendEnquiryForm').find('.success').fadeIn('slow').delay(3000).fadeOut('slow', function () {
                            $('#sendEnquiryForm')[0].reset();
                            $('#enquiryModal').find('#product_sku').val('');
                            $('#enquiryModal').find('#product_name').val('');
                            $('#enquiryModal').find('#proName').html('');
                            $('#enquiryModal').hide();
                        });
                    } else if (res.message == 'Fail') {
                        $('#sendEnquiryForm').find('.error').fadeIn('slow').delay(3000).fadeOut('slow', function () {
                            $('#sendEnquiryForm')[0].reset();
                            $('#enquiryModal').find('#product_sku').val('');
                            $('#enquiryModal').find('#product_name').val('');
                            $('#enquiryModal').find('#proName').html('');
                            $('#enquiryModal').hide();
                        });
                    } else {
                        $('#sendEnquiryForm').find('.error').fadeIn('slow').delay(3000).fadeOut('slow', function () {
                            $('#sendEnquiryForm')[0].reset();
                            $('#enquiryModal').find('#product_sku').val('');
                            $('#enquiryModal').find('#product_name').val('');
                            $('#enquiryModal').find('#proName').html('');
                            $('#enquiryModal').hide();
                        });
                    }
                    $('.preloader').hide();
                }
            });

        });

        //initiate the plugin and pass the id of the div containing gallery images
        $('.fancybox').fancybox({
            buttons: [
                "thumbs",
                "close"
            ],
        });

        $("#zoom_03").elevateZoom({
            responsive: true,
            gallery: 'gallery_01',
            cursor: 'pointer',
            galleryActiveClass: "active",
            imageCrossfade: true
        });

        //dell-dummy page
        $('body').on('submit', '#downloadModal', function (e) {
            e.preventDefault();
            var name = $('#fullName').val();
            var email = $('#email').val();
            var formKey = '<?= $block->escapeJs($formKey) ?>';
            if (name && email) {
                $.ajax({
                    url: '/rptechlead/lead/SaveBrochureLead',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        name: name,
                        email: email,
                        form_key: formKey
                    },
                    //showLoader: true,
                    success: function (response) {
                        if (response.success) {
                            const link = document.createElement("a");
                            link.href = "https://rptechindia.com/media/fileupload/Dell_CSG_Catalog_FY27Q1-India_1.pdf";
                            link.download = "GB10-Brochure.pdf";
                            link.click();

                            alert("Thank you " + name + "! Your brochure is downloading.");
                            closeModal();
                            $('#downloadForm')[0].reset();
                        } else {
                            alert("Backend Error: " + response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error Status: " + status);
                        console.error("AJAX Error Thrown: " + error);
                        alert("An error occurred while saving your details. Please check the console logs.");
                    }
                });
            }
        });
    });
});
