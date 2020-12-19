<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 14:17
 */

namespace cms\dao;

use common\models\User;
use common\utils\DbUtil;

class UserDao extends User
{

    /**
     *
     * @param $param
     * @return int
     * @des
     * @author mrqi
     * @date 2020-12-19 11:47
     */
    public function add($param){

        return DbUtil::insert($this->tablename(),$param);
    }




    public function checkIsLogin(){

    }


    public static function model(){

        return new self();
    }



}