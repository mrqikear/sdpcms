<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 14:23
 */

namespace common\utils;


use Yii;

class DbUtil
{

    /**
     *
     * @param $table_name
     * @param $array
     * @return int
     * @throws \yii\db\Exception
     * @des 插入一条数据
     * @author mrqi
     * @date 2020-12-19 13:49
     */
    public static function insert($table_name,$array){

         Yii::$app->db->createCommand()->insert($table_name,$array)->execute();
         return  Yii::$app->db->getLastInsertID();
    }





}