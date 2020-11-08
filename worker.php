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

$nameQueue = getenv('SQS_QUEUE');

// Get queue name from cli if has
if (count($argv) === 2) {
    $parts = explode('=', $argv[1]);
    if (count($parts) === 2 && trim($parts[0]) === '--queue') {
        $nameQueue = trim($parts[1]);
    }
}

// Instantiate queue
$queue = new AwsQueue($nameQueue, new SqsClient($credentials));

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
