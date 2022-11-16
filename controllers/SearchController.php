<?php

namespace app\controllers;

use app\services\HighestPriorityService;
use app\services\LowPriorityService;
use app\services\MiddlePriorityService;
use Yii;
use yii\rest\Controller;

class SearchController extends Controller
{

    public function actionIndex()
    {
//        \Yii::error('something bad occurred');
        /**
         * @todo выполнить задачи параллельно
         */

        $searchServices = [
            new HighestPriorityService(),
            new MiddlePriorityService(),
            new LowPriorityService()
        ];

        foreach ($searchServices as $service) {
            return $service->handle();
        }
    }

}