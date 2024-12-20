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

    public function execute(WithdrawDto $WithdrawDto): Response {
        try {
            $accountOrigin = $this->origin($WithdrawDto);

            $this->accountRepository->save($accountOrigin);
            $transactionEntity = new TransactionEntity(
                $accountOrigin->getNumberAccount(),
                $WithdrawDto->getType(),
                $WithdrawDto->getValue(),
                new \DateTime(),
                $WithdrawDto->getDescription()
            );
            $this->transactionRepository->save($transactionEntity);

            return Response::success('Saque realizada com sucesso!');
        } catch (\Exception $e) {
            return Response::error('Erro ao realizar transferência!');
        }
    }

    private function origin(WithdrawDto $WithdrawDto): AccountEntity {
        $accountOrigin = $this->accountRepository->findByAccount(
            $WithdrawDto->getNumberAccountOrigin()
        );

        $accountOrigin->balance->debit($WithdrawDto->getValue());
        return new AccountEntity(
            $accountOrigin->numberAccount,
            $accountOrigin->balance,
            $accountOrigin->createdAt,
        );
    }
}