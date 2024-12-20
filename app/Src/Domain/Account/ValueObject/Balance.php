<?php 

namespace App\Src\Domain\Account\ValueObject;

class Balance {
    public function __construct(
        public float $value,
        public float $limitCredit,
    ) {}

    public function debit(float $value): void {
        
        if ($this->limitCredit() < $value) {
            throw new \Exception('Saldo insuficiente');
        }

        $this->value -= $value;
    }

    public function credit(float $value): void {
        $this->value += $value;
    }

    public function limitCredit(): float {
        
        $this->exceedLimitCredit($this->limitCredit);        
        return $this->value + $this->limitCredit;
    }

    public function exceedLimitCredit(int $limitCredit): bool {

        if ($this->value <= ($limitCredit * -1)) {
            throw new \Exception('Limite de crédito excedido');
        }
        return true;
    }
}