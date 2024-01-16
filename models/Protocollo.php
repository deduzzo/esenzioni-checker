<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "protocollo".
 *
 * @property int $id_protocollo
 * @property string $protocollo
 * @property int $anno
 * @property string $cf_titolare_esenzione
 * @property string|null $esenzione
 * @property string|null $cod_fiscale
 * @property string|null $data_inizio
 * @property string|null $data_fine
 * @property string|null $esito
 * @property string|null $descrizione
 * @property float|null $importo_totale
 *
 * @property Ricetta[] $ricettas
 */
class Protocollo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'protocollo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['protocollo', 'anno', 'cf_titolare_esenzione'], 'required'],
            [['anno'], 'integer'],
            [['descrizione'], 'string'],
            [['importo_totale'], 'number'],
            [['protocollo', 'cf_titolare_esenzione', 'cod_fiscale'], 'string', 'max' => 16],
            [['esenzione', 'data_inizio', 'data_fine'], 'string', 'max' => 10],
            [['esito'], 'string', 'max' => 100],
            [['protocollo', 'anno'], 'unique', 'targetAttribute' => ['protocollo', 'anno']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_protocollo' => 'Id Protocollo',
            'protocollo' => 'Protocollo',
            'anno' => 'Anno',
            'cf_titolare_esenzione' => 'Cf Titolare Esenzione',
            'esenzione' => 'Esenzione',
            'cod_fiscale' => 'Cod Fiscale',
            'data_inizio' => 'Data Inizio',
            'data_fine' => 'Data Fine',
            'esito' => 'Esito',
            'descrizione' => 'Descrizione',
            'importo_totale' => 'Importo Totale',
        ];
    }

    /**
     * Gets query for [[Ricettas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRicettas()
    {
        return $this->hasMany(Ricetta::class, ['id_protocollo' => 'id_protocollo']);
    }

    public static function totaleImportoTotaleProtocolliByCf($cf)
    {
        return (new Query())
            ->select('SUM(importo_totale) AS totale')
            ->from('protocollo')
            ->where(['cf_titolare_esenzione' => $cf])
            ->scalar();
    }
}
