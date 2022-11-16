<?php

namespace app\services;

abstract class PriorityServiceAbstract
{

    /**
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $rand = mt_rand(3, 15);
        sleep($rand);

//        if ($rand % 2 == 0) {
//            throw new \Exception('oops, fail((');
//        }

        return $rand;
    }
}