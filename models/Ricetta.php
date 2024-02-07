<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ricetta".
 *
 * @property int $id
 * @property string $numero
 * @property string|null $tipologia
 * @property string|null $struttura
 * @property string|null $ubicazione
 * @property string|null $data_prescrizione
 * @property string|null $data_spedizione
 * @property float|null $ticket
 * @property int $id_protocollo
 *
 * @property Prestazione[] $prestaziones
 * @property Protocollo $protocollo
 */
class Ricetta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ricetta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'id_protocollo'], 'required'],
            [['tipologia'], 'string'],
            [['ticket'], 'number'],
            [['id_protocollo'], 'integer'],
            [['numero'], 'string', 'max' => 20],
            [['struttura', 'ubicazione'], 'string', 'max' => 100],
            [['data_prescrizione', 'data_spedizione'], 'string', 'max' => 10],
            [['id_protocollo'], 'exist', 'skipOnError' => true, 'targetClass' => Protocollo::class, 'targetAttribute' => ['id_protocollo' => 'id_protocollo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero' => 'Numero',
            'tipologia' => 'Tipologia',
            'struttura' => 'Struttura',
            'ubicazione' => 'Ubicazione',
            'data_prescrizione' => 'Data Prescrizione',
            'data_spedizione' => 'Data Spedizione',
            'ticket' => 'Ticket',
            'id_protocollo' => 'Id Protocollo',
        ];
    }

    /**
     * Gets query for [[Protocollo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProtocollo()
    {
        return $this->hasOne(Protocollo::class, ['id_protocollo' => 'id_protocollo']);
    }

    /**
     * Gets query for [[Prestaziones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrestaziones()
    {
        return $this->hasMany(Prestazione::class, ['id_ricetta' => 'id']);
    }
}
