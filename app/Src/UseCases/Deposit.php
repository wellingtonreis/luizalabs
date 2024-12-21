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

    public function execute(DepositDto $DepositDto): Response {
        try {
            $accountOrigin = $this->origin($DepositDto);
            $this->accountRepository->save($accountOrigin);

            $transactionEntity = new TransactionEntity(
                $accountOrigin->getNumberAccount(),
                $DepositDto->getType(),
                $DepositDto->getValue(),
                new \DateTime(),
                $DepositDto->getDescription() 
            );
            $this->transactionRepository->save($transactionEntity);

            return Response::success('Depósito realizada com sucesso!');
        } catch (\Exception $e) {
            return Response::error('Erro ao realizar transferência!');
        }
    }

    private function origin(DepositDto $DepositDto): AccountEntity {
        $accountOrigin = $this->accountRepository->findByAccount(
            $DepositDto->getNumberAccountOrigin()
        );
        
        $accountOrigin->balance->credit($DepositDto->getValue());
        return new AccountEntity(
            $accountOrigin->numberAccount,
            $accountOrigin->balance,
            $accountOrigin->createdAt,
        );
    }
}