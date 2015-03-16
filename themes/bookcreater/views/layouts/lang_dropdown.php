<div class="lang-selector pull-right nav">
	<span class="mlabel"><?=Yii::t('main', 'Language')?>:</span> 
	<form method="POST">
		<?
		$data = CHtml::listData($langs, 'code', 'name');
		echo CHtml::activeDropDownList($langActive, 'code', $data, array(
			'class' => 'dropdown',
			'onChange' => 'js: $(this).closest("form").submit();'
		));
		?>
	</form>
</div>
