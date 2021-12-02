<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2020 Vitt IT-Services
 * 
 */

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput\models;

use Yii;
use \yii\base\Model;
use DateTime;
use DateTimeZone;
use humhub\components\ActiveRecord;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers\DbDateValidatorPatched;

class PaperInputModel extends Model
{
    /**
     * @var string the title of the task
     */
    public $title = "";

    /**
     * @var string the authors
     */
    public $authors = '';

    /**
     * @var string the venue
     */
    public $venue = '';

    /**
     * @var string the description of the task
     */
    public $description = "";

    /**
     * @var string the start date
     */
    public $startdate = "";

    /**
     * @var string the end date
     */
    public $enddate = "";

    /**
     * @var file the paperfile
     */
    public $files = [];

    /**
     * @var string message
     */
    public $message = "";

    /**
     * @var string success
     */
    public $success = true;

    public function getModelName()
    {
        return \yii\helpers\StringHelper::basename(__CLASS__);
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['title',  'string'], 'required', 'message' => 'Please write down the title of you paper!'],
            [['authors',  'string'], 'required', 'message' => 'Please type in the authors!'],
            [['venue',  'string'], 'required', 'message' => 'Please specify where you want to submit (e.g. CHI 2021, Yokohama)!'],
            [['description',  'string'], 'required', 'message' => 'Please type in some type of description (e.g. the abstract)!'],
            [['startdate'], DbDateValidatorPatched::class, 'format' => "d.m.y"],
            [['enddate'], DbDateValidatorPatched::class, 'format' => "d.m.y"],
            [['enddate'], 'validateEndTime'],
            [['enddate'], 'required'],
            [['timeZone'], 'in', 'range' => DateTimeZone::listIdentifiers()],
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, docx', 'message' => 'Please select the file as pdf-document!']
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('DaveandpeterspaperscheduleinputModule.base', "Title"),
            'authors' => Yii::t('DaveandpeterspaperscheduleinputModule.base', "Authors"),
            'venue' => Yii::t('DaveandpeterspaperscheduleinputModule.base', "Venue"),
            'startdate' => Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Begin'),
            'enddate' => Yii::t('DaveandpeterspaperscheduleinputModule.base', 'By when do you need feedback?'),
            'timeZone' => Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Time Zone'),
            'description' => Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Description (Abstract)'),
        ];
    }

    public function upload()
    {
        // if ($this->validate()) {
        foreach ($this->files as $file) {
            // $file->baseName
            $file->saveAs('uploads/' . bin2hex(random_bytes(20)) . '.' . $file->extension);
        }
        return true;
        // } else {
        // return false;
        // }
    }

    /**
     * Validator for the endtime field.
     * Execute this after DbDateValidator
     *
     * @param string $attribute attribute name
     * @param [] $params parameters
     * @throws \Exception
     */
    public function validateEndTime($attribute, $params)
    {
        if (new DateTime($this->startdate) >= new DateTime($this->enddate)) {
            $this->addError($attribute, 'End time must be after start time!');
        }
    }
}
