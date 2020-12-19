<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sdp_user".
 *
 * @property int $id
 * @property string $user_name 用户名
 * @property string $password 密码
 * @property string $token 登录标识
 * @property string $create_time
 * @property string $update_time
 * @property int $is_del 0:未删除 1:删除
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sdp_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time'], 'safe'],
            [['is_del'], 'integer'],
            [['user_name', 'password'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 2048],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_name' => 'User Name',
            'password' => 'Password',
            'token' => 'Token',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_del' => 'Is Del',
        ];
    }


}
