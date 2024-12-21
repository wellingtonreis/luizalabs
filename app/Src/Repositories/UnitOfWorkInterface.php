<?php

namespace App\Src\Repositories;

interface UnitOfWorkInterface {
    public function begin(): void;
    public function commit(): void;
    public function rollback(): void;
}