<?php

namespace app\controllers;

use app\services\HighestPriorityService;
use app\services\LowPriorityService;
use app\services\MiddlePriorityService;
use Yii;
use yii\rest\Controller;

class AsyncController extends Controller
{

    public function actionIndex()
    {
        /**
         * @todo выполнить задачи параллельно
         */

        $searchServices = [
            HighestPriorityService::class,
            MiddlePriorityService::class,
            LowPriorityService::class,
        ];

        foreach ($searchServices as $service) {
            Yii::$app->async->run([$service, 'handle']);
        }

        return Yii::$app->async->wait();
    }

}
