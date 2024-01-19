<?php

namespace app\models;

use yii\base\Model;

class VerificaForm extends Model
{
    public $protocollo;
    public $codice_fiscale;
    public $anni;
    public $tipo_cf;
    public $captcha;

    public function rules()
    {
        return [
            ['protocollo', 'required', 'when' => function($model) {
                return $model->codice_fiscale === null || $model->codice_fiscale === '';
            }, 'message' => "E' necessario inserire almeno uno tra Protocollo e Codice Fiscale."],

            ['codice_fiscale', 'required', 'when' => function($model) {
                return $model->protocollo === null || $model->protocollo === '';
            }, 'message' => ''],
            ['protocollo', 'match', 'pattern' => '/^[0-9]{1,9}$/'],
            ['codice_fiscale', 'string', 'length' => 16],
            ['anni', 'each', 'rule' => ['integer']],
            ['anni', 'required', 'message' => 'Selezionare almeno un anno.'],
            ['tipo_cf', 'string'],
            ['captcha', 'captcha'],
        ];
    }
}
