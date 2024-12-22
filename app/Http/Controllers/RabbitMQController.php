<?php

namespace App\Http\Controllers;

use App\Repositories\TransferFundsRepository;
use App\Src\UseCases\Dto\TransferFunds\TransferFundsDto;
use App\Src\UseCases\TransferFunds;

use App\Services\RabbitMQ\RabbitMQConsumer;
use App\Services\RabbitMQ\RabbitMQPublisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class RabbitMQController
{
    public function __construct(private TransferFunds $transferFunds){}

    public function send(): JsonResponse
    {
        $pid = pcntl_fork();
        if ($pid === -1) {
            return response()->json(['error' => 'Erro ao iniciar consumidor'], 500);
        } elseif ($pid === 0) {
            $publisher = new RabbitMQPublisher(
                env('RABBITMQ_EXCHANGE'),
                env('RABBITMQ_QUEUE'),
                env('RABBITMQ_QUEUE')
            );
            $transferFundsRepository = new TransferFundsRepository();
            $transferFundsRepository->bach(
                1000, 
                function ($transfers) use ($publisher) {
                foreach ($transfers as $transfer) {
                    $data = $transfer->toArray();
                    $publisher->publish($data);
                }
            });
            exit(0);
        }

        return response()->json(['success' => 'Mensagens enviadas com sucesso!'], 200);
    }

    public function consume(): JsonResponse
    {
        $consumer = new RabbitMQConsumer(
            env('RABBITMQ_EXCHANGE'),
            env('RABBITMQ_QUEUE'),
            env('RABBITMQ_QUEUE')
        );
        $pid = pcntl_fork();
        if ($pid === -1) {
            return response()->json(['error' => 'Erro ao iniciar consumidor'], 500);
        } elseif ($pid === 0) {
            $consumer->consume(function (array $data) {
                Log::info('Consumindo mensagens...');
                foreach ($data as $message) {

                    $dto = new TransferFundsDto(
                        $message['number_account_origin'],
                        $message['number_account_destination'],
                        $message['amount'],
                        $message['type'],
                        $message['description']
                    );
                    $this->transferFunds->execute($dto);
                }
            }, function () {
                Log::info('Consumidor finalizado');
            });
            exit(0);
        }

        return response()->json(['message' => 'Consumidor iniciado em background'], 200);
    }
}