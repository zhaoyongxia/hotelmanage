<?php

namespace hotel\modules\api\controllers;

use hotel\models\MyHttpBearerAuth;
use hotel\models\User;
use hotel\modules\user\UserUtils;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class UsersController extends ActiveController
{
    public $modelClass = 'hotel\models\User';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => MyHttpBearerAuth::className(),
                'only' => ['logout'],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'text/html' => Response::FORMAT_JSON,
                ],
            ],
            'rateLimiter' => [
                'class' => RateLimiter::className(),
                'enableRateLimitHeaders' => true,
            ],
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        // 禁用""elete" 和 "create" 操作
        unset($actions['delete'], $actions['create'], $actions['index'], $actions['view'],$actions['update']);
        return $actions;
    }

    /**
     * api小程序登录接口
     * http://localhost/hotelmanage/hotel/web/api/users/login post 提交 提供 微信号（wx）或者（电话）phone 和 password
     * @return string access-token
     */
    public function actionLogin()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if(isset($params['wxOrPhone']) && isset($params['password'])){
                $WxOrPhone = $params['wxOrPhone'];
                $password = $params['password'];
                $user = UserUtils::getUser($WxOrPhone,$password);
            }else{
                $result = [
                    'data' => '',
                    'status' => 'error',
                    'code' => 1100,
                    'msg' => \Yii::$app -> params['errorCode']['1100'],
                ];
                return $result;
            }
            if (isset($user)) {
                if($user -> status == 0){
                    //返回一个用户未审核的错误码
                    $result = [
                        'data' => '',
                        'status' => 'error',
                        'code' => 3100,
                        'msg' => \Yii::$app -> params['errorCode']['3100'],
                    ];
                    return $result;
                }
                if($user -> status == 2){
                    //返回一个用户审核不通过的错误码
                    $result = [
                        'data' => '',
                        'status' => 'error',
                        'code' => 3300,
                        'msg' => \Yii::$app -> params['errorCode']['3300'],
                    ];
                    return $result;
                }
                $accessToken = UserUtils::getToken($user -> id,$WxOrPhone);
                $user->access_token = $accessToken;
                $user->token_date = time();
                $user->logintime = time();
                $user->save();
                $result = [
                    'data' => ['token' => $accessToken],
                    'status' => 'success',
                    'code' => 0,
                    'msg' => '',
                ];
                return $result;
            } else {
                $result = [
                    'data' => '',
                    'status' => 'error',
                    'code' => 1000,
                    'msg' => \Yii::$app -> params['errorCode']['1000'],
                ];
                return $result;
            }
        } else {
            $result = [
                'data' => '',
                'status' => 'error',
                'code' => 2000,
                'msg' => \Yii::$app -> params['errorCode']['2000'],
            ];
            return $result;
        }
    }

    /**
     * 注册接口
     * http://localhost/hotelmanage/hotel/web/api/users/register
     * post 提交 提供 email 和 password
     * @return string
     */
    public function actionRegister()
    {

//        if (\Yii::$app->request->isPost) {
//            $params = \Yii::$app->request->post();
//            $hotel_name = $params['hotel_name'];
//            $address = $params['address'];
//            $name = $params['name'];
//            $phone = $params['phone'];
//            $wx = $params['wx'];
//            $password = md5('111111');
//            $user = User::find()->where(['wx' => $wx])->orWhere(['phone' => $phone])->one();
//            if(empty($wx) && empty($phone)){
//                $result = [
//                    'data' => '',
//                    'status' => 'error',
//                    'code' => 3200,
//                    'msg' => \Yii::$app -> params['errorCode']['3200'],
//                ];
//                return $result;
//            }
//            if ($user) {
//                $result = [
//                    'data' => '',
//                    'status' => 'error',
//                    'code' => 4000,
//                    'msg' => \Yii::$app -> params['errorCode']['3000'],
//                ];
//                return $result;
//            } else {
//
//                $result = [
//                    'status' => 'success',
//                    'code' => 0,
//                    'msg' => '',
//                ];
//                return $result;
//            }
//        } else {
//            $result = [
//                'data' => '',
//                'status' => 'error',
//                'code' => 2000,
//                'msg' => \Yii::$app -> params['errorCode']['2000'],
//            ];
//            return $result;
//        }
    }


}
