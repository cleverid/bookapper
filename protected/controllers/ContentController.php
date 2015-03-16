<?php

class ContentController extends Controller {
	
	public function actionIndex() {
		$mArticle = new Article;
		$mArticle->unsetAttributes();
		
		if (isset($_POST['Article']))
			$mArticle->attributes = $_POST['Article'];
		
		$mArticle->langId = Lang::getActive()->id;
		
		$this->render('content', array(
			'mArticle' => $mArticle,
		));
	}
	
	public function actionUpdate($id = null){
		
		$mArticle = Article::model()->findByPk($id);
		if(!$mArticle)
			$mArticle = new Article;

		$mArticleLang = ArticleLang::model()->findByAttributes(array(
			'article_id' => $id,
			'lang_id' => Lang::getActive()->id,
		));
		if(!$mArticleLang)
			$mArticleLang = new ArticleLang;

		if(isset($_POST['Article'])){
			$mArticle->setAttributes($_POST['Article'], false);
			$mArticleLang->setAttributes($_POST['ArticleLang'], false);
			$mArticleLang->article_id = 1;
			$mArticleLang->lang_id = Lang::getActive()->id;

			if($mArticle->validate() && $mArticleLang->validate()) {
				$mArticle->save(false);
				$mArticleLang->article_id = $mArticle->id;
				$mArticleLang->save(false);
				$this->redirect('/content/update/id/'.$mArticle->id);
			}
		}
		
		$this->render('update', array(
			'mArticle' => $mArticle,
			'mArticleLang' => $mArticleLang,
		));
	}
	
	public function actionDelete($id){
		Article::model()->deleteByPk($id);
		$crit->compare('t.article_id', $id);
		$crit->compare('t.lang_id', Lang::getActive()->id);
		ArticleLang::model()->delete($crit);
	}

    public function actionUpdaterow() {
        $es = new EditableSaver('Article');
        try {
            $es->update();
        } catch (Exception $ex) {
            echo CJSON::encode(array('success' => false, 'msg' => $ex->getMessage()));
            return;
        }

        echo CJSON::encode(array('success' => true));
    }
}