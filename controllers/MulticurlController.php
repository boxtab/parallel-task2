<?php

namespace app\controllers;

use app\services\HighestPriorityService;
use app\services\LowPriorityService;
use app\services\MiddlePriorityService;
use yii\rest\Controller;

class MulticurlController extends Controller
{
    public function actionMulti()
    {
        $urls = [
            'http://parallel.local/index.php?r=multicurl/highest',
            'http://parallel.local/index.php?r=multicurl/middle',
            'http://parallel.local/index.php?r=multicurl/low',
        ];

        $results = [];

        $multi = curl_multi_init();
        $channels = array();

        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_multi_add_handle($multi, $ch);

            $channels[$url] = $ch;
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($multi, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($multi) == -1) {
                continue;
            }

            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        foreach ($channels as $channel) {
            $results[] = curl_multi_getcontent($channel);
            curl_multi_remove_handle($multi, $channel);
        }

        curl_multi_close($multi);

        return $results;
    }

    public function actionHighest()
    {
        $highestPriorityService = new HighestPriorityService();
        return $highestPriorityService->handle();
    }

    public function actionMiddle()
    {
        $middlePriorityService = new MiddlePriorityService();
        return $middlePriorityService->handle();
    }

    public function actionLow()
    {
        $lowPriorityService = new LowPriorityService();
        return $lowPriorityService->handle();
    }
}
