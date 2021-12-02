<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\VittITServices\humhub\modules\daveandpeterspaperscheduleinput\assets\Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig("daveandpeterspaperscheduleinput", [
    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
    'text' => [
        'hello' => Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Hi there {name}!', ["name" => $displayName])
    ]
])

?>

<div class="panel-heading"><strong>Daveandpeterspaperscheduleinput</strong> <?= Yii::t('DaveandpeterspaperscheduleinputModule.base', 'overview') ?></div>

<div class="panel-body">
    <p><?= Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Hello World!') ?></p>

    <?=  Button::primary(Yii::t('DaveandpeterspaperscheduleinputModule.base', 'Say Hello!'))->action("daveandpeterspaperscheduleinput.hello")->loader(false); ?></div>
