<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<link rel="stylesheet" type="text/css" 
		  href="<?= Yii::app()->theme->baseUrl; ?>/css/bookcreator.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
	<div class="menu">
		<a href="/">Вывод</a>
		<a href="/site/content">Контент</a>
		<a href="/site/gallery">Галерея</a>
	</div>
	<?$prefix = 'ololo'?>
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
		'brand'=> Yii::app()->name,
		'fluid' => true,
		'collapse'=>true, // requires bootstrap-responsive.css
		'items'=>array(
			array(
				'class'=>'bootstrap.widgets.TbMenu',
				'items'=>array(
					array('label'=>Yii::t('main', 'Content'), 'url'=>'/content', 'active' => Yii::app()->controller->id == 'content'),
					array('label'=>Yii::t('main', 'Gallery'), 'url'=>'/gallery', 'active' => Yii::app()->controller->id == 'gallery'),
					array('label'=>Yii::t('main', 'Export'), 'url'=>'/export', 'active' => Yii::app()->controller->id == 'export'),
				),
			),
			$this->renderPartial('//layouts/lang_dropdown', array(
				'langs' => Lang::getAll(),
				'langActive' => Lang::getActive(),
			), true),
		),
	)); ?>
	<br>
	<br>
	<?php echo $content; ?>
</body>
</html>
