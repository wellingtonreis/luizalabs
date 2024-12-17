<?php

namespace Src\UseCases;

use DateTime;
use Src\Domain\Account\ValueObject\Balance;
use Src\Domain\Transaction\Dto\InputTransactionDto;

class InputAccountDto {
    public function __construct(
        public readonly int $numberAccount,
        public readonly Balance $balance,
        public readonly int $limitCredit,
        public readonly DateTime $createdAt,
        public readonly InputTransactionDto $transaction
    ) {}

    public function getNumberAccount(): int {
        return $this->numberAccount;
    }
}