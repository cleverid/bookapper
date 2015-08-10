<?php

use PhpOffice\PhpWord\PhpWord;
require(Yii::getPathOfAlias('phpquery').'/phpQuery/phpQuery.php');

class ExportController extends Controller {

    const STYLE_H1 = "FS_H1";
    const STYLE_POSITION = "FS_POSITION";
    const STYLE_ARTICLE = "FS_ARTICLE";

    const PARAGRAF_H1 = "PS_H1";
    const PARAGRAF_POSITION = "PS_POSITION";
    const PARAGRAF_ARTICLE = "PS_ARTICLE";

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

        $this->phpword->addFontStyle(self::STYLE_ARTICLE, array('size' => 12));
        $this->phpword->addParagraphStyle(self::PARAGRAF_ARTICLE, array('align' => 'left'));
    }

    /**
     * @throws Exception
     */
    public function actionIndex() {
        $activeLang = Lang::getActive();

        foreach(Lang::getAll() as $lang) {
            $this->phpword = new PhpWord();
            Lang::setActive($lang->code);
            $name = 'pocket_doctor_'.$lang->code.'.docx';
            $this->export($name);
            $files[] = array(
                'name' => $name,
                'url' => '/export_files/'.$name,
            );
        }

        Lang::setActive($activeLang->code);

        $this->render('view', array(
            'files' => $files
        ));
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

        // Content
        $section = $this->phpword->addSection();
        $section->addText(Yii::t('main', 'Content_Export'), self::STYLE_POSITION, self::PARAGRAF_POSITION);
        foreach($articles as $article) {
            $section->addText($article->getTitleWithPosition());
        }

        // Articles
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

                if( !!strlen(trim(strip_tags($text))) ) {
                    $section->addText($name, self::STYLE_POSITION, self::PARAGRAF_POSITION);

                    try{
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $text);
                    } catch(Exception $e) {
                        echo $text;
                    }

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

        $text= str_replace("<b>", '[[strong]]', $text);
        $text= str_replace("</b>", '[[/strong]]', $text);
        $text= str_replace("<ul>", '[[ul]]', $text);
        $text= str_replace("</ul>", '[[/ul]]', $text);
        $text= str_replace("<li>", '[[li]]', $text);
        $text= str_replace("</li>", '[[/li]]', $text);
        // delete all tags
        $text = strip_tags($text);
        // restore allowed tags
        $text= str_replace("[[", '<', $text);
        $text= str_replace("]]", '>', $text);

        $text = trim($text);

        $lines = preg_split('/\\r\\n?|\\n/', $text);
        $textFinal = '';
        $lineSpace = 0;
        foreach($lines as $line) {
            $line = trim($line);

            if(strlen(trim($line)) === 0) {
                $lineSpace++;
                if($lineSpace > 1) {
                    continue;
                }
            } else {
                $lineSpace = 0;
            }

            if(preg_match('/>\s*$/', $line)) {
                $textFinal .= $line;
            } else {
                $textFinal .= '<p>'.$line.'</p>';
            }
        }

        return $textFinal;
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