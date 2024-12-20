<?php

namespace App\Src\UseCases;

use App\Src\UseCases\Dto\TransferFunds\TransferFundsDto;
use App\Src\Domain\Account\Entity\AccountEntity;
use App\Src\Domain\Transaction\Entity\TransactionEntity;
use App\Src\Repositories\AccountRepositoryInterface;
use App\Src\Repositories\TransactionRepositoryInterface;

class Deposit {
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(TransferFundsDto $transferFundsDto): Response {
        try {
            $accountOrigin = $this->origin($transferFundsDto);

            $this->accountRepository->save($accountOrigin);
            $this->transaction(
                $accountOrigin->getNumberAccount(),
                $transferFundsDto->getType(),
                $transferFundsDto->getValue(),
                $transferFundsDto->getDescription() 
            );

            return Response::success('Transferência realizada com sucesso!');
        } catch (\Exception $e) {
            dump($e->getMessage());
            return Response::error('Erro ao realizar transferência!');
        }
    }

    private function origin(TransferFundsDto $transferFundsDto): AccountEntity {
        $accountOrigin = $this->accountRepository->findByAccount(
            $transferFundsDto->getNumberAccountOrigin()
        );

        $accountOrigin->balance->debit($transferFundsDto->getValue());
        return new AccountEntity(
            $accountOrigin->numberAccount,
            $accountOrigin->balance,
            $accountOrigin->createdAt,
        );
    }

    private function transaction(int $numberAccount, string $type, float $value, string $description): void {
        
        $transactionEntity = new TransactionEntity(
            $numberAccount,
            $type,
            $value,
            new \DateTime(),
            $description
        );
        $this->transactionRepository->save($transactionEntity);
    }
}