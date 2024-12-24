<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Queue;

class RabbitMQConsumer
{
    private const BATCH_SIZE = 1000;
    private array $buffer = [];

    public function __construct(
        private string $exchange,
        private string $queue,
        private string $key
    ){}

    public function consume(callable $callback, callable $onComplete = null): void
    {
        $connection = Queue::connection('rabbitmq')->getConnection();
        $channel = $connection->channel();

        $channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $arguments = ['x-queue-mode' => ['S', 'lazy']];
        $channel->queue_declare($this->queue, false, true, false, false, false, $arguments);
        $channel->queue_bind($this->queue, $this->exchange, $this->key);

        $channel->basic_qos(0, self::BATCH_SIZE, false);
        $channel->basic_consume($this->queue, '', false, true, false, false, function (AMQPMessage $msg) use ($callback) {
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
