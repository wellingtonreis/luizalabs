<?php 

namespace App\Src\Repositories;

use Closure;

interface TransferFundsRepositoryInterface {
    public function bach(int $number, Closure $closure): void;
}