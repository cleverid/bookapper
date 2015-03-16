//content
$(document).on('focusout', '[data-var-id]', function(e){
	var contentText = $(this).val();
	var id = $(this).attr('data-var-id');
	
	$.ajax({
		type: 'POST',
		url: '/ajax/setvar',
		data: {content: contentText, var_id: id},
	}).done(function(res){
		if (res.length != 0) alert(res);
	});
});

$(document).on('click', '#addArticle', function(e){
	$.ajax({
		type: 'POST',
		url: '/ajax/addArticle',
	}).done(function(res){
		if (res.length != 0) alert(res);
		//alert('Статья добавлена');
		location.reload();
	});
});

$(document).on('click', '#dellArticle', function(e){
	var id = $('.article.selected').attr('data-article-id');
	if (id) {
		if (window.confirm('Выдействительно хотите удалить статью?')){
			$.ajax({
				type: 'POST',
				url: '/ajax/dellArticle',
				data: {articles_id: id},
			}).done(function(res){
				if (res.length != 0) alert(res);
				//alert('Статья добавлена');
				//var url = window.location.href;
				document.location.href = '/creator/content';
			});
		}
	}else{
		 alert('not selected');
	}
	
});

//gallery
$(document).on('click', '#addImage', function(e){
	$.ajax({
		type: 'POST',
		url: '/gallery/addImage',
	}).done(function(res){
		if (res.length != 0) alert(res);
		//alert('Изображение добавлено');
		location.reload();
	});
});

$(document).on('click', '#dellImage', function(e){
	var id = $('.image-block.selected').attr('data-image-id');
	if (id) {
		if (window.confirm('Выдействительно хотите удалить изображение?')){
			$.ajax({
				type: 'POST',
				url: '/gallery/dellImage',
				data: {image_id: id},
			}).done(function(res){
				if (res.length != 0) alert(res);
				//alert('Статья добавлена');
				//var url = window.location.href;
				document.location.href = '/gallery';
			});
		}
	}else{
		 alert('not selected');
	}
});
