<?php

namespace app\controllers;

use app\models\enums\TipologiaEsente;
use app\models\Protocollo;
use app\models\VerificaForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

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
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index'],
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
                'fixedVerifyCode' => YII_ENV_TEST ? 'vai' : null,
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

        $tuttiGliAnni = Protocollo::getAnniProtocolli();

        if ($this->request->post()) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $tutti = [];
                $risultato = Protocollo::find();
                if ($model->protocollo)
                    $risultato = $risultato->andWhere(['protocollo' => $model->protocollo]);
                if ($model->codice_fiscale) {
                    switch ($model->tipo_cf) {
                        case TipologiaEsente::ASSITITO_ESENTE:
                            $risultato = $risultato->andWhere(['cf_esente' => $model->codice_fiscale]);
                            break;
                        case TipologiaEsente::ASSISTITO_DICHIARANTE:
                            $risultato = $risultato->andWhere(['cf_dichiarante' => $model->codice_fiscale]);
                            break;
                        case TipologiaEsente::ASSISTITO_TITOLARE:
                            $risultato = $risultato->andWhere(['cf_titolare' => $model->codice_fiscale]);
                            break;
                        case TipologiaEsente::TUTTI:
                            $risultato = $risultato->andWhere(['or',
                                ['cf_esente' => $model->codice_fiscale],
                                ['cf_dichiarante' => $model->codice_fiscale],
                                ['cf_titolare' => $model->codice_fiscale],
                            ]);
                            break;
                    }
                }
                $risultato = $risultato->andWhere(['in', 'anno', $model->anni])
                    ->orderBy(['anno' => SORT_DESC])->all();

                return $this->render('verifica', [
                    'model' => $model, // Passa il modello alla vista
                    'risultato' => $risultato,
                ]);
            }
        }
        else {
            $model->anni = array_keys($tuttiGliAnni);
            $model->tipo_cf = TipologiaEsente::TUTTI;
        }

        return $this->render('index', [
            'model' => $model,
            'anni' => $tuttiGliAnni,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
