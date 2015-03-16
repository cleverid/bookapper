<html>
<head>
	<meta charset="utf-8" />
	<title>Домашний доктор. Руководство.</title>
	<link href="<?php echo URL::base();?>design/css/creator.css" rel="stylesheet" type="text/css" />
	
	<script src="/design/js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="/design/js/main.js" type="text/javascript"></script>
</head>
<body>
<div class="gallery">
	<form action="<?=URL::site('gallery/?id=')?>
								<?= isset($showImage) ? $showImage[0]['id']:'';?>" method="POST" enctype="multipart/form-data">
		<input id="addImage" type="button" value="+добавить" />
		<?if(isset($showImage)):?>
			<input id="dellImage" type="button" value="удалить" />
			<input type="submit" value="сохранить"/>
			<br />
			<span class="form-title">id:</span>
			<span><?= $showImage[0]['id']?></span>
			<br />
			<span class="form-title">title:</span>
			<input type="text" name="title" value="<?=$showImage[0]['title']?>"/>
			<br />
			<span class="form-title">image:</span>
			<span class="image-name"><?= $showImage[0]['image']?></span>
			<input type="file" name="image"/>
		<?endif;?>
	</form>
	<div class="list-images">
		<?foreach($listImages as $image):?>
			<? 
				$selected = '';
				$nonimage = '';
				if ($image['image'] == NULL) $nonimage = 'nonimage';
				if(isset($showImage)){
					if ($showImage[0]['id'] == $image['id']) $selected = 'selected';
				}
			?>
			<a data-image-id="<?= $image['id']?>" href="<?= URL::site('gallery/?id='.$image['id'])?>" class="image-block <?=$selected?> <?=$nonimage?>">
				<div class="image-container">
					<?if ($nonimage == ''):?>	
						<img src="/imagefly/<?=$image['id']?>/100/100"/>
					<?endif;?>
				</div>
				<div class="title"><?=$image['title']?></div>
			</a>
		<?endforeach;?>
	</div>
</div>
</body>
</html>