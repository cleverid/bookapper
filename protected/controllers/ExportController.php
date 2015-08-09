<?php

use PhpOffice\PhpWord\PhpWord;
require(Yii::getPathOfAlias('phpquery').'/phpQuery/phpQuery.php');

class ExportController extends Controller {

    const STYLE_H1 = "FS_H1";
    const STYLE_POSITION = "FS_POSITION";

    const PARAGRAF_H1 = "PS_H1";
    const PARAGRAF_POSITION = "PS_POSITION";

    /** @var PhpWord */
    public $phpword;

    public function init() {
        parent::init();

        $this->phpword = new PhpWord();
        $this->setStyle();
    }

    private function setStyle() {
        $this->phpword->addFontStyle(self::STYLE_H1, array('bold' => true, 'size' => 16));
        $this->phpword->addParagraphStyle(self::PARAGRAF_H1, array('align' => 'center', 'spaceAfter' => 100));

        $this->phpword->addFontStyle(self::STYLE_POSITION, array('bold' => true, 'size' => 14));
        $this->phpword->addParagraphStyle(self::PARAGRAF_POSITION, array('align' => 'left', 'spaceAfter' => 100));
    }

    /**
     * @throws Exception
     */
    public function actionIndex() {
        $activeLang = Lang::getActive();

        foreach(Lang::getAll() as $lang) {
            $this->phpword = new PhpWord();
            Lang::setActive($lang->code);
            $this->export('pocket_doctor_'.$lang->code.'.docx');
        }

        Lang::setActive($activeLang->code);
	}

    /**
     * @param string $nameFile
     */
    private function export($nameFile) {
        $crit = new CDbCriteria;
        $crit->together = true;
        $crit->with = array('article_lang');
        $crit->order = 't.position';
        /** @var Article[] $articles */
        $articles = Article::model()->findAll($crit);

        foreach($articles as $article) {
            $section = $this->phpword->addSection();
            $section->addText($article->getTitleWithPosition(), self::STYLE_H1, self::PARAGRAF_H1);

            $vars = array(
                'text' => Yii::t("main", "Text"),
                'necessary' => Yii::t("main", 'Necessary'),
                'possible' => Yii::t("main", 'Possible'),
                'must_not' => Yii::t("main", 'Must not'),
                'important' => Yii::t("main", "it's important"),
            );

            foreach($vars as $var => $name) {
                $text = $this->prepareText($article->getLangPart()->$var);

                if( !empty($text) ) {
                    $section->addText($name, self::STYLE_POSITION, self::PARAGRAF_POSITION);
                    $section->addText($text);
                    $section->addTextBreak(1);
                }
            }

        }

        // Save File
        $this->phpword->save('export_files/'.$nameFile);
    }

    /**
     * @param $text
     * @return string
     */
    private function prepareText($text) {
        $text = $this->prepareLinks($text);
        $text = $this->prepareImages($text);
        $text = strip_tags($text);

        return $text;
    }

    /**
     * @param string $text
     * @return string
     */
    private function prepareLinks($text) {
        $selectorLink = 'data-link-id';
        $selectorLinkPosition = 'data-link-position';
        $selectorLinkName = 'data-link-name';

        $doc = phpQuery::newDocument($text);

        $links = $doc->find("[$selectorLink]");
        foreach ($links as $el) {
            $pq = pq($el); // Это аналог $ в jQuery
            $idArticle = $pq->attr($selectorLink);
            /** @var Article $article */
            $article = Article::model()->findByPk($idArticle);
            if($article) {
                $pq->find("[$selectorLinkName]")->text($article->getLangPart()->title);
                $pq->find("[$selectorLinkPosition]")->text($article->position);
            }
        }

        return $doc;
    }

    /**
     * @param string $text
     * @return string
     */
    private function prepareImages($text) {
        $selectorGallery = 'gallery-items';
        $selectorImage = 'data-image-id';

        $doc = phpQuery::newDocument($text);

        $galeries = $doc->find(".$selectorGallery");
        $textGallery = '';
        foreach ($galeries as $gallery) {
            $pqGallery = pq($gallery);

            $images = $pqGallery->find("[$selectorImage]");
            foreach ($images as $key => $img) {
                $pqImg = pq($img);
                $idImage = $pqImg->attr($selectorImage);
                /** @var Image $image */
                $image = Image::model()->findByPk($idImage);
                if($image) {
                    $textImg = !$key?"":PHP_EOL;
                    $textImg .= Yii::t('main', 'Image #{id}', array('{id}' => $image->id));
                    if(strlen(trim($image->getName())) > 0) {
                        $textImg .= ' - '.$image->getName();
                    }

                    $textGallery .= $textImg;
                }
            }

            $textGallery .= PHP_EOL;
            $pqGallery->wrap('<span></span>')->parent()->text($textGallery);
        }

        return $doc;
    }

}