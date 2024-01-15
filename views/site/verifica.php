<?php
/** @var yii\web\View $this */
/** @var \app\models\Protocollo $risultato */
/** @var \app\models\Protocollo[] $tutti */
use yii\bootstrap5\Html;

$this->title = Yii::$app->name;
?>

<style>
    /* Stile per la tabella principale */
    .table-main th, .table-main td {
        font-weight: bold; /* Rende il testo in grassetto */
    }

    /* Stile per la sottotabella */
    .table-sub th, .table-sub td {
        font-size: smaller;
        font-weight: normal;

    }
</style>

<div class="site-index">
    <div class="card">
        <div class="card-header">
            <div class="card-toolbar">
                <h1>Risultato verifica</h1>
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
                    <?php foreach ($tutti as $protocollo): ?>
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
                                <table class="table table-light table-sub">
                                    <thead>
                                    <tr>
                                        <th>Numero</th>
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
                                            <td><?= $ricetta->ticket." €" ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
