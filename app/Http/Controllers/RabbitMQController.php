<?php

namespace App\Http\Controllers;

use App\Repositories\TransferFundsRepository;
use App\Src\UseCases\Dto\TransferFunds\TransferFundsDto;
use App\Src\UseCases\TransferFunds;

use App\Services\RabbitMQ\RabbitMQConsumer;
use App\Services\RabbitMQ\RabbitMQPublisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseStatusCode;

class RabbitMQController
{
    public function __construct(private TransferFunds $transferFunds){}

    public function send(): JsonResponse
    {
        $pid = pcntl_fork();
        if ($pid === -1) {
            return response()->json(['error' => 'Erro ao iniciar consumidor'], ResponseStatusCode::HTTP_INTERNAL_SERVER_ERROR);
        } elseif ($pid === 0) {

            $config = config('queue.connections.rabbitmq');
            $publisher = new RabbitMQPublisher(
                $config['exchange'],
                $config['queue'],
                $config['queue']
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

        return response()->json(['success' => 'Mensagens enviadas com sucesso!'], ResponseStatusCode::HTTP_OK);
    }

    public function consume(): JsonResponse
    {
        $config = config('queue.connections.rabbitmq');
        $consumer = new RabbitMQConsumer(
            $config['exchange'],
            $config['queue'],
            $config['queue']
        );
        $pid = pcntl_fork();
        if ($pid === -1) {
            return response()->json(['error' => 'Erro ao iniciar consumidor'], ResponseStatusCode::HTTP_INTERNAL_SERVER_ERROR);
        } elseif ($pid === 0) {
            Log::info('Consumindo mensagens...');
            $consumer->consume(function (array $data) {
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

        return response()->json(['message' => 'Consumidor iniciado em background'], ResponseStatusCode::HTTP_OK);
    }
}