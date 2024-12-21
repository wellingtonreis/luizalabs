<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
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
        $publisher = new RabbitMQPublisher();

        Transfer::chunk(100, function ($transfers) use ($publisher) {
            foreach ($transfers as $transfer) {
                $data = $transfer->toArray();
                $publisher->publish($data);
            }
        });

        return response()->json(['success' => 'Mensagens enviadas com sucesso!'], 200);
    }

    public function consume(): JsonResponse
    {
        $consumer = new RabbitMQConsumer();

        $pid = pcntl_fork();

        if ($pid === -1) {
            return response()->json(['error' => 'Erro ao iniciar consumidor'], 500);
        } elseif ($pid === 0) {
            $reply = [];
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
                    $response = $this->transferFunds->execute($dto);
            
                    if(!$response->outcome) {

                        array_push($reply, [
                            'number_account_origin' => $dto->getNumberAccountOrigin(),
                            'number_account_destination' => $dto->getNumberAccountDestination(),
                            'amount' => $dto->getValue(),
                            'description' => $dto->getDescription(),
                            'created_at' => now(),
                        ]);
                    }
                }
            }, function () use ($reply) {
                Log::info('Consumidor finalizado');

                if (!empty($reply)) {
                    $publisher = new RabbitMQPublisher();
                    $publisher->publish($reply);
                }
            });
            exit(0);
        }

        return response()->json(['message' => 'Consumidor iniciado em background'], 200);
    }
}