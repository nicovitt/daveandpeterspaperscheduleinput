<?php

use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\assets\Assets;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers\Url as urlManager;
use yii\bootstrap\ActiveForm;
use humhub\modules\ui\form\widgets\DatePicker;

Assets::register($this);

if (!isset($model)) {
    $model = new VittITServices\humhub\modules\daveandpeterspaperscheduleinput\models\PaperInputModel();
}

$formconfig = [
    // 'action' => urlManager::toSubmitPaper($baseurl),
    'options' => ['enctype' => 'multipart/form-data',  'method' => 'post'],
];
?>

<?php if (strlen($model->message) > 0) {
?>
    <div class="alert alert-info" role="alert">
        <?= $model->message ?>
    </div>
<?php
}
?>

<div class="panel panel-default">
    <div class="panel-heading">The Paper Input Machine</div>
    <div class="panel-body">
        <p>Please insert your papers here to be scheduled by Peter and Dave! Your input will be posted in the tasks list and Peter and Dave can schedule it. You can check on the progess going to the Tasks-Section on the left menu.</p>

        <?php $form = ActiveForm::begin($formconfig); ?>
        <?= \yii\helpers\Html::csrfMetaTags() ?>
        <?= $form->field($model, 'title'); ?>
        <?= $form->field($model, 'authors'); ?>
        <?= $form->field($model, 'venue'); ?>
        <?= $form->field($model, 'description')->textarea(['rows' => '6']); ?>
        <div class="row">
            <div class="col-md-6 dateField">
                <?= $form->field($model, 'enddate')->widget(DatePicker::class, ['dateFormat' => "dd.MM.yyyy", 'clientOptions' => [], 'options' => ['class' => 'form-control',  'autocomplete' => "off"]]) ?>
            </div>
        </div>

        <?php
        $upload = \humhub\modules\file\widgets\Upload::withName();
        $upload->reset($model->success);
        ?>
        <?= $upload->button([
            'max' => 2,
            'dropZone' => '#paper-drop-zone',
            'buttonOptions' => ['style' => 'position: relative;overflow: hidden;'],
            'model' => $model,
        ]); ?>
        <?= $upload->progress(); ?>
        <?= $upload->preview(['options' => ['style' => 'margin:10px']]);  ?>
        <div id="paper-drop-zone" class="alert alert-warn">
            <p class="blinker">Drop your paper-file right here</p>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <?php ActiveForm::end(); ?>

    </div>
</div>