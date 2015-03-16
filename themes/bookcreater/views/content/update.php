<div class="row-fluid">
	<div class="span8">
		<?$form = $this->beginWidget("bootstrap.widgets.TbActiveForm", array(
			'id'=>'article-form',
			'enableAjaxValidation'=>false,
			'enableClientValidation'=>false,
		));?>

			<?= $form->errorSummary(array($mArticle, $mArticleLang)); ?>

			<?= $form->textFieldRow($mArticleLang, 'title', array('class' => 'span6'));?>
			<?= $form->textFieldRow($mArticle, 'position', array('class' => 'span1'));?>
			<?if(!empty($mArticle->image)):?>
				<div>
					<img src="<?=$mArticle->image->getUrl()?>">
				</div>
			<?endif;?>
			<?= $form->dropDownListRow($mArticle, 'image_id', array('' => "-Изображение-") + CHtml::listData(Image::getForMenu(), 'id', function ($data) {
				return $data->getLangPart()->name;
			}), array('class' => 'span4')); ?>
			<?= $form->textAreaRow($mArticleLang, 'necessary', array('class' => 'span12', 'rows' => 10));?>
			<?= $form->textAreaRow($mArticleLang, 'possible', array('class' => 'span12', 'rows' => 10));?>
			<?= $form->textAreaRow($mArticleLang, 'must_not', array('class' => 'span12', 'rows' => 10));?>
			<?= $form->textAreaRow($mArticleLang, 'important', array('class' => 'span12', 'rows' => 10));?>
			<?= $form->textAreaRow($mArticleLang, 'text', array('class' => 'span12', 'rows' => 10));?>
			<?= $form->textFieldRow($mArticle, 'code', array('class' => 'span2'));?>
			<?= $form->checkBoxRow($mArticleLang, 'active', array());?>
			<br />
			<?= CHtml::submitButton('Сохранить', array('class' => 'btn btn-success')); ?> 
		<?$this->endWidget();?>
	</div>
</div>
<div>

Выделение жирным
<xmp><b>жирный текст</b></xmp>
	
Галерея
<xmp><div class="gallery-items">
    <img data-image-id="5" class="gallery-item"/>
</div></xmp>
	
Ссылка на другую страницу
<xmp><span data-link-id="5">
    <span data-link-position></span>
    <span data-link-name></span>
</span></xmp>

</div>