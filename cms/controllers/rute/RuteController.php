<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 16:24
 */

namespace cms\controllers\rute;


use common\components\sdpBaseController;
use common\components\SDPException;
use common\utils\annotationUtil;
use common\utils\FileUtil;


class RuteController extends sdpBaseController
{


    /**
     * @url /rute/rute/getrutes
     * @des  获取controllers下面所有接口
     * @author mrqi
     * @date 2020-12-18 16:25
     */
    public function actionGetrutes()
    {

        $dir = \Yii::getAlias('@cms/controllers/');

        $controllers = FileUtil::scanFile($dir);

        if(empty($controllers)){
            throw  new SDPException("没有controller！！！",500);
        }


        $rute = [];
        foreach ($controllers as $controller){

            $controller = ltrim($controller,'/');
            $controller = ltrim($controller,'/');
            $controller=str_replace('/',"\\",$controller);
            $class = new \ReflectionClass('cms\controllers\\'. $controller);
            //控制器的名称
            $controller = str_ireplace('controller', '', $controller);
            //控制器类的注释
            //控制器中的所有公开方法
            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

            //提取action
            $actionList = [];
            foreach ($methods as $method) {
                $action = $method->getName();
                $identify = substr($action, 0, 6);
                if ($identify == 'action' && $identify.'s' != $action) {
                    $actionName = str_replace('action', '', $action);
                    //action的注释
                    $comment = $method->getDocComment();
                    $url=annotationUtil::getDocComment($comment,"@url");
                    $des = annotationUtil::getDocComment($comment,"@des");

                    if(!empty($url)){
                        array_push($rute,['url'=>$url,'dec'=>$des]);

                    }



                }
            }

        }


        var_dump($rute);
    }






}

