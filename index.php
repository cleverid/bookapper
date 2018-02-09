<?php

require_once 'vendor/phpoffice/phpword/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

// change the following paths if necessary
$yii=dirname(__FILE__).'/vendor/yiisoft/yii/framework/yii.php';
//$phpword = dirname(__FILE__)."/vendor/phpoffice/phpword/src/PhpWord/";
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
