<?php
/** @var yii\web\View $this */
/** @var VerificaForm $model */
/** @var Protocollo[] $risultato */
/** @var array $totalePerAnno */



use app\models\Protocollo;
use app\models\VerificaForm;
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

<script>
    function printDiv2() {
        printJS({
            printable: 'out',
            type: 'html',
            css: '<?= Yii::getAlias('@web/css/print.css') ?>',
            maxWidth: 800,
        });
    }
</script>

<div class="site-index">
    <div class="card">
        <div class="card-header">
            <div class="card-toolbar">
                <h1>Esito verifica </h1>
            </div>
        </div>
        <div class="card-body" id="out">
            <?php if (count($risultato) === 0): ?>
                <div class="alert alert-danger">
                    <h3>Attenzione</h3>
                    <p>Non sono stati trovati protocolli con i dati inseriti</p>
                    <?= Html::a('Torna indietro', ['/site'], ['class' => 'btn btn-lg btn-primary']) ?>
                </div>
            <?php else: ?>
            <!-- mostra il riassunto, numero protocolli trovati e importo totale -->
            <div class="alert alert-success">
                <p>Sono stati segnalati da SOGEI <b> <?= count($risultato) ?> protocolli</b> con il codice fiscale
                    <b><?= $model->codice_fiscale ?></b></p>
                <!-- suddivisione per anni -->
                <table class="table table-striped table-bordered table-main" style="max-width: 500px">
                    <thead>
                    <tr>
                        <th>Anno</th>
                        <th>N° contestazioni</th>
                        <th>Importo Totale</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($totalePerAnno as $anno => $importo): ?>
                        <tr>
                            <td><?= $anno ?></td>
                            <td><?= $importo['numProtocolli'] ?></td>
                            <td><?= $importo['totale'] . "€" ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p>Importo totale contestato (tutte le annualità):
                    <b> <?= Protocollo::totaleImportoTotaleProtocolli($risultato) . "€" ?></b>
                </p>
                <div class="alert alert-warning">
                    <p><b>ATTENZIONE!!</b><br />Gli importi mostrati sono relativi ai <b>SOLI</b> protocolli come rilevabile su Sogei, sono pertanto <b>ESCLUSE eventuali sanzioni o ulteriori spese notifica</b>, se dovute.</p>
                </div>
                <!-- Alert con scritta "Stampa Contenuto" -->
                <button id="btnStampa" onclick="printDiv2()">Stampa Contenuto</button>
            </div>
            <div class="card-body daStampare">
                <?php foreach ($risultato as $protocollo): ?>
                    <table class="table table-striped table-bordered table-main">
                        <thead>
                        <tr>
                            <th>Protocollo</th>
                            <th>Anno</th>
                            <th>Esenzione</th>
                            <th>CF Esente</th>
                            <th>CF Dichiarante</th>
                            <th>CF Titolare</th>
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
                            <td><?= $protocollo->cf_esente ?></td>
                            <td><?= $protocollo->cf_dichiarante ?></td>
                            <td><?= $protocollo->cf_titolare ?></td>
                            <td><?= $protocollo->esito ?></td>
                            <td><?= $protocollo->descrizione ?></td>
                            <td><?= $protocollo->importo_totale . "€" ?></td>
                        </tr>
                        <tr>
                            <td colspan="9">
                                <h4>Ricette</h4>
                                <table class="table table-light table-sub" style="margin:5px">
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
