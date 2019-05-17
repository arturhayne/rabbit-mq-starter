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
$channel->queue_declare('my-queue', false, false, false, false);

/**
 * Create the message
 */
$msg = new AMQPMessage('Hello!');

/**
 * Send to the queue
 */
$channel->basic_publish($msg, '', 'hello');

/**
 * Close connection
 */
$channel->close();
$connection->close();