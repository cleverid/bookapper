<?php

/**
 * This is the model class for table "lang".
 *
 * The followings are the available columns in table 'lang':
 * @property integer $id
 * @property string $lang_type
 */
class Lang extends LangORM
{
	
	public static $_cAllLang;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getAll(){
		if(!empty(self::$_cAllLang))
			return self::$_cAllLang;
		
		$_langs = self::model()->findAll();
		self::$_cAllLang = $_langs;
		
		return self::$_cAllLang;
	}
	
	/**
	 * Возвращает код языка
	 * @return Lang
	 */
	public static function getActive(){
		$langCode = Yii::app()->session['_lang'];
		if( $langCode && ($_lang = self::model()->find('t.code = :l', array('l' => $langCode))) ) {
			return $_lang;
		} elseif($_lang = self::model()->find('t.is_default = 1')) {
			Yii::app()->session['_lang'] = $_lang->code;
			return $_lang;
		} elseif($_lang = self::model()->find()) {
			Yii::app()->session['_lang'] = $_lang->code;
			return $_lang;
		}
	}
	
	/**
	 * Устанавливает язык
	 * @param String $code
	 */
	public static function setActive($code){
		Yii::app()->session["_lang"] = Yii::app()->language = $code;
	}

}