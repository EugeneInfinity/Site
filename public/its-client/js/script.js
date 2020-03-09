(function ($) {
    'use strict'
    
    $(document).ready(function () {

        var HomeAdvantages = (".swiper-container-advantages")
        function initSwiper() {
            var screenWidth = $(window).width();
            if(screenWidth < 1181 && HomeAdvantages == (".swiper-container-advantages")) {            
                HomeAdvantages = new Swiper ('.swiper-container-advantages', {
                    slidesPerView: 'auto',
                    navigation: {
                        nextEl: '.swiper-button-next-advantages',
                        prevEl: '.swiper-button-prev-advantages',
                    }
                });
            } else if (screenWidth > 1181 && HomeAdvantages != (".swiper-container-advantages")) {
                HomeAdvantages.destroy();
                HomeAdvantages = (".swiper-container-advantages");
                jQuery('.swiper-wrapper').removeAttr('style');
                jQuery('.swiper-slide').removeAttr('style');            
            }        
        }
        
        initSwiper();

        $('#basket-location').filterUl({
            ulSelector: '.location-list ul',
            inputSelector: '#location-input'
        })

        svg4everybody({})
        /* Javascript */

        $('.rating > img').on('click', function() {
            var value = $(this).attr('value'),
            path = $(this).parent('.rating').data('path') || 'img/';
            $(this).prevAll().attr('src', path + 'big-star-active.png');
            $(this).attr('src', path + 'big-star-active.png');
            $('.rating input').val(value)
            $(this).nextAll().attr("src", path + "big-star.png");
          });

        $('.phone1').mask("+7 (___) ___-__-__", {
            translation: {
              '_': {
                pattern: /[0-9]/,
                fallback: ''
              }
            },
          placeholder: "+7 (___) __-__-__",
          selectOnFocus: true
        });

        $('#select2-filter').select2({
            templateResult: function (state) {
                var filterIcon, $state;
                filterIcon = $(state.element).data("img");
                if (!state.id) { return state.text; }
                $state = $('<span class="' + filterIcon + '" />' + state.text + '</span>');
    
                return $state;
            },
            dropdownCssClass: 'select-two',
            minimumResultsForSearch: -1
        });
    
        $("#select2-filter").on("change", function() {
            
            var $data, $text, $template, $img;
            
            $data = $(".product-right__head").find(".select2-selection__rendered");
            $text = $data.text();
            $img = $("#select2-filter option:selected").data("img");
            $template = "<span class='" + $img + "'>" + $text + "</span>";
            $data.html($template);
        })

        // $('html').css('height', '100%')
        // $('.footer').css('position', 'absolute').css('width', '100%').css('bottom', '-370px')
        // $('body').css('position', 'relative').css('min-height', '100%')

        // $('.home-content__head-like').on('click', function(e){
        //     e.preventDefault()
        //     $(this).toggleClass('active')
        // })

        // $('.card-product__head-left svg').on('click', function(e){
        //     e.preventDefault()
        //     $(this).toggleClass('active')
        // })

        
       

        // $('.home-content__head-mobile button').on('click', function(e){
        //     e.preventDefault()
        //     $(this).toggleClass('active')
        //     $('.swiper-slide-inner').slideToggle(300)
        // })

        $('.footer-mobile button').on('click', function(e){
            e.preventDefault()
            $(this).toggleClass('active')
            $('.footer__block_second').slideToggle(300)
            $('.footer__block_last ul').slideToggle(300)
            $('.footer').toggleClass('big')
        })

        $('.header__mobile-menu').on('click', function(e){
            e.preventDefault()
            $(this).toggleClass('active')
            $('.header__wrapper').toggleClass('show')
        })

        $('.home-content__head-mobile button').on('click', function (e) {
            e.preventDefault()
            $('.home-content__head-mobile button span').toggle()
        })

        $('.menu').on('click', '.menu-btn', function (e) {
            e.preventDefault()
            $(this).parent().children('.mobile-menu').slideToggle(300);
        })


        $('.list__item').on('click', function () {
            $(this).find('.hide').fadeToggle(200);
            $(this).find('.row-link__arrow').toggleClass('active');
        })


        $('.favorites-info').on('click', '.close', function () {
            $(this).parent().parent().fadeOut(200);
        })

        $('.menu').on('click', '.menu-btn-1', function (e) {
            e.preventDefault()
            $(this).parent().children('.mobile-menu-1').slideToggle(300);
        })

        $('.btn-gen_log-in').on('click', function(){
            $("html, body").animate({ scrollTop: 0 }, "slow");
        })

        $(document).on("click", "[data-btn]", function() {
            // $(".header__interface-item").removeClass("active");
            // $(this).addClass("active");
            var id = ".header-" + $(this).data("btn");
            $(".header-tabs").slideUp(300); 
            $(id).slideDown(300); 
        })
        

       


        $(document).on('click', function (e){ 
            var div = $(".header__interface-items"); 
            if (!div.is(e.target) && div.has(e.target).length === 0) { 
                $(".header-tabs").slideUp(1); 
                // $(".header__interface-item").removeClass("active");
                $('.footer').removeClass('menu')
            }
        })

        $(document).on('click', function (e){ 
            var div = $(".btn-gen_log-in"); 
            if (!div.is(e.target) && div.has(e.target).length === 0) { 
                $(".header-user").removeClass('log-in'); 
                $(".header__interface-items .user").removeClass('active-1'); 
            }
        })
        $(document).on('click', function (e){ 
            var div = $(".header"); 
            if (!div.is(e.target) && div.has(e.target).length === 0) { 
                $(".header__wrapper").removeClass('show'); 
                $(".header__mobile-menu").removeClass('active'); 
            }
        })

        $('.search').on('click', function(){
            $('.header__gray-block').slideDown()
            $('.header__interface-items').addClass('posit')
            $('.footer').addClass('menu')

        })

        $('.header__gray-block').on('click', function(){
            
            $('.search').removeClass('active')
            $('.header__interface-items').removeClass('posit')
            $(this).slideUp(100)
        })


        $('.product-filter').on('click', '.product-filter__name', function () {
            $(this).parent().children('.product-filter__block-repeat').slideToggle(300);
            $(this).parent().children('.product-filter__name').toggleClass('active')
        })


        $('.mobile-filter').on('click', function () {
            $('.product-filter__repeat').slideDown(300)
            $('.select2-container--default').addClass('index')
        })

        $('.mobile-filter__close').on('click', function () {
            $('.product-filter__repeat').slideUp(300)
            $('.select2-container--default').removeClass('index')
        })

        $('.card-product__head-button .btn-gen_1').on('click', function (){
            $('.modal-name').fadeIn(500)
            $('footer').addClass('menu-1')
        })

        $('.circle').on('click', function (e){
            e.preventDefault()
            $('.modal-name').fadeOut(500)
            $('footer').removeClass('menu-1')
        })

        $('label').on('click', function (){
            $(this).toggleClass('active')
        })

        $('.btn-gen_log-in').on('click', function (){
            $('.header-user').addClass('log-in')
            $('.header__interface-items .user').addClass('active-1')
        })

        $('.select-personal').select2({
            dropdownCssClass: 'select-dropdown',
            minimumResultsForSearch: -1,
            
        })

       $('#location-input').on('click', function() {
           $('.location-list').toggleClass('slide')
       })

       $(document).on('click', function (e){ 
            var div = $(".location__wrapper"); 
            if (!div.is(e.target) && div.has(e.target).length === 0) { 
                $('.location-list').removeClass('slide')
            }
        })

        $('.select-basket').select2({
            dropdownCssClass: 'select-basket-dropdown',
        });
        

        $('.cookies .button-close').on('click', function(){
            $('.cookies').slideUp(300);
            var date = new Date;
            date.setDate(date.getDate() + 90);
            document.cookie = "window=1; path=/; expires=" + date.toUTCString();
        })

        if(!getCookie("window")) { $('.cookies').slideDown(300); }

        
    })

    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
          "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }


    $('#card-textarea').on("input", function(){
        var maxlength = $(this).attr("maxlength");
        var currentLength = $(this).val().length;
    
        if( currentLength >= maxlength ){
        } else {
            $('#card-number').html(currentLength)
        }
    });

   
    
})(jQuery)

;(function ($) {
    $.fn.filterUl = function(options) {
        var settings = $.extend({
                ulSelector: '.filter-ul-selector',
                inputSelector: '.filter-input-selector'
            }, options)

        return this.each(function() {
                var $base = $(this)

            $(settings.inputSelector).on('keyup insert:text', function () {
                var filter = $base.find(settings.inputSelector).val().toUpperCase().replace(/[^A-Za-zА-Яа-яЁёЪъьЬ0-9]/g, "")
                $base.find(settings.ulSelector).find('li').each(function (i) {
                    if ($(this).text().toUpperCase().replace(/[^A-Za-zА-Яа-яЁёЪъьЬ0-9]/g, "").indexOf(filter) > -1) {
                        $(this).fadeIn(100)
                    } else {
                        $(this).fadeOut(100)
                    }
                })
            }) 
            $base.find(settings.ulSelector).find('li').on('click', function () {
                $base.find(settings.ulSelector).find('li').trigger('set:address')
            })

            $(settings.inputSelector).on('insert:text', function () {
                $base.find(settings.ulSelector).find('li').trigger('set:address')
            })
        })   
    }
})(jQuery)
 ;(function ($) {
     $.fn.extend({
         toggleText: function(a, b){
             return this.text(this.text() === b ? a : b);
         }
     })
 })(jQuery)
 
;(function ($) {
    var HomeHead = new Swiper ('.swiper-container-head', {
        slidesPerView: 'auto',
        loop: true,
        pagination: {
        el: '.swiper-pagination-head',
        clickable: true
        },
        autoplay: true,
        speed: 1000
    })

    var HomeBottom = new Swiper ('.swiper-container-recomend', {
        slidesPerView: 'auto',
        dynamicMainBullets: 3,
        navigation: {
            nextEl: '.swiper-button-next-recomend',
            prevEl: '.swiper-button-prev-recomend',
        },
        // breakpoints: {
        //     1180: {
        //     slidesPerView: 2
        //     }
        // },    
    })

    var HomePresent= new Swiper ('.swiper-container-present', {
        slidesPerView: 'auto',
        navigation: {
            nextEl: '.swiper-button-next-present',
            prevEl: '.swiper-button-prev-present',
        }
    })

    var HomeInsta= new Swiper ('.swiper-container-insta', {
        slidesPerView: 'auto',
        loop: true,
        centeredSlides: true,
        centerInsufficientSlides: true,
        navigation: {
            nextEl: '.swiper-button-next-insta',
            prevEl: '.swiper-button-prev-insta',
        }
    })

    var HomeInstaMobile= new Swiper ('.swiper-container-instamobile', {
        slidesPerView: 'auto',
        loop: true,
        centeredSlides: true,
        centerInsufficientSlides: true,
        navigation: {
            nextEl: '.swiper-button-next-instamobile',
            prevEl: '.swiper-button-prev-instamobile',
        }
    })

    HomeMiddle = new Swiper ('.swiper-container-middle', {
        slidesPerView: 'auto',
        navigation: {
            nextEl: '.swiper-button-next-middle',
            prevEl: '.swiper-button-prev-middle',
        }
    });

    var sliderTraining = (".swiper-container-training")
    function initSwiper() {
        var screenWidth = $(window).width();
        if(screenWidth < 1181 && sliderTraining == (".swiper-container-training")) {            
            sliderTraining = new Swiper ('.swiper-container-training', {
                slidesPerView: 'auto',
                observer: true,
                observeParents: true,
                navigation: {
                    nextEl: '.swiper-button-next-training',
                    prevEl: '.swiper-button-prev-training',
                }
            });
        } else if (screenWidth > 1181 && sliderTraining != (".swiper-container-training")) {
            sliderTraining.destroy();
            sliderTraining = (".swiper-container-training");
            jQuery('.swiper-wrapper').removeAttr('style');
            jQuery('.swiper-slide').removeAttr('style');            
        }        
    }

    initSwiper();

    var HomeBottom = new Swiper ('.swiper-container-recomendation', {
    
        slidesPerView: 'auto',
        dynamicMainBullets: 3,
        // slidesPerView: 3,
        pagination: {
        el: '.swiper-pagination-bottom',
        clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            1180: {
            slidesPerView: 2
            }
        },    
    })
})(jQuery)