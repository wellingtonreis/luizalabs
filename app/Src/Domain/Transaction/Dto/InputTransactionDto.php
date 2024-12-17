<?php

namespace Src\Domain\Transaction\Dto;

class InputTransactionDto {
    public function __construct(
        public readonly int $type,
        public readonly float $value,
        public readonly \DateTime $createdAt,
        public readonly ?string $description
    ) {}
}