<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2020 Vitt IT-Services
 * 
 */
/* @var $this yii\web\View */
/* @var $model VittITServices\humhub\modules\daveandpeterspaperscheduleinput\models\SettingsModel */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers\Url as urlManager;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\models\SettingsModel;
?>

<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Dave-And-Peters-Paper-Schedule-Input</strong></div>

        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <h4>
                <?= Yii::t('DaveandpeterspaperscheduleinputModule.config', 'Which users should be assigned to the tasks?'); ?>
            </h4>
            <div class="help-block">
                <?= Yii::t('DaveandpeterspaperscheduleinputModule.config', 'These users then have to review the papers.') ?>
            </div>

            <?= humhub\modules\user\widgets\UserPickerField::widget([
                'model' => $model,
                'attribute' => 'guids',
                'form' => $form
            ]); ?>

            <h4>
                <?= Yii::t('DaveandpeterspaperscheduleinputModule.config', 'Should the files be shown in task and stream?'); ?>
            </h4>
            <div class="help-block">
                <?= Yii::t('DaveandpeterspaperscheduleinputModule.config', 'Sensitive data could be not displayed. If not checked the files will be hidden except for responsible or assigned users for the task.') ?>
            </div>

            <?= $form->field($model, 'showfiles')->checkbox(); ?>

            <br /><br />

            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>