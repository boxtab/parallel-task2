<?php

namespace app\commands;

use mamatveev\yii2rabbitmq\RabbitComponent;
use yii\console\Controller;

class RpcController extends Controller
{
    public function actionRabbitServer()
    {
        /** @var RabbitComponent $rpc */
        $rpc = \Yii::$app->rpc;

//        $rpcServer = $rpc->initServer('exchange-name');
//
//        $callback = function($msg){
//            $result = "msg from client: " . print_r($msg, true);
//            echo $result."\n";
//            sleep(1);
//            return $result;
//        };
//
//        $rpcServer->setCallback($callback);
//        $rpcServer->start();
    }

    public function actionRabbitClient()
    {
        /** @var RabbitComponent $rpc */
        $rpc = \Yii::$app->rpc;

        // init a client
        $rpcClient = $rpc->initClient('exchange-name');

        // send a messages to exchange
        for ($i = 0; $i < 5; $i++) {
            $rpcClient->addRequest("message number {$i}, getReplies() test");
        }

        // get all responses from rpc server
        print_r($rpcClient->getReplies());

        for ($i = 0; $i < 5; $i++) {
            $rpcClient->addRequest("message number {$i}, getReplies() with callback");
        }

        // use callback for responses
        $rpcClient->getReplies(function($msg) {
            echo "server reply callback... response is {$msg}\n";
        });

        for ($i = 0; $i < 5; $i++) {
            $rpcClient->addRequest("message number {$i}, waitExecution() test");
        }

        // wait messages execution without getting any response
        $rpcClient->waitExecution();

        // any message object will be serialized
        $message = new \stdClass();
        $message->some_property = 2;
        $rpcClient->addRequest($message);
    }
}
