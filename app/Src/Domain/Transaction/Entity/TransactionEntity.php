<?php

namespace App\Src\Domain\Transaction\Entity;

class TransactionEntity {
    public function __construct(
        public int $numberAccount,
        public string $type,
        public float $value,
        public \DateTime $createdAt,
        public string $description
    ) {}
}