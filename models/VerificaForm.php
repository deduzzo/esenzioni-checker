<?php

namespace app\models;

use yii\base\Model;

class VerificaForm extends Model
{
    public $protocollo;
    public $codice_fiscale;
    public $tipo_cf;
    public $captcha;

    public function rules()
    {
        return [
            // validazione per protocollo e codice fiscale
            [['protocollo', 'codice_fiscale'], 'required'],
            ['protocollo', 'match', 'pattern' => '/^[0-9]{1,9}$/'],
            ['codice_fiscale', 'string', 'length' => 16],
            ['tipo_cf', 'string'],
            // regola per la validazione CAPTCHA
            ['captcha', 'captcha'],
        ];
    }
}
