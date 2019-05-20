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
 * Instead to send to a queue send to a exchange
 * exchange types: direct, topic, headers and fanout
 */
$channel->exchange_declare('logs', 'fanout', false, false, false);

$data = implode(' ', array_slice($argv, 1));
if (empty($data)) {
    $data = "info:Hello World!";
}

$msg = new AMQPMessage($data);

/**
 * Send to the queue
 * 2nd parameter exchange name
 * 3rd queue - in this case default (nameless)
 */
$channel->basic_publish($msg, 'logs');

echo ' [x] Sent ', $data, "\n";

/**
 * Close connection
 */
$channel->close();
$connection->close();