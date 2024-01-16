<?php
/** @var yii\web\View $this */
/** @var \app\models\VerificaForm $model */
/** @var \app\models\Protocollo $risultato */

/** @var \app\models\Protocollo[] $tutti */


use app\models\Protocollo;
use yii\bootstrap5\Html;

$this->title = Yii::$app->name;
?>

<style>
    /* Stile per la tabella principale */
    .table-main th, .table-main td {
        font-weight: bold; /* Rende il testo in grassetto */
    }

    /* Stile per la sottotabella */
    .table-sub th {
        font-size: smaller;
        font-weight: bold

    }
    .table-sub td {
        font-size: smaller;
        font-weight: normal;
    }
</style>

<div class="site-index">
    <div class="card">
        <div class="card-header">
            <div class="card-toolbar">
                <h1>Esito verifica </h1>
            </div>
        </div>
        <div class="card-body">
            <?php if (!$risultato): ?>
                <div class="alert alert-danger">
                    <h3>Attenzione</h3>
                    <p>Non sono stati trovati protocolli con i dati inseriti</p>
                    <?= Html::a('Torna indietro', ['/site'], ['class' => 'btn btn-lg btn-primary']) ?>
                </div>
            <?php else: ?>
                <!-- mostra il riassunto, numero protocolli trovati e importo totale -->
                <div class="alert alert-success">
                    <p>Sono stati segnalati da SOGEI <b> <?= count($tutti) ?> </b> protocolli con il codice fiscale
                        <b><?= $model->codice_fiscale ?></b></p>
                    <p>Importo totale contestato:
                        <b> <?= Protocollo::totaleImportoTotaleProtocolliByCf($model->codice_fiscale) . "€" ?></b></p>
                </div>
                <?php foreach ($tutti as $protocollo): ?>
                    <table class="table table-striped table-bordered table-main">
                        <thead>
                        <tr>
                            <th>Protocollo</th>
                            <th>Anno</th>
                            <th>Esenzione</th>
                            <th>CF</th>
                            <th>Esito</th>
                            <th>Descrizione</th>
                            <th>Importo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $protocollo->protocollo ?></td>
                            <td><?= $protocollo->anno ?></td>
                            <td><?= $protocollo->esenzione ?></td>
                            <td><?= $protocollo->cod_fiscale ?></td>
                            <td><?= $protocollo->esito ?></td>
                            <td><?= $protocollo->descrizione ?></td>
                            <td><?= $protocollo->importo_totale . "€" ?></td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <h4>Ricette</h4>
                                <table class="table table-light table-sub">
                                    <thead>
                                    <tr>
                                        <th>Numero ricetta</th>
                                        <th>Tipologia</th>
                                        <th>Struttura</th>
                                        <th>Ubicazione</th>
                                        <th>Data Prescrizione</th>
                                        <th>Data Spedizione</th>
                                        <th>Importo Ticket</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($protocollo->ricettas as $ricetta): ?>
                                        <tr>
                                            <td><?= $ricetta->numero ?></td>
                                            <td><?= $ricetta->tipologia ?></td>
                                            <td><?= $ricetta->struttura ?></td>
                                            <td><?= $ricetta->ubicazione ?></td>
                                            <td><?= $ricetta->data_prescrizione ?></td>
                                            <td><?= $ricetta->data_spedizione ?></td>
                                            <td><?= $ricetta->ticket . " €" ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
