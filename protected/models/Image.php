<?php

/**
 * This is the model class for table "image".
 *
 * The followings are the available columns in table 'image':
 * @property integer $id
 * @property string  $file
 * @property integer $image_type_id
 */
class Image extends CActiveRecord {

	private static $_cAll;
	private $_cLangPart;

    public function defaultScope() {
        return CMap::mergeArray(parent::defaultScope(), array(
            'order' => 'id DESC'
        ));
    }

    /**
	 * Возвращает все ихображения
	 * @return Image[]
	 */
	public static function getAll() {
		if(!empty(self::$_cAll))
			return self::$_cAll;
		
		$crit = new CDbCriteria;
		$crit->order = 't.id DESC';
		self::$_cAll = self::model()->findAll($crit);
		
		return self::$_cAll;
	}
	
	
	/**
	 * Возвращает изображение с типом меню
	 * @return Image[]
	 */
	public static function getForMenu() {
		$result = array();
		$images = self::getAll();
		
		foreach($images as $img) {
			if($img->image_type_id == 1)
				$result[] = $img;
		}
		
		return $result;
	}


	/**
	 * Возвращает все языки
	 * @return ImageLang[]
	 */
	public function getLangAll() {
		$langs = ImageLang::model()->findAllByAttributes(array(
			'image_id' => $this->id,
		));
		
		return $langs;
	}
	
	/**
	 * Возвращает языковую часть
	 * @return ImageLang
	 */
	public function getLangPart() {
		if(!empty($this->_cLangPart))
			return $this->_cLangPart;
		
		$langs = $this->getLangAll();
		
		// выбираем активный
		foreach ($langs as $_lang) {
			if($_lang->lang_id === Lang::getActive()->id)
				$langPart = $_lang;
		}
		
		if(empty($langPart) && !empty($langs[0])) {
			// тогда берем первый попавшийся
			$langPart = $langs[0];
		}
		
		if(empty($langPart)) {
			// тогда создаем пустой
			$langPart = new ImageLang;
			$langPart->image_id = !empty($this->id)?$this->id:0;
			$langPart->lang_id = Lang::getActive()->id;
		}
		
		$langPart->setModelMain($this);
		$this->_cLangPart = $langPart;
		
		return $langPart;
	}
	
	/**
	 * Возвращает имя файла
	 * @return string
	 */
	public function getName() {
		$langPart = $this->getLangPart();
		
		return $langPart->name;
	}
	
	/**
	 * Возвращает url адресс изображения
	 * @return string
	 */
	public function getUrl() {
		$url = '/storage/images/'.$this->file;
		return $url;
	}
	
	// ========================================================================
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Image the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file', 'required'),
			array('image_type_id', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, file, file_extension, image_type_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			"image_lang" => array(self::HAS_MANY, "ImageLang", 'image_id'),
			"type" => array(self::BELONGS_TO, "ImageType", 'image_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'file' => 'File Name',
			'image_type_id' => 'Image Type',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('file', $this->file, true);
		$criteria->compare('image_type_id', $this->image_type_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => false,
		));
	}

}
