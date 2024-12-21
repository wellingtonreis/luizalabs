<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Queue;

class RabbitMQConsumer
{
    private const BATCH_SIZE = 100;
    private array $buffer = [];

    public function consume(callable $callback, callable $onComplete = null): void
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

        $channel->basic_qos(null, self::BATCH_SIZE, null);
        $channel->basic_consume($queue, '', false, true, false, false, function (AMQPMessage $msg) use ($callback) {
            $data = json_decode($msg->getBody(), true);
            $this->buffer[] = $data;

        
            if (count($this->buffer) >= self::BATCH_SIZE) {
                $this->processBatch($callback);
            }
        });

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        if (!empty($this->buffer)) {
            $this->processBatch($callback);
        }

        if ($onComplete) {
            $onComplete();
        }

        $channel->close();
    }

    private function processBatch(callable $callback): void
    {
        $callback($this->buffer);
        $this->buffer = [];
    }
}
