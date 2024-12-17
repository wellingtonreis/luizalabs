<?php

namespace Src\UseCases;

use Src\Domain\Transaction\Entity\TransactionEntity;
use Src\Repositories\AccountRepositoryInterface;
use Src\Repositories\TransactionRepositoryInterface;

class TransferFunds {
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(InputAccountDto $inputAccountDto): Response {

        try {
            $accountOrigin = $this->accountRepository->findByAccount($inputAccountDto->getNumberAccount());
            $accountDestination = $this->accountRepository->findByAccount($inputAccountDto->getNumberAccount());
    
            $accountOrigin->balance->debit($inputAccountDto->balance->value);
            $accountDestination->balance->credit($inputAccountDto->balance->value);
    
            $transactionEntity = new TransactionEntity(
                $inputAccountDto->transaction->type,
                $inputAccountDto->transaction->value,
                $inputAccountDto->transaction->createdAt,
                $inputAccountDto->transaction?->description ?? ''
            );
    
            $this->accountRepository->save($accountOrigin);
            $this->accountRepository->save($accountDestination);
            $this->transactionRepository->save($transactionEntity);
            
            return Response::success('Transferência realizada com sucesso!');
        } catch (\Exception $e) {
            return Response::error('Erro ao realizar transferência!');
        }
    }
}