<?php 

namespace Src\Domain\Account\Entity;

use Src\Domain\Account\ValueObject\Balance;
use Src\Domain\Transaction\Entity\TransactionEntity;

class AccountEntity {
    public function __construct(
        public int $numberAccount,
        public Balance $balance,
        public int $limitCredit,
        public \DateTime $createdAt,
        public TransactionEntity $transaction
    ) {}

    public function limitCredit(): int {
        return $this->balance->value + ($this->limitCredit ?? 0);
    }

    public function transfer(AccountEntity $toAccount, int $amount, float $fee = 0.0): bool {
        $totalAmount = $amount + $fee;
        if ($amount > 0 && $this->balance >= $totalAmount) {
            $this->balance->value -= $amount;
            $toAccount->balance->value += $amount;
            return true;
        }
        return false;
    }

    public function getNumberAccount(): int {
        return $this->numberAccount;
    }
}