<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/19
 * Time: 10:58
 */

namespace common\utils;


use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Yii;

class JwtUtil
{

    public static function createJwt($user_array){

        $request = Yii::$app->getRequest();
        $signer = new Sha256();//使用Sha256加密，常用加密方式有Sha256,Sha384,Sha512
        $now_time = time();
        $tokenBuilder  = Yii::$app->jwt->getBuilder();
        $jwt_key = Yii::$app->jwt->key;
        $tokenBuilder
        ->issuedBy($request->getHostInfo()) // 设置发行人
        ->permittedFor(isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '') // 设置接收
        ->identifiedBy(Yii::$app->security->generateRandomString(10), true) // 设置id
        ->issuedAt($now_time) // 设置生成token的时间
        ->canOnlyBeUsedAfter($now_time) // 设置token使用时间(实时使用)
        ->expiresAt($now_time + 3600); //设置token过期时间
        $tokenBuilder->withClaim('user',$user_array['user_name']);
        $tokenBuilder->withClaim('id',$user_array['id']);
        $token = $tokenBuilder->getToken($signer, new Key($jwt_key));

        return (String)$token;
    }

    /**
     *
     * @param $token
     * @param $user_name
     * @des   用户名和token验证
     * @author mrqi
     * @date 2020-12-19 11:27
     */
    public static function ValiJwt($token){

        return $token = Yii::$app->jwt->ValiJwt($token); #JWT验证

    }


}