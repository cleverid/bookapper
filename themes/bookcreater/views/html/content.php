<!DOCTYPE html>
<html>
<head>
	<title>Редактор контента</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="<?php echo URL::base();?>design/css/creator.css" rel="stylesheet" type="text/css" />
	
	<script src="/design/js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="/design/js/main.js" type="text/javascript"></script>
</head>
<body>
	<!--<? if(isset($test_post)) echo Debug::vars($test_post); ?>-->
	<!--<? if(isset($vars)) echo Debug::vars($vars); ?>-->
	<div class="article-list">
		<div class="add-dell">
			<form>
				<input id="addArticle" type="button" value="+Добавить" />
			</form>
			<form>
				<input id="dellArticle" type="button" value="Удалить" />
			</form>
		</div>
		<div class="title">Список статей:</div>
		<div class="content">
			<? foreach ($articles as $article): ?>
				<? if (isset($show_article)) {
					$selected = '';
					if ($show_article[0]['id'] == $article['id']) $selected = 'selected';
				}
				?>
				<a data-article-id="<?=$article['id']?>" class='article <?=$selected?>'
					href="<?= URL::site('creator/content').'/'. $article['id']; ?>">
						<div class="position"><?=$article['position']?></div>
						<?if ($article['image_id'] != NULL):?>
							<div class="image-place">
							<img src="/imagefly/<?=$article['image_id']?>/32/32"/>
							</div>
						<?endif;?>
						<div class="name"><?=$article['title']?></div>
				</a>
			<? endforeach; ?>
		</div>
		<? if (isset($show_article)): ?>
			<form method="POST">
				<span>Позиция:</span><input type="number" name="position" value="<?=$show_article[0]['position']?>"/><br>
				<span>Название:</span><input type="text" name="title" value="<?=$show_article[0]['title']?>"/><br>
				<span>Картинка:</span>
				<select name="gallery-image" size="1">
					<option value="NULL" selected="selected"></option>
					<?if (isset($gallery)):?>
					<? foreach ($gallery as $image):?>
						<option value="<?=$image['id']?>"> <?=$image['title']?> </option>
					<? endforeach; ?>
					<?endif;?>
				</select>
				<input type="submit" value="сохранить"/>
			</form>
			
			<hr>
			<div class="vars">
				<?foreach($vars as $var):?>
					<div class="title"><?=$var['name']?>:</div>
					<textarea class="var-input" data-var-id="<?=$var['id']?>"><?=$var['content']?></textarea>
				<?endforeach;?>
			</div>
		<?endif;?>
	</div>
</body>
</html>