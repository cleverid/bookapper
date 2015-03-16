<h3><?=Yii::t('main', 'Gallery')?></h3>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'label' => 'Добавить',
	'type' => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
	'icon' => 'plus white',
	'htmlOptions' => array(
		'href' => $this->createUrl('/gallery/update'),
	),
));
?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $mImage->search(),
    'filter' => $mImage,
    'template' => "{items}",
    'columns' => array(
        array(
			'name' => 'id',
			'htmlOptions'=>array('style'=>'width: 50px'),
		),
        array(
			'name' => 'file',
			'type' => 'image',
			'value' => '$data->getUrl()',
			'htmlOptions' => array('width' => 150),
		),
        array(
			'name' => 'file',
		),
        array(
			'name' => 'name',
			'type' => 'raw',
			'value' => function($data) {
				$name = $data->getLangPart()->name;
				if(!$data->getLangPart()->isActiveLang()) {
					return CHtml::tag('div', array(
						'class' => 'alert alert-warning',
						'style' => 'margin: 0; padding: 2px;',
					), $name);
				}
				
				return $name;
			}
		),
        array(
			'name' => 'image_type_id',
			'value' => '$data->type->name',
			'filter' => CHtml::listData(ImageType::getAll(), 'id', 'name'),
			'htmlOptions' => array('width' => 80),
		),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>