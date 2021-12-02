<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use humhub\modules\ui\view\helpers\ThemeHelper;

class SettingsModel extends Model
{
    public $guids = [];

    public $showfiles = true;

    public function init()
    {
        $module = Yii::$app->getModule('daveandpeterspaperscheduleinput');
        $this->guids = json_decode($module->settings->get('assignedusersforrevision', "[]"));
        $this->showfiles = $module->settings->get('showfilesintask', true);
    }

    public static function instantiate()
    {
        return new self;
    }

    public function rules()
    {
        return [
            ['guids', 'safe'],
            ['showfiles', 'safe']
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'guids' => Yii::t('DaveandpeterspaperscheduleinputModule.config', "Users"),
            'showfiles' => Yii::t('DaveandpeterspaperscheduleinputModule.config', "Show files"),
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $module = Yii::$app->getModule('daveandpeterspaperscheduleinput');
        $module->settings->set('assignedusersforrevision', json_encode($this->guids));
        $module->settings->set('showfilesintask', $this->showfiles);

        if (!$this->showfiles) {
            foreach (ThemeHelper::getThemes() as $theme) {
                if (!file_exists($theme->basePath . '/views/tasks/widgets/lists/views')) {
                    mkdir($theme->basePath . '/views/tasks/widgets/lists/views', 0777, true);
                }

                $source =   Yii::$app->getModule('daveandpeterspaperscheduleinput')->basePath . "/resources/taskDetails.php";
                $destination = $theme->basePath . "/views/tasks/widgets/lists/views/taskDetails.php";
                copy($source, $destination);
            }
        } else {
            foreach (ThemeHelper::getThemes() as $theme) {
                unlink($theme->basePath . "/views/tasks/widgets/lists/views/taskDetails.php");
            }
        }
        return true;
    }

    public function getSelectionString()
    {
        if (empty($this->guids)) {
            return "";
        } else {
            return Html::encode(implode(',', $this->guids));
        }
    }
}
