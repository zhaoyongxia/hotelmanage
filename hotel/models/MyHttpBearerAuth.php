<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace hotel\models;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;

/**
 * HttpBearerAuth is an action filter that supports the authentication method based on HTTP Bearer token.
 *
 * You may use HttpBearerAuth by attaching it as a behavior to a controller or module, like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'bearerAuth' => [
 *             'class' => \yii\filters\auth\HttpBearerAuth::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MyHttpBearerAuth extends HttpBearerAuth
{
    /**
     * @var string the HTTP authentication realm
     */
    public $realm = 'hotel';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match("/^Bearer\\s+(.*?)$/", $authHeader, $matches)) {
            $identity = $user->loginByAccessToken($matches[1], get_class($this));
            if ($identity === null) {
                $this->handleFailure($response);
            }
            return $identity;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function challenge($response)
    {
        $response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
    }

    /**
     * @inheritdoc
     */
    public function handleFailure($response)
    {
        $id = Yii::$app -> cache -> get('id');
        if(empty($response -> getHeaders() -> get('Authorization'))){
            $error = Yii::$app -> params['errorCode']['1600'];
            throw new UnauthorizedHttpException($error,'1600');
        }
        if(empty($id)){
            $error = Yii::$app -> params['errorCode']['9000'];
            throw new UnauthorizedHttpException($error,'9000');
        }else{
            $access_token = Yii::$app -> cache -> get($id);
            $user = User::findById($id);
            if($access_token!=$user ->access_token){
                $error = Yii::$app -> params['errorCode']['1100'];
                throw new UnauthorizedHttpException($error,'1100');
            }else{
                if(time() -$user->token_date>30*60){
                    $error = Yii::$app -> params['errorCode']['1500'];
                    throw new UnauthorizedHttpException($error,'1500');
                }
            }
        }
    }
}
