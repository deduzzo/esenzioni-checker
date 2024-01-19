<?php

/** @var yii\web\View $this */

/** @var \app\models\VerificaForm $model */

/** @var array $anni */

use app\models\enums\TipologiaEsente;
use app\models\Protocollo;
use kartik\select2\Select2;
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

    .captcha-center {
        display: flex;
        justify-content: center; /* Centra orizzontalmente */
        align-items: center; /* Centra verticalmente */
        margin-bottom: 20px; /* Aggiungi un margine inferiore, se necessario */
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
        <p class="lead">Inserire protocollo e codice fiscale del titolare esenzione contestata per verificare i
            protocolli collegati</p>
        <div class="form-center">
            <?php

            $form = ActiveForm::begin([
                'enableClientValidation' => false,
            ]);


            echo $form->field($model, 'protocollo')->textInput([
                'class' => 'form-control mb-2 mr-sm-2 bold-input',
                'placeholder' => 'Protocollo',
                'style' => "width: 500px;",
                'maxlength' => 9,
                'oninput' => "this.value = this.value.replace(/[^0-9]/g, '')",
            ])->label(false);

            echo $form->field($model, 'codice_fiscale')->textInput([
                'class' => 'form-control mb-2 mr-sm-2 bold-input',
                'placeholder' => 'Codice Fiscale',
                'style' => "width: 500px;",
                'maxlength' => 16,
                'oninput' => "this.value = this.value.toUpperCase()",
            ])->label(false);

            echo $form->field($model, 'tipo_cf')->radioList(TipologiaEsente::$list, [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $checkedAttribute = $checked ? 'checked' : '';
                    return "<div class='form-check d-inline-block mx-2'>
                    <input type='radio' class='form-check-input' id='tipo_cf_{$index}' name='{$name}' value='{$value}' {$checkedAttribute}>
                    <label class='form-check-label' for='tipo_cf_{$index}'>{$label}</label>
                </div>";
                }
            ])->label(false);

            ?>


            <div class="form-floating" style="max-width: 500px">
                <?= Select2::widget([
                    'model' => $model,
                    'attribute' => 'anni',
                    'data' => $anni,
                    'options' => ['placeholder' => 'Seleziona un anno...', 'multiple' => true],
                    'pluginOptions' => ['allowClear' => true],
                ]);
                ?>
                <label class="form-label">Anni protocollo</label>
            </div>


            <?php
            echo "<div class='captcha-center'>";
                echo $form->field($model, 'captcha')->widget(Captcha::class, [
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Inserisci il codice soprastante',
                        'style' => "width: 400px;",
                    ]])->label(false);
            echo "</div>";
            echo Html::submitButton('Verifica', ['class' => 'btn btn-lg mb-2 btn-success']);

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
