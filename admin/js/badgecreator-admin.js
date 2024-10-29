(function( $ ) {
	'use strict';
	$(document).ready(function(){
		window.send_to_editor_default = window.send_to_editor;
		$('#set-badge-image').click(function(){
		  // replace the default send_to_editor handler function with our own
		  window.send_to_editor = window.attach_image;
		  var postid = $('#post_id').val(); 
		  tb_show('', 'media-upload.php?post_id='+postid+'&amp;type=image&amp;TB_iframe=true');
		  return false;
		});

		window.attach_image = function(html) {
			console.log(html);

			$('body').append('<div id="temp_image">' + html + '</div>');

			var img = $('#temp_image').find('img');

			var imgurl   = img.attr('src');
			var imgclass = img.attr('class');
			var imgid    = parseInt( imgclass.replace(/\D+/g, "")  );

		  $('#upload_image_id').val(imgid);
		  $('#remove-badge-image').show();

		  $('img#badge_image').attr('src', imgurl);

		  try{tb_remove();}catch(e){};

		  $('#temp_image').remove();

		  window.send_to_editor = window.send_to_editor_default;
		}

		$('#remove-badge-image').click(function() {
		  $('#upload_image_id').val('');
		  $('img').attr('src', '');
		  $(this).hide();
		  return false;
		});

	});

})( jQuery );
