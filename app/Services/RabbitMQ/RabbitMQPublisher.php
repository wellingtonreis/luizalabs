<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Queue;

class RabbitMQPublisher
{
    public function publish(array $data): void
    {
        $connection = Queue::connection('rabbitmq')->getConnection();
        $channel = $connection->channel();

        $exchange = env('RABBITMQ_EXCHANGE');
        $queue = env('RABBITMQ_QUEUE');
        $routingKey = env('RABBITMQ_QUEUE');

        $channel->exchange_declare($exchange, 'direct', false, true, false);
        $arguments = ['x-queue-mode' => ['S', 'lazy']];
        $channel->queue_declare($queue, false, true, false, false, false, $arguments);
        $channel->queue_bind($queue, $exchange, $routingKey);

        $msg = new AMQPMessage(json_encode($data), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $channel->basic_publish($msg, $exchange, $routingKey);
        $channel->close();
    }
}
