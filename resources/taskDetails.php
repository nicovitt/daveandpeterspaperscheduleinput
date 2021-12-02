<?php
/* @var $this \humhub\components\View */

use humhub\modules\comment\widgets\Comments;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\content\widgets\WallEntryAddons;
use humhub\modules\tasks\helpers\TaskUrl;
use humhub\modules\tasks\widgets\ChangeStatusButton;
use humhub\modules\tasks\widgets\TaskInfoBox;
use humhub\modules\tasks\widgets\checklist\TaskChecklist;
use humhub\modules\tasks\widgets\TaskRoleInfoBox;
use humhub\widgets\Button;

/* @var $task \humhub\modules\tasks\models\Task */

$scheduleTextClass = '';

if (($task->schedule->isOverdue())) {
    $scheduleTextClass = 'colorDanger';
}
?>

<div class="task-list-task-details">

    <div class="task-list-task-details-body clearfix">


        <div class="task-list-task-infos">
            <?= TaskRoleInfoBox::widget(['task' => $task]) ?>
            <?= TaskInfoBox::widget([
                'title' => Yii::t('TasksModule.base', 'Scheduling'),
                'value' => $task->schedule->getFormattedDateTime(),
                'icon' => 'fa-clock-o',
                'textClass' => $scheduleTextClass
            ]) ?>

            <?php if ($task->schedule->canRequestExtension()) : ?>
                <div style="display:inline-block;vertical-align:bottom;">
                    <?= Button::primary()->icon('fa-calendar-plus-o')->xs()->cssClass('tt')->link(TaskUrl::requestExtension($task))->options(['title' => Yii::t('TasksModule.base', 'Request extension')]) ?>
                </div>
            <?php endif; ?>

            <?= ChangeStatusButton::widget(['task' => $task]) ?>
        </div>

        <?php if (!empty($task->description)) : ?>
            <div class="task-details-body">
                <?= RichText::output($task->description) ?>
            </div>
        <?php endif; ?>

        <?php if ($task->hasItems()) : ?>
            <div class="task-details-body">
                <?= TaskChecklist::widget(['task' => $task]) ?>
            </div>
        <?php endif; ?>

    </div>
    <?php
    // Check if the module is enabled in the space.
    $modulesinspace = humhub\modules\content\helpers\ContentContainerHelper::getCurrent("humhub\modules\space\models\Space")->moduleManager->getEnabled();
    if (in_array('daveandpeterspaperscheduleinput', $modulesinspace)) {

        // Check if files should be restricted.
        $module = Yii::$app->getModule('daveandpeterspaperscheduleinput');
        $showfiles = $module->settings->get('showfilesintask', true);

        // Check if user is assigned or responsible for the task.
        if ($showfiles || $task->isTaskAssigned() || $task->isTaskResponsible()) {
            echo WallEntryAddons::widget(['object' => $task]);
        } else {
            echo "<p>You are not allowed to see the file.</p>";
        }
    } else {
        echo WallEntryAddons::widget(['object' => $task]);
    }
    ?>
</div>