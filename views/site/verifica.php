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
    /* Riduzione della dimensione del testo per le righe dei dettagli e delle ricette */
    .small-text {
        font-size: 0.85em;
    }

    /* Stili per righe pari delle ricette */
    .ricetta-even-row {
        background-color: #f8f9fa; /* Colore di sfondo per le righe pari (ad esempio, un grigio chiaro) */
    }

    /* Stili per righe dispari delle ricette */
    .ricetta-odd-row {
        background-color: #e9ecef; /* Colore di sfondo per le righe dispari (ad esempio, un grigio leggermente più scuro) */
    }

    /* Nascondi la navbar durante la stampa */
    @media print {
        .container {
            padding: 0 !important; /* Rimuove il padding e usa !important per garantire che l'impostazione venga applicata */
        }

        body {
            font-size: 10px; /* Imposta la dimensione del testo desiderata (ad esempio, 16px) */
        }
        .navbar {
            display: none !important;
        }
        .stampa-btn {
            display: none !important;
        }
        @page {
            margin-top: 100mm; /* Margine inizio pagina */
            margin-bottom: 100mm; /* Margine fine pagina */
        }
    }

</style>


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
                        <p><b>ATTENZIONE!!</b><br/>Gli importi mostrati sono relativi ai <b>SOLI</b> protocolli come
                            rilevabile su Sogei, sono pertanto <b>ESCLUSE eventuali sanzioni o ulteriori spese
                                notifica</b>, se dovute.</p>
                    </div>
                    <!-- button to print page -->
                    <div class="d-flex stampa-btn">
                        <button class="btn btn-primary" onclick="window.print()">Stampa Pagina</button>
                        <p style="margin-left: 20px">Impostazioni di stampa consigliate con Chrome: margini <b>nessuno</b> togliere la spunta in "Grafica in background".</p>
                    </div>

                </div>
                <div class="card-body daStampare">
                    <!-- Header del protocollo -->

                    <?php foreach ($risultato as $protocollo): ?>
                        <div class="card" style="margin-bottom: 20px">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-1">Protocollo</div>
                                    <div class="col-md-1">Anno</div>
                                    <div class="col-md-1">Esenzione</div>
                                    <div class="col-md-3">Codici Fiscali</div>
                                    <div class="col-md-1">Esito</div>
                                    <div class="col-md-4">Descrizione</div>
                                    <div class="col-md-1">Importo</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Dettagli del protocollo con testo ridotto -->
                                <div class="row protocollo-details small-text">
                                    <div class="col-md-1"><?= htmlspecialchars($protocollo->protocollo) ?></div>
                                    <div class="col-md-1"><?= htmlspecialchars($protocollo->anno) ?></div>
                                    <div class="col-md-1"><?= htmlspecialchars($protocollo->esenzione) ?></div>
                                    <div class="col-md-3">
                                        <?= "<b>Esente</b>: " . htmlspecialchars($protocollo->cf_esente ?? "-") . "<br />" .
                                        "<b>Dichiarante</b>: " . htmlspecialchars($protocollo->cf_dichiarante ?? "-") . "<br />" .
                                        "<b>Titolare</b>: " . htmlspecialchars($protocollo->cf_titolare ?? "-") ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?= '<span class="badge bg-danger">' . htmlspecialchars(str_replace("ESITO", "", $protocollo->esito)) . '</span>' ?>
                                    </div>
                                    <div class="col-md-4"><?= htmlspecialchars($protocollo->descrizione) ?></div>
                                    <div class="col-md-1"><?= htmlspecialchars($protocollo->importo_totale) ?> €</div>

                                    <!-- Ricette con testo ridotto e colore di sfondo diverso -->
                                    <div class="col-12">
                                        <div class="card" style="margin-top:5px">
                                            <div class="card-header"><b>Ricette</b></div>
                                            <div class="card-body">
                                                <!-- show header row -->
                                                <div class="row">
                                                    <div class="col-md-2"><b>Numero</b></div>
                                                    <div class="col-md-1"><b>Tipo</b></div>
                                                    <div class="col-md-3"><b>Struttura</b></div>
                                                    <div class="col-md-2"><b>Ubicazione</b></div>
                                                    <div class="col-md-3"><b>Data</b></div>
                                                    <div class="col-md-1"><b>Ticket</b></div>
                                                </div>
                                                <div class="ricette-container">
                                                    <?php foreach ($protocollo->ricettas as $index2 =>  $ricetta): ?>
                                                        <div class="row ricetta-row <?= ($index2 % 2 == 0) ? 'ricetta-even-row' : 'ricetta-odd-row' ?>">
                                                            <div class="col-md-2"><b><?= htmlspecialchars($ricetta->numero) ?></b></div>
                                                            <div class="col-md-1"><b><?= strtoupper(substr(htmlspecialchars($ricetta->tipologia),0,4).".") ?></b></div>
                                                            <div class="col-md-3"><?= htmlspecialchars($ricetta->struttura) ?></div>
                                                            <div class="col-md-2"><?= htmlspecialchars($ricetta->ubicazione) ?></div>
                                                            <div class="col-md-3"><?= "<b>Data Prescrizione:</b> ". htmlspecialchars($ricetta->data_prescrizione). "<br /><b>Data spedizione:</b> ".
                                                                htmlspecialchars($ricetta->data_spedizione) ?></div>
                                                            <div class="col-md-1"><?= htmlspecialchars($ricetta->ticket) ?>€</div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>