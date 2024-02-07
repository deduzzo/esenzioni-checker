<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prestazione".
 *
 * @property int $id
 * @property string|null $regione
 * @property string|null $data_erogazione
 * @property int|null $quantita
 * @property string|null $codice_prodotto
 * @property string|null $descrizione
 * @property float|null $tariffa
 * @property int|null $id_ricetta
 *
 * @property Ricetta $ricetta
 */
class Prestazione extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prestazione';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantita', 'id_ricetta'], 'integer'],
            [['descrizione'], 'string'],
            [['tariffa'], 'number'],
            [['regione'], 'string', 'max' => 10],
            [['data_erogazione'], 'string', 'max' => 20],
            [['codice_prodotto'], 'string', 'max' => 100],
            [['id_ricetta'], 'exist', 'skipOnError' => true, 'targetClass' => Ricetta::class, 'targetAttribute' => ['id_ricetta' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regione' => 'Regione',
            'data_erogazione' => 'Data Erogazione',
            'quantita' => 'Quantita',
            'codice_prodotto' => 'Codice Prodotto',
            'descrizione' => 'Descrizione',
            'tariffa' => 'Tariffa',
            'id_ricetta' => 'Id Ricetta',
        ];
    }

    /**
     * Gets query for [[Ricetta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRicetta()
    {
        return $this->hasOne(Ricetta::class, ['id' => 'id_ricetta']);
    }
}
