<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 14:28
 */

namespace cms\services;

use cms\dao\UserDao;
use common\components\Service;
use common\models\User;
use common\utils\JwtUtil;
use common\utils\SecurityUtil;
use Yii;

class UserServices extends Service
{

    /**
     * @des 用户注册
     * @author mrqi
     * @date 2020-12-18 14:31
     */
    public function signUp($user_name,$password){

        $password = SecurityUtil::md5Md5($password);

        $token ='';
        $user = [
            'user_name'=>$user_name,
            'password'=>$password,
            #'token'=>$token,
        ];
         $id =UserDao::model()->add($user);
         if($id){
             $user_array = [
                 'user_name'=>$user_name,
                 'id'=>$id,
             ];

             $token=JwtUtil::createJwt($user_array);
         }


        return ['token'=>$token];
    }



}