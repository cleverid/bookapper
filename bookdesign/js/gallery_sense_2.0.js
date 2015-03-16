(function() {
	//stops floating events
	function stopBubble(oEvent) {
		if(oEvent && oEvent.stopPropagation) {
			oEvent.stopPropagation();
		} else {
			window.event.cancelBubble = true; //для IE
		}
	}
	
	//main function
	window.onload = function() {
		//create a template gallery
		$("body").append(''+
		'<div id = "gallery_view">' +
		'	<table>' +
		'		<tr id = "gallery_place">' +
		'			<td>' +
		'				<img id = "gallery_image">' +
		'				<p id = "gallery_text"></p>' +
		'			</td>' +
		'		</tr>' +
		'	</table>' +
		'</div>');

		var scroll_position = {x: 0, y: 0};								//var scroll position
		var el_web_page = $("#web_page_container");						//acces all page
		
		//set gallery table	
		var el_gallery = $("#gallery_view");							//acces all gallery
		el_gallery.hide();
		var el_img = $("#gallery_image");								//acces to image
		var el_text = $("#gallery_text");								//acces to text
		
		//all gallery enter
		$(".gallery[data-gallery_big_image]").click(function() {
			document.body.bgColor = "#000";
			//save scroll position
			scroll_position.x = window.scrollX;
			scroll_position.y = window.scrollY;
			el_img.attr("src", $(this).attr("data-gallery_big_image"));
			if ($(this).attr("alt") !== undefined && $(this).attr("alt") !== "") {
				el_text.show();
				el_text.html($(this).attr("alt"));
			} else {
				el_text.hide();
				el_text.html("");
			}
			el_web_page.hide();
			el_gallery.show();
		});

		//gallery view out
		el_gallery.click(function () {
			document.body.bgColor = "";
			el_web_page.show();
			window.scroll(scroll_position.x, scroll_position.y); 
			el_gallery.hide();
		});


		//hide header
		el_text.click(function (oEvent, el_text) {
			$(this).hide();
			// предотвращаем дальнейшую передачу события
			stopBubble(oEvent);
		});
	}
})();