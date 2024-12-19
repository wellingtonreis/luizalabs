<?php

namespace App\Src\UseCases;

use App\Src\UseCases\Dto\TransferFunds\TransferFundsDto;
use App\Src\Domain\Account\Entity\AccountEntity;
use App\Src\Domain\Transaction\Entity\TransactionEntity;
use App\Src\Repositories\AccountRepositoryInterface;
use App\Src\Repositories\TransactionRepositoryInterface;

class TransferFunds {
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(TransferFundsDto $transferFundsDto): Response {
        try {
            $accountOrigin = $this->origin($transferFundsDto);
            $accountDestination = $this->destination($transferFundsDto);
            
            $this->accountRepository->save($accountOrigin);
            $this->transaction(
                $accountOrigin->getNumberAccount(),
                $transferFundsDto->getType(),
                $transferFundsDto->getValue(),
                $transferFundsDto->getDescription() 
            );

            $this->accountRepository->save($accountDestination);
            $this->transaction(
                $accountDestination->getNumberAccount(),
                $transferFundsDto->getType(),
                $transferFundsDto->getValue(),
                $transferFundsDto->getDescription()
            );
            
            return Response::success('Transferência realizada com sucesso!');
        } catch (\Exception $e) {
            return Response::error('Erro ao realizar transferência!');
        }
    }

    private function origin(TransferFundsDto $transferFundsDto): AccountEntity {
        $accountOrigin = $this->accountRepository->findByAccount(
            $transferFundsDto->getNumberAccountOrigin()
        );

        $accountOrigin->limitCredit()->debit($transferFundsDto->getValue());
        return new AccountEntity(
            $accountOrigin->numberAccount,
            $accountOrigin->balance,
            $accountOrigin->limitCredit,
            $accountOrigin->createdAt,
        );
    }

    private function destination(TransferFundsDto $transferFundsDto): AccountEntity {
        $accountDestination = $this->accountRepository->findByAccount(
            $transferFundsDto->getNumberAccountDestination()
        );
        
        $accountDestination->balance->credit($transferFundsDto->getValue());
        return new AccountEntity(
            $accountDestination->numberAccount,
            $accountDestination->balance,
            $accountDestination->limitCredit,
            $accountDestination->createdAt,
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