<?
$form = $this->beginWidget("bootstrap.widgets.TbActiveForm", array(
	'id' => 'image-form',
	'enableAjaxValidation' => false,
	'enableClientValidation' => false,
		));
?>

<?= $form->errorSummary(array($mImage)); ?>


<?= $form->dropDownListRow($mImage, 'image_type_id', CHtml::listData(ImageType::getAll(), 'id', 'name'), array('class' => 'span2')); ?>
<?if(!empty($mImage->file)):?>
	<div class="gallery-preview">
		<img src="<?=$mImage->getUrl()?>">
	</div>
<?endif;?>
<?= $form->textFieldRow($mImage, 'file', array('class' => 'span6')); ?>
<?= $form->textFieldRow($mImage->getLangPart(), 'file_lang', array('class' => 'span6')); ?>
<div class="control-group <?=$mImage->getLangPart()->isActiveLang()?'':'warning'?>">
	<?= $form->textFieldRow($mImage->getLangPart(), 'name', array('class' => 'span6 warning')); ?>
</div>
<?= CHtml::submitButton('Сохранить', array('class' => 'btn btn-success')); ?> 
<? $this->endWidget(); ?>