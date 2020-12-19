<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);




/**
 * @param $data
 * @param int $way 0:echo 1:print_r 2:var_dump
 * @param bool $isExit true:exit;
 */
function myDebug( $data,$way=1,$isExit=true ){
    echo '<pre>';
    switch( $way ){
        case 0 :
            echo $data;
            break;
        case 1 :
            print_r($data);
            break;
        case 2 :
            var_dump($data);
            break;
    }
    if( $isExit === true )
        exit;
}


(new yii\web\Application($config))->run();
