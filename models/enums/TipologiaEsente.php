<?php
namespace app\models\enums;
use yii2mod\enum\helpers\BaseEnum;


class TipologiaEsente extends BaseEnum {

    const ASSITITO_ESENTE = "esente";
    const ASSISTITO_DICHIARANTE = "dichiarante";
    const ASSISTITO_TITOLARE = "titolare";
    const TUTTI = "tutti";
    /**
     * @var string message category
     * You can set your own message category for translate the values in the $list property
     * Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'app';

    /**
     * @var array
     */
    public static $list = [
        self::ASSITITO_ESENTE => 'Esente',
        self::ASSISTITO_DICHIARANTE => 'Dichiarante',
        self::ASSISTITO_TITOLARE => 'Titolare',
        self::TUTTI => 'Qualsiasi',
    ];
    // invert $list to get key => value
    public static $listaInvertita = [
        'Esente' => self::ASSITITO_ESENTE,
        'Dichiarante' => self::ASSISTITO_DICHIARANTE,
        'Titolare' => self::ASSISTITO_TITOLARE,
        'Qualsiasi' => self::TUTTI,
    ];
}