<?php

namespace hotel\modules\admin\controllers;

use hotel\models\MyHttpBearerAuth;
use hotel\models\User;
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
     * 登录接口
     * http://localhost/hotelmanage/hotel/web/backend/users/login post 提交 提供 email 和 password
     * @return string access-token
     */
    public function actionLogin()
    {
        return ['zyx'=>'登陆成功'];
    }

    /**
     * 注册接口
     * http://localhost/hotelmanage/hotel/web/backend/users/register
     * post 提交 提供 email 和 password
     * @return string
     */
    public function actionRegister()
    {
        return ['zyx'=>'注册成功'];
    }

}
