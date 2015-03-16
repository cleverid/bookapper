<?php

class GalleryController extends Controller {
	
	public function actionIndex() {
		$mImage = new Image;
		$mImage->unsetAttributes();
		if(isset($_GET['Image'])) {
			$mImage->setAttributes($_GET['Image'], false);
		}
			
		$this->render('gallery', array(
			'mImage' => $mImage,
		));
	}
	
	public function actionUpdate($id = null) {
		
		if($id !== null)
			$mImage = Image::model()->findByPk($id);
		else
			$mImage = new Image;
		
		if(isset($_POST['Image'])) {
			$mImage->setAttributes($_POST['Image'], false);
			$mImage->getLangPart()->setAttributes($_POST['ImageLang'], false);
			
			if($mImage->validate() & $mImage->getLangPart()->validate()) {
				$mImage->save(false);
				$mImage->getLangPart()->save(false);
				$this->redirect('/gallery/update/'.$mImage->id);
			} else {
				//Service::preView($mImage->attributes);
			}
		}
		
		$this->render('update', array(
			'mImage' => $mImage,
		));
	}
	
	public function actionDelete($id) {
		Image::model()->deleteAllByAttributes(array(
			'id' => $id,
		));
		ImageLang::model()->deleteAllByAttributes(array(
			'image_id' => $id,
		));
	}
}