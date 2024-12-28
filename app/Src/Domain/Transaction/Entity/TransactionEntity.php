<?php

namespace App\Src\Domain\Transaction\Entity;

class TransactionEntity {
    public function __construct(
        public int $numberAccount,
        public string $type,
        public float $value,
        public string $status,
        public \DateTime $createdAt,
        public ?string $description
    ) {}

    public static function transaction(
        int $numberAccount,
        string $type,
        float $value,
        string $status,
        ?string $description = null
    ): self {

        $typeEnum = match ($type) {
            'withdraw' => 'withdraw',
            'deposit' => 'deposit',
            'transfer' => 'transfer',
            default => null,
        };

        if (empty($typeEnum)) {
            throw new \InvalidArgumentException('Tipo de transação inválida!');
        }

        $statusEnum = match ($status) {
            'completed' => 'completed',
            'pending' => 'pending',
            'failed' => 'failed',
            default => 'pending',
        };

        return new self(
            $numberAccount,
            $typeEnum,
            $value,
            $statusEnum,
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