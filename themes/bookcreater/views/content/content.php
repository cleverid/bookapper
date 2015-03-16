<h3><?=Yii::t('main', 'Content');?></h3>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'label' => 'Добавить',
	'type' => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
	'icon' => 'plus white',
	'htmlOptions' => array(
		'href' => $this->createUrl('content/update'),
	),
));
?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'articleTable',
    'type' => 'striped bordered condensed',
    'dataProvider' => $mArticle->search(),
    'template' => "{items}",
    'columns' => array(
        array(
			'name' => 'id',
			'htmlOptions'=>array('style'=>'width: 50px'),
		),
        array(
            'class' => 'editable.EditableColumn',
            'name' => 'position',
            'headerHtmlOptions' => array('style' => 'width: 50px'),
            'editable' => array(//editable section
                //'apply'      => '$data->user_status != 4', //can't edit deleted users
                'url' => $this->createUrl('content/updaterow', array()),
                'placement' => 'right',
                'success' => 'js: function(response, newValue) {
                    $("#articleTable").yiiGridView("update");
					if(!response.success) { return response.msg; }
				}',
                'options' => array(
                    'ajaxOptions' => array('dataType' => 'json')
                )
            )
        ),
        array(
			'name' => 'image_id',
			'type' => 'image',
			'value' => '!empty($data->image)?$data->image->getUrl():""',
			'htmlOptions'=>array('style'=>'width: 80px'),
		),
        array(
			'name'=>'t.lang.title',
			'header'=>Yii::t('main', 'Title'),
			'value' => '$data->article_lang[0]->title'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>