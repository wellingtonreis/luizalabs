<?php 

namespace App\Src\Domain\Account\Entity;

use App\Src\Domain\Account\ValueObject\Balance;

class AccountEntity {
    public function __construct(
        public int $numberAccount,
        public Balance $balance,
        public int $limitCredit,
        public \DateTime $createdAt,
    ) {}

    public function limitCredit(): Balance {
        $this->balance->value = $this->balance->value + $this->limitCredit;
        return $this->balance;
    }

    public function getNumberAccount(): int {
        return $this->numberAccount;
    }
}