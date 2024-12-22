<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Queue;

class RabbitMQPublisher
{
    public function __construct(
        private string $exchange,
        private string $queue,
        private string $key
    ){}

    public function publish(array $data): void
    {
        $connection = Queue::connection('rabbitmq')->getConnection();
        $channel = $connection->channel();

        $channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $arguments = ['x-queue-mode' => ['S', 'lazy']];
        $channel->queue_declare($this->queue, false, true, false, false, false, $arguments);
        $channel->queue_bind($this->queue, $this->exchange, $this->key);

        $msg = new AMQPMessage(json_encode($data), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $channel->basic_publish($msg, $this->exchange, $this->key);
        $channel->close();
    }
}
