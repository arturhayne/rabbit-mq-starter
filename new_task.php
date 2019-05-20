<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Starts Connection
 */
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

/**
 * What queue it will send the letter
 */
$channel->queue_declare('task_queue', false, true, false, false);

$data = implode(' ', array_slice($argv, 1));
if (empty($data)) {
    $data = "Hello World!";
}

/**
 * delivery-mode persistent to persist message on disk for short time
 */
$msg = new AMQPMessage(
    $data,
    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
);

/**
 * Send to the queue
 */
$channel->basic_publish($msg, '', 'task_queue');

echo ' [x] Sent ', $data, "\n";

/**
 * Close connection
 */
$channel->close();
$connection->close();