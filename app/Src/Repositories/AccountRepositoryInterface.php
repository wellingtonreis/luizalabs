<?php 

namespace Src\Repositories;

use Src\Domain\Account\Entity\AccountEntity;

interface AccountRepositoryInterface {
    public function findByAccount(int $numberAccount): AccountEntity;
    public function save(AccountEntity $account): void;
}