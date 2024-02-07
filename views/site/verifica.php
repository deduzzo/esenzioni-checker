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
        background-color: #f8f9fa;
        margin-bottom: 5px; /* Colore di sfondo per le righe pari (ad esempio, un grigio chiaro) */
    }

    /* Stili per righe dispari delle ricette */
    .ricetta-odd-row {
        background-color: #e9ecef;
        margin-bottom: 5px; /* Colore di sfondo per le righe dispari (ad esempio, un grigio leggermente più scuro) */
    }

    .card-body {
        padding: 5px;
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
                        <p style="margin-left: 20px">Impostazioni di stampa consigliate con Chrome: orientamento
                            verticale, margini <b>nessuno</b> togliere la spunta in "Grafica in background".</p>
                    </div>

                </div>
                <div class="card-body daStampare">
                    <!-- Header del protocollo -->

                    <?php foreach ($risultato as $protocollo): ?>
                        <div class="card" style="margin-bottom: 20px">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-1"><b><i>Protocollo</i></b></div>
                                    <div class="col-md-1"><b><i>Anno</i></b></div>
                                    <div class="col-md-1"><b><i>Esenzione</i></b></div>
                                    <div class="col-md-3"><b><i>Codici Fiscali</i></b></div>
                                    <div class="col-md-1"><b><i>Esito</i></b></div>
                                    <div class="col-md-4"><b><i>Descrizione</i></b></div>
                                    <div class="col-md-1"><b><i>Importo</i></b></div>
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
                                            <div class="card-header"><b>Dettaglio: <?= count($protocollo->ricettas) ?> ricette per un totale di <?= $protocollo->getNumTotalePrestazioni() ?> prestazioni</b></div>
                                            <div class="card-body">
                                                <!-- show header row -->
                                                <div class="ricette-container">
                                                    <?php foreach ($protocollo->ricettas as $index2 => $ricetta): ?>
                                                        <div class="card ricetta-row <?= ($index2 % 2 == 0) ? 'ricetta-even-row' : 'ricetta-odd-row' ?>">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-2" style="text-align: center"><b>n° </b><span
                                                                                class="badge bg-info text-dark"><?= htmlspecialchars($ricetta->numero) ?></span>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <b><?= strtoupper(substr(htmlspecialchars($ricetta->tipologia), 0, 4) . ".") ?></b>
                                                                    </div>
                                                                    <div class="col-md-2"><?= htmlspecialchars($ricetta->struttura) ?></div>
                                                                    <div class="col-md-2"><?= htmlspecialchars($ricetta->ubicazione) ?></div>
                                                                    <div class="col-md-3"><?= "<b>Data Prescrizione:</b> " . htmlspecialchars($ricetta->data_prescrizione) . "<br /><b>Data spedizione:</b> " .
                                                                        htmlspecialchars($ricetta->data_spedizione) ?></div>
                                                                    <div class="col-md-1">
                                                                        <b>Tot. Prest: </b><?= count($ricetta->prestaziones) ?>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <b>Tot.</b> <?= htmlspecialchars($ricetta->ticket) ?>
                                                                        €
                                                                    </div>
                                                                    <?php if ($model->mostraDettagli) : ?>
                                                                        <div class="col-md-12" style="margin-top:15px;">
                                                                            <div class="row">
                                                                                <?php foreach ($ricetta->prestaziones as $prestazione): ?>
                                                                                    <div class="col-md-2" style="text-align: center">
                                                                                        <b><?= htmlspecialchars($prestazione->data_erogazione) ?></b>
                                                                                    </div>
                                                                                    <div class="col-md-2"><b>cod.
                                                                                            :</b> <?= htmlspecialchars($prestazione->codice_prodotto) ?>
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <?= htmlspecialchars($prestazione->descrizione) ?>
                                                                                    </div>
                                                                                    <div class="col-md-1"><b>Qta: </b> <?= htmlspecialchars($prestazione->quantita) ?>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <b>Tariffa:</b> <?= htmlspecialchars($prestazione->tariffa) ?>
                                                                                        €
                                                                                    </div>
                                                                                <?php endforeach; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
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