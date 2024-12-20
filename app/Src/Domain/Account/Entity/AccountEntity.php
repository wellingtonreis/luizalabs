<?php 

namespace App\Src\Domain\Account\Entity;

use App\Src\Domain\Account\ValueObject\Balance;

class AccountEntity {
    public function __construct(
        public int $numberAccount,
        public Balance $balance,
        public \DateTime $createdAt,
    ) {}

    public function getNumberAccount(): int {
        return $this->numberAccount;
    }
}