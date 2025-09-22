<?php

namespace app\models;

use app\models\enums\TipologiaLogin;
use Yii;
use yii\httpclient\Client;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    public static $utentiDefault = [
        "asp" => "asp",
    ];


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return new static([
            "id" => $id,
            "username" => $id,
            "password" => $id,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /*       foreach (self::$users as $user) {
                   if ($user['accessToken'] === $token) {
                       return new static($user);
                   }
               }*/

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        /*        foreach (self::$users as $user) {
                    if (strcasecmp($user['username'], $username) === 0) {
                        return new static($user);
                    }
                }*/

        return null;
    }

    public static function findByUsernameAndPassword($username, $password, $tipo, $otp = null)
    {
        $uri = "https://ws1.asp.messina.it/api/v1/login/get-token";
        $data = [
            'login' => $username,
            'password' => $password,
            'scopi' => Yii::$app->params['scopi'],
            'ambito' => Yii::$app->params['ambito']
        ];
        if ($tipo == TipologiaLogin::DOMINIO) {
            if (!str_ends_with('@asp.messina.it', $username))
                $username .= '@asp.messina.it';
            $data['username'] = $username;
            $data['domain'] = Yii::$app->params['domain'];
        }
        if (!empty($otp))
            $data['otp'] = $otp;
        // fai la chiamata api usando yiisoft/yii2-httpclient
        $client = new Client();
        $response = $client->post($uri, $data)->send();
        $data2 = $response->getData();
        if ($response->isOk) {
            if (!empty($data2['data']['otpExpire'])) {
                // otp required
                return [
                    'ok' => true,
                    'otpRequired' => true,
                    'user'=>null
                ];
            }
            else return [
                'ok' => true,
                'otpRequired' => false,
                'user' => new User([
                    'id' => $username,
                    'username' => $username,
                    'password' => $password,
                    'accessToken' => $data2['data']['token'],
                ])
            ];
        }
        else {
            return [
                'ok' => false,
                'otpRequired' => false,
                'user'=>null
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
