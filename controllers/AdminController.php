<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput\controllers;

use Yii;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\admin\components\Controller;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\models\SettingsModel;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers\Url as urlManager;

class AdminController extends Controller
{

    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can(ManageSettings::class)) {
            $model = new SettingsModel();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->view->saved();
            }

            return $this->render('index', [
                'model' => $model
            ]);
        } else {
            $this->redirect("/");
        }
    }
}
