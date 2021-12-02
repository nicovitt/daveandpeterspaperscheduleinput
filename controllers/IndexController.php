<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput\controllers;

use humhub\components\Controller;

class IndexController extends Controller
{

    public $subLayout = "@daveandpeterspaperscheduleinput/views/layouts/default";

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}

