<?php

namespace App\Src\Domain\Transaction\Entity;

class TransactionEntity {
    public function __construct(
        public int $numberAccount,
        public string $type,
        public float $value,
        public \DateTime $createdAt,
        public string|null $description
    ) {}

    public static function transaction(
        int $numberAccount,
        string $type,
        float $value,
        string|null $description = null
    ): self {
        return new self(
            $numberAccount,
            $type,
            $value,
            new \DateTime(),
            $description
        );
    }

    public function setDescription(string $type): void {

        if (empty($this->description)) {
            $this->description = $this->messageDefault($type);
        }
    }

    private function messageDefault(string $type): string {
        return match ($type) {
            'withdraw' => 'Saque realizado com sucesso!',
            'deposit' => 'Deposito realizado com sucesso!',
            'transfer' => 'Transferência realizada com sucesso!',
            default => 'Indefinido',
        };
    }
}