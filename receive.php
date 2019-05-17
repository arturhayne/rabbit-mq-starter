<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;


/**
 * Start connection
 */
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

/**
 * What queue it will be used
 */
$channel->queue_declare('my-queue', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

/**
 * Method the will receive and treat the messages
 */
$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
};

/**
 * Add this callback to the queue
 */
$channel->basic_consume('my-queue', '', false, true, false, false, $callback);

/**
 * Keep the method listening the queue for indeterminate time
 */
while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();