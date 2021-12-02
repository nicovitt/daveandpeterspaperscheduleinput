<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers;

use Yii;

class APITaskCollection
{
    function __construct()
    {
        $this->Task = new APITask();
        $this->TaskForm = new APITaskForm();
    }

    public $Task;
    public $TaskForm;
}

class APITask
{
    function __construct()
    {
        array_push($this->responsibleUsers, Yii::$app->user->guid); //The author of the paper.
        $module = Yii::$app->getModule('daveandpeterspaperscheduleinput');
        $this->assignedUsers = json_decode($module->settings->get('assignedusersforrevision', "[]")); //The one who should have a look at the paper.
    }

    public $title = '';
    public $description = '';
    public $scheduling = 1;
    public $all_day = 1;
    public $assignedUsers = array();
    public $responsibleUsers = array();
    public $review = 1;
}

class APITaskForm
{
    function __construct()
    {
        $this->timeZone = date_default_timezone_get();
        $this->start_date = date("Y-m-d H:i:s"); //Y-m-d or d.m.y
        $this->start_time = Yii::$app->formatter->asTime("0:00", 'php:H:i A');
        $this->end_time = Yii::$app->formatter->asTime("11:59", 'php:H:i A');
        $this->timeZone = Yii::$app->formatter->timeZone;
    }

    public $is_public = 0;
    public $start_date = "";
    public $start_time = "";
    public $end_date = "";
    public $end_time = "";
    public $timeZone;
    public $newItems = array();
}
