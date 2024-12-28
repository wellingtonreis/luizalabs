<?php

namespace App\Repositories;

use App\Models\Transaction as Model;
use App\Src\Domain\Transaction\Entity\TransactionEntity;
use App\Src\Repositories\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(protected Model $model){}

    public function save(TransactionEntity $transaction): void
    {
        $this->model->create([
            'number_account' => $transaction->numberAccount,
            'type' => $transaction->type,
            'value' => $transaction->value,
            'status' => $transaction->status,
            'created_at' => $transaction->createdAt,
            'description' => $transaction->description,
        ]);
    }
}