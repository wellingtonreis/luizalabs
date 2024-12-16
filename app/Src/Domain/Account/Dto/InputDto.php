<?php

use Illuminate\Support\Facades\Date;

class InputDto {
    public function __construct(
        private readonly int $numberAccount,
        private readonly int $balance,
        private readonly int $limitCredit,
        private readonly DateTime $createdAt,
        private readonly int $idTransaction
    ) {}
}