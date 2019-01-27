<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\RegistrForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;

class UserController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['profile_edit_own'],
                        'actions' => ['profile'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ]
                ],
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
        return $this->render('index');
    }

    public function actionProfile()
    {
        /** @var User $model */
        $model = Yii::$app->user->identity;

        if (Yii::$app->request->isPost) {
            $account = Yii::$app->request->post('account');
            $model->upAccount($account);
            Yii::$app->session->addFlash('success', Yii::t('app', 'Success!'));
        }
        return $this->render('edit', compact('model'));
    }
}
