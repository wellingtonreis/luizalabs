<?php

namespace App\Src\UseCases;

use App\Src\UseCases\Dto\Deposit\DepositDto;
use App\Src\Domain\Account\Entity\AccountEntity;
use App\Src\Domain\Transaction\Entity\TransactionEntity;
use App\Src\Repositories\AccountRepositoryInterface;
use App\Src\Repositories\TransactionRepositoryInterface;

class Deposit {
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(DepositDto $depositDto): Response {
        try {
            $accountOrigin = $this->origin($depositDto);
            $this->accountRepository->save($accountOrigin);
            $transactionEntity = TransactionEntity::transaction(
                $accountOrigin->getNumberAccount(),
                $depositDto->getType(),
                $depositDto->getValue()
            );
            $transactionEntity->setDescription($depositDto->getType());
            $this->transactionRepository->save($transactionEntity);

            return Response::success('Depósito realizada com sucesso!');
        } catch (\Exception $e) {

            $transactionEntity = TransactionEntity::transaction(
                $depositDto->getNumberAccountOrigin(),
                'deposit',
                $depositDto->getValue(),
                $e->getMessage()
            );
            $this->transactionRepository->save($transactionEntity);
            
            return Response::error($e->getMessage());
        }
    }

    private function origin(DepositDto $depositDto): AccountEntity {
        $accountOrigin = $this->accountRepository->findByAccount(
            $depositDto->getNumberAccountOrigin()
        );
        
        $accountOrigin->balance->credit($depositDto->getValue());
        return new AccountEntity(
            $accountOrigin->numberAccount,
            $accountOrigin->balance,
            $accountOrigin->createdAt,
        );
    }
}