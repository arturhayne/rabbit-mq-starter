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
$channel->queue_declare('task_queue', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

/**
 * Method the will receive and treat the messages
 */
$callback = function ($msg) {
  echo ' [x] Received ', $msg->body, "\n";
  sleep(substr_count($msg->body, '.'));
  echo " [x] Done\n";
  $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

/**
 * not to give more than one message to a worker at a time
 */
$channel->basic_qos(null, 1, null);

/**
 * Add this callback to the queue
 * 2nd boolean = ack (true = no ack)
 */
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

/**
 * Keep the method listening the queue for indeterminate time
 */
while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();