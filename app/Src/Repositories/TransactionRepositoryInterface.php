<?php

namespace App\Src\Repositories;

use App\Src\Domain\Transaction\Entity\TransactionEntity;

interface TransactionRepositoryInterface {
    public function save(TransactionEntity $transaction): void;
}