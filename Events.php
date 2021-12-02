<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput;

use Yii;
use yii\base\Event;
use yii\helpers\Url;
use humhub\modules\tasks\models\Task;
use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\Menu;
use humhub\modules\file\widgets\ShowFiles;
use humhub\modules\content\widgets\WallEntryAddons;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers\Url as urlManager;

class Events
{
    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => 'Paper Input',
            'url' => Url::to(['/daveandpeterspaperscheduleinput/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-book"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'daveandpeterspaperscheduleinput' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 99999,
        ]);
    }

    /**
     * Defines what to do when the space menu is initialized.
     *
     * @param $event
     */
    public static function onSpaceMenuInit(Event $event)
    {
        if ($event->sender->space !== null && $event->sender->space->isModuleEnabled('daveandpeterspaperscheduleinput')) {
            /** @var Menu $sender */
            $sender = $event->sender;
            if (!($sender instanceof Menu)) {
                throw new \LogicException();
            }

            /** @var Space $space */
            $space = $sender->space;
            if (!($space instanceof Space)) {
                throw new \LogicException();
            }
            $event->sender->addItem([
                'label' => 'Paper Input',
                'group' => 'modules',
                'icon' => '<i class="fa fa-book"></i>',
                'url' => $space->createUrl(urlManager::toSpaceFromMenu()),
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'daveandpeterspaperscheduleinput'),
            ]);
        }
    }

    /**
     * Defines what to do when a task is displayed on the wall.
     *
     * @param $event
     */
    public static function onCreateWallEntryTaskView($event)
    {
        if (get_class($event->config["object"]) == Task::class) {
            $task = Task::findOne($event->config["object"]["state"]->task->attributes["id"]);
            $module = Yii::$app->getModule('daveandpeterspaperscheduleinput');
            $showfiles = $module->settings->get('showfilesintask', true);
            if (!$showfiles && !$task->isTaskAssigned() && !$task->isTaskResponsible()) {
                if (array_key_exists("widgetOptions", $event->config)) {
                    $event->config['widgetOptions'] = array_merge($event->config['widgetOptions'], [ShowFiles::class => ['active' => false]]);
                } else {
                    $event->config['widgetOptions'] = [ShowFiles::class => ['active' => false]];
                }
            }
        }
        return $event;
    }

    public static function onAssignNewPeople($event)
    {
        $module = Yii::$app->getModule('daveandpeterspaperscheduleinput');
        $module->settings->set('showfilesintask', true);
    }
}
