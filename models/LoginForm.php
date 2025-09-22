<?php

namespace app\models;

use app\models\enums\TipologiaLogin;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $tipo = TipologiaLogin::DOMINIO;

    // OTP related fields
    public $otp;           // one-time password entered by the user (second step)
    public $otpRequired = false; // flag to drive the two-step form rendering

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are required in the first step (when OTP is not requested)
            [['username', 'password'], 'required', 'when' => function ($model) { return !$model->otpRequired; },
                'whenClient' => "function (attribute, value) { return $('#loginform-otprequired').val() != '1'; }"],
            // otp is required only when otpRequired is true (second step)
            ['otp', 'required', 'when' => function ($model) { return (bool)$model->otpRequired; },
                'whenClient' => "function (attribute, value) { return $('#loginform-otprequired').val() == '1'; }"],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['tipo', 'string'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // $user = $this->getUser();
            $user = User::findByUsernameAndPassword($this->username, $this->password, $this->tipo, $this->otp ?? null);

            if (!$user['ok']) {
                $this->addError($attribute, 'Utente non valido ' . ($this->tipo == TipologiaLogin::DOMINIO ? 'nel dominio' : 'nel database'));
            }
            else {
                if ($user['otpRequired']) {
                    $this->otpRequired = true;
                }
                else {
                    $this->_user = $user['user'];
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            // If OTP is required, do not attempt to login yet
            if ($this->otpRequired) {
                return false;
            }
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
