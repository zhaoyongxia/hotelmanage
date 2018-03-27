<?php

namespace hotel\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "zxe_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $phone
 * @property integer $logintime
 * @property string $access_token
 * @property integer $token_date
 * @property string $name
 * @property string $wx
 * @property string $address
 * @property string $hotelname
 */
class User extends HomeModel implements IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'phone', 'wx'], 'required'],
            [['logintime', 'token_date'], 'integer'],
            [['username', 'access_token', 'wx'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 18],
            [['name', 'address'], 'string', 'max' => 200],
            [['hotelname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'phone' => '联系人电话',
            'logintime' => '登陆时间',
            'access_token' => 'Access Token',
            'token_date' => 'Token时间',
            'name' => '联系人',
            'wx' => '微信号',
            'address' => '酒店地址',
            'hotelname' => '酒店名称',
        ];
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $user = self::findById($id);
        if ($user) {
            return new static($user);
        }
        return null;
    }

    public static function findById($id)
    {
        $user = User::find()->where(array('id' => $id))->asArray()->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }
}
