<?php

use humhub\components\Widget;
use humhub\modules\space\widgets\Menu;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\content\widgets\WallEntryAddons;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\Events;

return [
	'id' => 'daveandpeterspaperscheduleinput',
	'class' => 'VittITServices\humhub\modules\daveandpeterspaperscheduleinput\Module',
	'namespace' => 'VittITServices\humhub\modules\daveandpeterspaperscheduleinput',
	'events' => [
		[
			'class' => AdminMenu::class,
			'event' => AdminMenu::EVENT_INIT,
			'callback' => [Events::class, 'onAdminMenuInit']
		],
		[
			'class' => Menu::class,
			'event' => Menu::EVENT_INIT,
			'callback' => [Events::class, 'onSpaceMenuInit'],
		],
		[
			'class' => WallEntryAddons::class,
			'event' => Widget::EVENT_CREATE,
			'callback' => [Events::class, 'onCreateWallEntryTaskView']
		],
		// [
		// 	'class' => 'humhub\modules\tasks\controllers\TaskController',
		// 	'event' => 'actionEdit',
		// 	'callback' => ['VittITServices\humhub\modules\daveandpeterspaperscheduleinput\Events', 'onAssignNewPeople']
		// ],
	],
];
