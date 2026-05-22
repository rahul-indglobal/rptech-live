/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery'
], function ($) {
    var windowScrollToTop;
    $(window).scroll(function(){
    clearTimeout(windowScrollToTop);
    windowScrollToTop = setTimeout(function(){
        if($(this).scrollTop() > 100){
            $('#um-stotop').fadeIn();
        } else {
            $('#um-stotop').fadeOut();
        }
    }, 500);        
    });
    $('#um-stotop').click(function(){
    $('html, body').animate({scrollTop: 0}, 600);
    return false;
    }); 	
});
