<?php

use App\Implementations\AwsQueue;
use Aws\Sqs\SqsClient;
use Dotenv\Dotenv;

require __DIR__.'/vendor/autoload.php';

// load env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// aws credentitals
$credentials = include './config/aws.php';

// Instantiate queue
$queue = new AwsQueue(getenv('SQS_QUEUE'), new SqsClient($credentials));

// Continuously poll queue for new messages and process them
while (true) {
    $message = $queue->receive();
    if ($message) {
        try {
            $message->process();
            $queue->delete($message);
        } catch (Exception $e) {
            $queue->release($message);
            echo $e->getMessage();
        }
    } else {
        // Wait 20 seconds if no jobs in queue to minimise requests to AWS API
        echo "Queue is currently being empty.\n";
        sleep(20);
    }
}
