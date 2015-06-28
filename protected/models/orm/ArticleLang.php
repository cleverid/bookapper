<?php

/**
 * This is the model class for table "articles_lang".
 *
 * The followings are the available columns in table 'articles_lang':
 * @property integer $id
 * @property integer $article_id
 * @property integer $lang_id
 * @property string $title
 * @property string $necessary
 * @property string $possible
 * @property string $must_not
 * @property string $important
 * @property string $text
 */
class ArticleLang extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ArticlesLang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'article_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('article_id, lang_id, title', 'required'),
			array('article_id, lang_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, article_id, lang_id, title', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'article_id' => Yii::t("main", 'Article'),
			'lang_id' => Yii::t("main", 'Lang'),
			'title' => Yii::t("main", 'Title'),
			'text' => Yii::t("main", 'Text'),
			'necessary' => Yii::t("main", 'Necessary'),
			'possible' => Yii::t("main", 'Possible'),
			'must_not' => Yii::t("main", 'Must not'),
			'important' => Yii::t("main", "it's important"),
			'active' => Yii::t("main", "Active"),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('article_id',$this->article_id);
		$criteria->compare('lang_id',$this->lang_id);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}