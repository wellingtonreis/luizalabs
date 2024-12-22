<?php

namespace App\Repositories;

use App\Models\Transfer;
use App\Src\Repositories\TransferFundsRepositoryInterface;
use Closure;

class TransferFundsRepository implements TransferFundsRepositoryInterface
{
    public function bach(int $number, Closure $closure): void
    {
        Transfer::chunk($number, $closure);
    }
}