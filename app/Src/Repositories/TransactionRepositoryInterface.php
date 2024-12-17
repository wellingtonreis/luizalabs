<?php

namespace Src\Repositories;

use Src\Domain\Transaction\Entity\TransactionEntity;

interface TransactionRepositoryInterface {
    public function save(TransactionEntity $transaction): void;
}