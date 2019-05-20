<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;


/**
 * Start connection
 */
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

/**
 * Instead to send to a queue send to a exchange
 */
$channel->exchange_declare('logs', 'fanout', false, false, false);

list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

$channel->queue_bind($queue_name, 'logs');

echo ' [*] Waiting for logs messages. To exit press CTRL+C', "\n";

/**
 * Method the will receive and treat the messages
 */
$callback = function ($msg) {
  echo ' [x] Received ', $msg->body, "\n";
};

/**
 * Add this callback to the queue
 * 2nd boolean = ack (true = no ack)
 */
$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

/**
 * Keep the method listening the queue for indeterminate time
 */
while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();