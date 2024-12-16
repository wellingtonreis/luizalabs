<?php 

class AccountEntity {
    public function __construct(
        private int $numberAccount,
        private int $balance,
        private int $limitCredit,
        private \DateTime $createdAt,
        private int $transaction
    ) {}

    public function limitCredit(): int {
        return $this->balance + ($this->limitCredit ?? 0);
    }

    public function transfer(AccountEntity $toAccount, int $amount, float $fee = 0.0): bool {
        $totalAmount = $amount + $fee;
        if ($amount > 0 && $this->balance >= $totalAmount) {
            $this->balance -= $amount;
            $toAccount->balance += $amount;
            return true;
        }
        return false;
    }
}