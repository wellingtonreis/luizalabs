<?php

class TransactionEntity {
    public function __construct(
        private string $type,
        private float $value,
        private \DateTime $createdAt,
        private string $description
    ) {}
}