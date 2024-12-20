<?php

namespace App\Src\UseCases\Dto\Deposit;

class DepositDto {
    public function __construct(
        private readonly int $numberAccountOrigin,
        private readonly float $value,
        private readonly string $type,
        private readonly ?string $description,
    ) {}

    public function getNumberAccountOrigin(): int {
        return $this->numberAccountOrigin;
    }

    public function getValue(): float {
        return $this->value;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getDescription(): string {
        return $this->description ?? '';
    }
}