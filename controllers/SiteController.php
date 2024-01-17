<?php

namespace app\controllers;

use app\models\Protocollo;
use app\models\VerificaForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 5,  // Numero minimo di caratteri nel CAPTCHA
                'maxLength' => 7,  // Numero massimo di caratteri
                'padding' => 30,    // Spaziatura tra i caratteri
                'height' => 150,    // Altezza dell'immagine CAPTCHA
                'width' => 300,    // Larghezza dell'immagine CAPTCHA
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new VerificaForm();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionVerifica()
    {
        $model = new VerificaForm();

        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            $tutti = [];
            if ($model->tipo_cf == 'titolare')
                $risultato = Protocollo::find()->where([
                    'protocollo' => $model->protocollo,
                    'cf_titolare_esenzione' => $model->codice_fiscale
                ])->one();
            else
                $risultato = Protocollo::find()->where([
                    'protocollo' => $model->protocollo,
                    'cod_fiscale' => $model->codice_fiscale
                ])->one();

            if ($risultato) {
                if ($model->tipo_cf == 'titolare')
                    $tutti = Protocollo::find()->where([
                        'cf_titolare_esenzione' => $model->codice_fiscale
                    ])->orderBy(['anno' => SORT_DESC])->all();
                else
                    $tutti = Protocollo::find()->where([
                        'cod_fiscale' => $model->codice_fiscale
                    ])->orderBy(['anno' => SORT_DESC])->all();
            }

            return $this->render('verifica', [
                'model' => $model, // Passa il modello alla vista
                'risultato' => $risultato,
                'tutti' => $tutti
            ]);
        } else {
            // Gestisci il caso in cui i dati non sono validi o mancanti
            return $this->render('index', ['model' => $model]);
        }
    }
}
