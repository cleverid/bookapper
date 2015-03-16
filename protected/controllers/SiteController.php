<?php

class SiteController extends Controller {
	public $articlesList;
	
	public function actionIndex() {
		$this->render('index');
	}
}