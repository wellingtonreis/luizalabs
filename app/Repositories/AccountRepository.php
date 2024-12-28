<?php

namespace App\Repositories;

use App\Models\Account as Model;
use App\Src\Domain\Account\Entity\AccountEntity;
use App\Src\Domain\Account\ValueObject\Balance;
use App\Src\Repositories\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface 
{
    public function __construct(protected Model $model){}
    
    public function findByAccount(int $numberAccount): AccountEntity
    {
        $account = $this->model->where('number_account', $numberAccount)->first();

        if (!$account) {
            throw new \Exception('Conta não encontrada');
        }

        return new AccountEntity(
            $account->number_account,
            new Balance($account->balance, $account->limit_credit),
            $account->created_at,
        );
    }

    public function save(AccountEntity $accountEntity): void
    {
        $account = $this->model->where('number_account', $accountEntity->numberAccount)
        ->lockForUpdate()
        ->first();

        $account->balance = $accountEntity->balance->value;
        $account->created_at = $accountEntity->createdAt;
        $account->save();
    }
}