<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-3-26
 * Time: 下午3:33
 */

namespace hotel\modules\user;


use hotel\models\User;

class UserUtils {

    /** 生成随机数
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10) {
        $characters = '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function getToken($userid,$WxOrPhone){
        $token = $userid.';'.sha1($WxOrPhone . time());//用户id + 分号 + sha1产生的随机数
        return $token;
    }

    public static function getUser($WxOrPhone,$password){
        $user = User::find()->where(['wx' => $WxOrPhone])->orWhere(['phone' => $WxOrPhone])->andWhere(['password' => md5($password)])->one();
        if($user){
            return $user;
        }else{
            return null;
        }
    }

} 