<?php

use PhpOffice\PhpWord\PhpWord;

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
              'necessary' => Yii::t("main", 'Necessary'),
              'possible' => Yii::t("main", 'Possible'),
              'must_not' => Yii::t("main", 'Must not'),
              'important' => Yii::t("main", "it's important"),
              'text' => Yii::t("main", "Text"),
            );

            foreach($vars as $var => $name) {
                $text = strip_tags($article->getLangPart()->$var);
                if( !empty($text) ) {
                    $section->addText($name, self::STYLE_POSITION, self::PARAGRAF_POSITION);
                    $section->addText($text);
                    $section->addTextBreak(1);
                }

            }

        }

        // Save File
        $this->phpword->save('export_files/pocket_doctor.docx');
	}

}