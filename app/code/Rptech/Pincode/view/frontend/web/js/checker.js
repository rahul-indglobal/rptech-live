define([
    'jquery'
], function ($) {
    "use strict";
    return function (config, element) {
        $(element).click(function (e) {
            e.preventDefault();
            if($("#pincode_checker").valid()) {
                $("#spanpinloader").show();
                var action = $('#pincode_checker').attr('action');
                $.ajax({
                    url : action,
                    type : 'POST',
                    data: {
                        code: $('#pincode_check').val(),
                    },
                    dataType:'json',
                    success : function(response) { 
                    $("#spanpinloader").hide();             
                        if(response.status){
                            $("#showpincodemsg").text(response.message);
                        }else{
                             $(".errorDiv #errorMsg").text(response.message);
                        }
                    },
                    error : function(request,error)
                    {
                        $("#spanpinloader").hide();
                        alert("Error");
                    }
                });
            }
        });

        $('#pincode_check').focus(function(){
            $('#showpincodemsg, .errorDiv #errorMsg').text('');
        });
    }
});
