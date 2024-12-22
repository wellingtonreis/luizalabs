<?php 

namespace App\Src\Domain\Account\Entity;

use App\Src\Domain\Account\ValueObject\Balance;

class AccountEntity {
    public function __construct(
        public readonly int $numberAccount,
        public readonly Balance $balance,
        public readonly \DateTime $createdAt,
    ) {}

    public function getNumberAccount(): int {
        return $this->numberAccount;
    }

    public function feeGenerate(): float {
        return mt_rand(1, 5) / 10;
    }
}