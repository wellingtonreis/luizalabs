<?php

namespace App\Src\UseCases;

use App\Src\UseCases\Dto\Withdraw\WithdrawDto;
use App\Src\Domain\Account\Entity\AccountEntity;
use App\Src\Domain\Transaction\Entity\TransactionEntity;
use App\Src\Repositories\AccountRepositoryInterface;
use App\Src\Repositories\TransactionRepositoryInterface;

class Withdraw {
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(WithdrawDto $withdrawDto): Response {
        try {
            $accountOrigin = $this->origin($withdrawDto);

            $this->accountRepository->save($accountOrigin);
            $transactionEntity = TransactionEntity::transaction(
                $accountOrigin->getNumberAccount(),
                $withdrawDto->getType(),
                $withdrawDto->getValue()
            );
            $transactionEntity->setDescription($withdrawDto->getType());
            $this->transactionRepository->save($transactionEntity);

            return Response::success('Saque realizado com sucesso!');
        } catch (\Exception $e) {

            $transactionEntity = TransactionEntity::transaction(
                $withdrawDto->getNumberAccountOrigin(),
                'withdraw',
                $withdrawDto->getValue(),
                $e->getMessage()
            );
            $this->transactionRepository->save($transactionEntity);
            
            return Response::error($e->getMessage());
        }
    }

    private function origin(WithdrawDto $withdrawDto): AccountEntity {
        $accountOrigin = $this->accountRepository->findByAccount(
            $withdrawDto->getNumberAccountOrigin()
        );

        $accountOrigin->balance->debit(
            $withdrawDto->getValue(),
        );
        return new AccountEntity(
            $accountOrigin->numberAccount,
            $accountOrigin->balance,
            $accountOrigin->createdAt,
        );
    }
}