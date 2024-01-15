<?php

/** @var yii\web\View $this */
/** @var \app\models\VerificaForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = Yii::$app->name;
?>
<style>
    .form-center {
        display: flex;
        justify-content: center; /* Centers horizontally */
        align-items: center; /* Centers vertically */
        flex-direction: column; /* Stack elements vertically */
    }
</style>

<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <img src="/images/asp-messina.jpg" alt="ASP Messina" class="img-fluid rounded mx-auto d-block">
        <h1 class="display-4">Verifica Esenzioni</h1>

        <p class="lead">Inserire protocollo e codice fiscale</p>
        <div class="form-center">
            <?php
            $form = ActiveForm::begin([
                'action' => ['site/verifica'],
                'method' => 'get',
            ]);

            echo $form->field($model, 'protocollo')->textInput([
                'class' => 'form-control mb-2 mr-sm-2',
                'placeholder' => 'Protocollo',
                'style' => "width: 400px;",
                'maxlength' => 9,
            ])->label(false);

            echo $form->field($model, 'codice_fiscale')->textInput([
                'class' => 'form-control mb-2 mr-sm-2',
                'placeholder' => 'Codice Fiscale',
                'style' => "width: 400px;",
                'maxlength' => 16,
            ])->label(false);

            echo $form->field($model, 'captcha')->widget(Captcha::class, [
                'template' => '<div class="row" style="max-width: 400px"><div class="col-lg-12">{image}</div><div class="col-lg-12">{input}</div></div>',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'Inserisci il codice soprastante'
                ]])->label(false);

            echo Html::submitButton('Verifica', ['class' => 'btn btn-lg mb-2 btn-success']);

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
