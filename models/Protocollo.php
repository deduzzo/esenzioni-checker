<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "protocollo".
 *
 * @property int $id_protocollo
 * @property string $protocollo
 * @property int $anno
 * @property string $cf_esente
 * @property string|null $cf_dichiarante
 * @property string|null $cf_titolare
 * @property string|null $esenzione
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

    public static function getAnniProtocolli()
    {
        $anni = [];
        $query = (new Query())
            ->select('anno')
            ->from('protocollo')
            ->distinct()
            ->orderBy('anno ASC')
            ->column();
        foreach ($query as $anno) {
            $anni[$anno] = $anno;
        }
        return $anni;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['protocollo', 'anno', 'cf_esente'], 'required'],
            [['anno'], 'integer'],
            [['descrizione'], 'string'],
            [['importo_totale'], 'number'],
            [['protocollo', 'cf_esente', 'cf_dichiarante', 'cf_titolare'], 'string', 'max' => 16],
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
            'cf_esente' => 'Cf Esente',
            'cf_dichiarante' => 'Cf Dichiarante',
            'cf_titolare' => 'Cf Titolare',
            'esenzione' => 'Esenzione',
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

    public static function totaleImportoTotaleProtocolli($arrayProtocolli)
    {
        $totale = 0;
        foreach ($arrayProtocolli as $protocollo) {
            $totale += $protocollo->importo_totale;
        }
        return $totale;
    }

    public function getNumTotalePrestazioni()
    {
        return (new Query())
            ->select('COUNT(*)')
            ->from('prestazione')->innerJoin('ricetta', 'prestazione.id_ricetta = ricetta.id')
            ->innerJoin('protocollo', 'ricetta.id_protocollo = protocollo.id_protocollo')
            ->where(['protocollo.id_protocollo' => $this->id_protocollo])->scalar();
    }
}
