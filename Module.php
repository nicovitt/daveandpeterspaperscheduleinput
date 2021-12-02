<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput;

use Yii;
use yii\helpers\Url;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\space\models\Space;

class Module extends \humhub\modules\content\components\ContentContainerModule
{

    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/daveandpeterspaperscheduleinput/admin']);
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        // Cleanup all module data, don't remove the parent::disable()!!!
        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        // Clean up space related data, don't remove the parent::disable()!!!
        parent::disableContentContainer($container);
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Paper Input');
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        return Yii::t('DaveandpeterspaperscheduleinputModule.base', 'This Module allows to send new tasks to the task list (for paper scheduling).');
    }
}
