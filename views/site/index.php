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
    .bold-input::placeholder { /* Stile per il placeholder */
        font-weight: normal;
    }

    .bold-input { /* Stile per il testo inserito */
        font-weight: bold;
    }
</style>

<div class="site-index">

    <div class="text-center bg-transparent mt-5 mb-5">
        <img src="/images/asp-messina.jpg" alt="ASP Messina" class="img-fluid rounded mx-auto d-block">
        <h1 class="display-4">Verifica Esenzioni</h1>

        <p class="lead">Inserire protocollo e codice fiscale del titolare esenzione contestata per verificare i protocolli collegati</p>
        <div class="form-center">

            <?php
            $form = ActiveForm::begin([
                'action' => ['site/verifica'],
                'method' => 'get',
            ]);

            echo $form->field($model, 'protocollo')->textInput([
                'class' => 'form-control mb-2 mr-sm-2 bold-input',
                'placeholder' => 'Protocollo',
                'style' => "width: 400px;",
                'maxlength' => 9,
                'oninput' => "this.value = this.value.replace(/[^0-9]/g, '')",
            ])->label(false);

            echo $form->field($model, 'codice_fiscale')->textInput([
                'class' => 'form-control mb-2 mr-sm-2 bold-input',
                'placeholder' => 'Codice Fiscale',
                'style' => "width: 400px;",
                'maxlength' => 16,
                'oninput' => "this.value = this.value.toUpperCase()",
            ])->label(false);

            echo $form->field($model, 'tipo_cf')->radioList([
                'titolare' => 'Titolare Esenzione',
                'assistito' => 'Assistito'
            ], [
                'item' => function ($index, $label, $name, $checked, $value) {
                    // Imposta 'checked' per la prima opzione (indice 0)
                    $checkedAttr = ($index === 0) ? 'checked' : '';
                    $return = '<div class="form-check form-check-inline">';
                    $return .= '<input class="form-check-input" type="radio" name="' . $name . '" value="' . $value . '" ' . $checkedAttr . ' id="customRadio' . $index . '">';
                    $return .= '<label class="form-check-label" for="customRadio' . $index . '">';
                    $return .= ucwords($label);
                    $return .= '</label>';
                    $return .= '</div>';

                    return $return;
                }
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
