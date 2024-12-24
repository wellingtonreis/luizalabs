<?php 

namespace App\Src\Domain\Account\ValueObject;

class Balance {
    public function __construct(
        public float $value,
        public float $limitCredit,
    ) {}

    public function debit(float $value, float $feePercentage = 0.0): void {

        if ($value < 0) {
            throw new \Exception('Saque não pode ser negativo!');
        }

        $totalDebit = $value + $this->calculateFee($value, $feePercentage);
        
        if ($this->limitCredit() < $totalDebit) {
            throw new \Exception('Saldo insuficiênte');
        }

        $this->value -= $totalDebit;
    }

    public function credit(float $value): void {

        if ($value < 0) {
            throw new \Exception('Depósito não pode ser negativo');
        }

        $this->value += $value;
    }

    public function limitCredit(): float {
        
        $this->exceedLimitCredit($this->limitCredit);        
        return $this->value + $this->limitCredit;
    }

    public function exceedLimitCredit(float $limitCredit): bool {

        if ($this->value <= ($limitCredit * -1)) {
            throw new \Exception('Limite de crédito excedido');
        }
        return true;
    }

    private function calculateFee(float $value, float $percentage): float {
        return $value * ($percentage / 100);
    }
}