<?php

namespace App\Repositories;

use App\Src\Repositories\UnitOfWorkInterface;
use Illuminate\Support\Facades\DB;

class UnitOfWork implements UnitOfWorkInterface {
    public function begin(): void {
        DB::beginTransaction();
    }

    public function commit(): void {
        DB::commit();
    }

    public function rollback(): void {
        DB::rollBack();
    }
}