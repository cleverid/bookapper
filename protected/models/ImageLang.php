<?php

/**
 * This is the model class for table "image_lang".
 *
 * The followings are the available columns in table 'image_lang':
 * @property integer $image_id
 * @property integer $lang_id
 * @property string $name
 */
class ImageLang extends CActiveRecord {

	private $_modelMain;

	/**
	 * Активный ли язык или нет
	 * @return boolean
	 */
	public function isActiveLang() {
		return $this->lang_id === Lang::getActive()->id;
	}

	/**
	 * Устанавливает главную модель
	 */
	public function setModelMain(Image &$model) {
		$this->_modelMain = $model;
	}

	/**
	 * Возвращает главную модель
	 * @return Image
	 */
	public function getModelMain() {
		return $this->_modelMain;
	}

	protected function beforeValidate() {
		parent::beforeValidate();

		if (empty($this->image_id) && $this->getModelMain()) {
			$this->image_id = 0; ;
		}

		return true;
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		// если новая модел и надо привязать язык к ней
		if( $this->getModelMain() instanceof Image
			&& $this->isNewRecord
			&& $this->getModelMain()->id !== $this->image_id ) {
				$this->image_id = $this->getModelMain()->id;
		}
		
		// если язык у модели не совпадает с активным, то копируем
		if( $this->getModelMain() instanceof Image
			&& $this->lang_id !== Lang::getActive()->id ) {
			
			$test = ImageLang::model()->findByAttributes(array(
				'image_id' => $this->getModelMain()->id,
				'lang_id' => Lang::getActive()->id,
			));
			if(!$test) {
				// если нет ещё информации о продукте на этом языке
				$lang = new ImageLang;
				$lang->setAttributes($this->attributes, false);
				$lang->id = null;
				$lang->lang_id = Lang::getActive()->id;
				$lang->name = (string)$this->name;
				if(!$lang->save()){
					Service::preView($lang->getErrors());
				}
			}
				
			return false; // чтобы не сохранить модель с други языком
		}
	
		return true;
	}

	// ========================================================================

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ImageLang the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'image_lang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lang_id', 'required'),
			array('lang_id', 'numerical', 'integerOnly' => true),
			array('name, file_lang', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('image_id, lang_id, name', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'lang' => array(self::BELONGS_TO, 'Lang', 'id_lang'),	
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'image_id' => 'Image',
			'lang_id' => 'Lang',
			'name' => 'Name',
			'file_lang' => 'File for lang (override File Name)',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('image_id', $this->image_id);
		$criteria->compare('lang_id', $this->lang_id);
		$criteria->compare('name', $this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

}
