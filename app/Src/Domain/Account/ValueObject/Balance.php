<?php 

class Balance {
    public function __construct(
        private int $value
    ) {}

    public function debit(int $value): void {
        if ($this->value < $value) {
            throw new Exception('Saldo insuficiente');
        }

        $this->value -= $value;
    }

    public function credit(int $value): void {
        $this->value += $value;
    }
}