(function ($) {
    'use strict';

    $(document).ready(function () {
        var canvas = new fabric.Canvas('badgeCreator-canvas-editor');
        canvas.on('object:modified', function(){
           var builtfile = canvas.toDataURL("image/png"); 
            $('#badgeCreator-download').attr('href',builtfile );
            $('#badgeCreator-download').attr('download',builtfile );
        });
        
        init_canvas();
        function init_canvas()
        {
            if ($('#badgeCreator-whole-container').length)
            {
              fabric.Image.fromURL(badge_image, function(img) {
                   img.set({width: badgeCreatorwidth, height: badgeCreatorheight, top: 0, left: 0});
                   canvas.setOverlayImage(img, canvas.renderAll.bind(canvas));
               });  
            }
           
        }

        if ($('#custom-upload-form.custom-uploader').length)
        {
            var custom_upload_element = $('#custom-upload-form.custom-uploader .badgeCreator-upload-info');
            
            $('#custom-upload-form.custom-uploader').fileupload({
                dropZone: $('#drop'),
                url: ajax_object.ajax_url,
                add: function (e, data) {
                    var bcf = $('<div class="working"><div class="badgeCreator-info"></div><div class="badgeCreator-progress-bar"><div class="badgeCreator-progress"></div></div></div>');

                    bcf.find('.badgeCreator-info').text(data.files[0].name).append('<i>' + tailleFormat(data.files[0].size) + '</i>');

                    custom_upload_element.html("");
                    data.context = bcf.appendTo(custom_upload_element);
                    bcf.find('input').knob();
                    bcf.find('span').click(function () {

                        if (bcf.hasClass('working')) {
          
                            jqXHR.abort();
                        }

                        bcf.fadeOut(function () {
                            bcf.remove();
                        });

                    });

                    var jqXHR = data.subcit();


                },
                progress: function (e, data) {

                    var progress = parseInt(data.loaded / data.total * 100, 10);

                    data.context.find('.badgeCreator-progress').css("width", progress + "%");

                    if (progress == 100) {
                        data.context.removeClass('working');
                    }
                },
                fail: function (e, data) {
                    data.context.addClass('error');
                },
                done: function (e, data) {
                    upload_picture_callback(data.result, false, false, false);
                }
            });
        }

        function upload_picture_callback(responseText, statusText, xhr, form)
        {
            if (checkJson(responseText))
            {
                var response = $.parseJSON(responseText);

                if (response.success)
                {
                    
                    if (response.img_url)
                        ajouter_image(response.img_url, canvas);
                   
                }
                else{
                    alert(response.message);
                }
                    
            }
            else
            $("#user-custom-design").val("");
        }

        function tailleFormat(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
        
        
        function checkJson(data)
{
    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
            replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
            replace(/(?:^|:|,)(?:\s*\[)+/g, '')))
        return true;
    else
        return false;
}

function ajouter_image(url){
    fabric.Image.fromURL(url, function(oImg) {
        canvas.add(oImg);
        canvas.renderAll();
        var builtfile = canvas.toDataURL("image/png");
        $('#badgeCreator-download').attr('href',builtfile );
        $('#badgeCreator-download').attr('download',builtfile );
      });          
}
    });
})(jQuery);

