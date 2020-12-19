<?php
namespace cms\tools;

use common\utils\JwtUtil;

/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/19
 * Time: 13:53
 * @des 权限检查tool
 */

class PowerCheck
{

    /**
     * @des 是否登录
     * @author mrqi
     * @date 2020-12-19 14:48
     */
    public static function isLogin($token){
        //验证 jwt是否过期
        $jwt=JwtUtil::ValiJwt($token);
        if(empty($jwt)){

            return false;
        }

        //获取指定参数的PayLoad(负载)信息
        $id = $jwt->getClaim('id');
        $user_name=$jwt->getClaim('user');



    }


    /**
     * @des 获取登录用户的拥有权限的资源
     * @author mrqi
     * @date 2020-12-19 14:49
     * user->角色-》权限-》资源
     */
    public static function getUserResouce($id){


    }



}