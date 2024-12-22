<?php

namespace App\Src\UseCases;

use App\Src\UseCases\Dto\TransferFunds\TransferFundsDto;
use App\Src\Domain\Account\Entity\AccountEntity;
use App\Src\Domain\Transaction\Entity\TransactionEntity;
use App\Src\Repositories\AccountRepositoryInterface;
use App\Src\Repositories\TransactionRepositoryInterface;
use App\Src\Repositories\UnitOfWorkInterface;

class TransferFunds {
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionRepositoryInterface $transactionRepository,
        private UnitOfWorkInterface $unitOfWork
    ) {}

    public function execute(TransferFundsDto $transferFundsDto): Response {
        try {
            $this->unitOfWork->begin();

            $accountOrigin = $this->origin($transferFundsDto);
            $accountDestination = $this->destination($transferFundsDto);

            // REALIZA DÉBITO NA CONTA ORIGEM
            $this->accountRepository->save($accountOrigin);
            // SALVA TRANSAÇÃO NA CONTA ORIGEM
            $transAccount = TransactionEntity::transaction(
                $accountOrigin->getNumberAccount(),
                $transferFundsDto->getType(),
                $transferFundsDto->getValue()
            );
            $transAccount->setDescription($transferFundsDto->getType());
            $this->transactionRepository->save($transAccount);
            
            // REALIZA CRÉDITO NA CONTA DESTINO
            $this->accountRepository->save($accountDestination);
            // SALVA TRANSAÇÃO NA CONTA DESTINO
            $transDestination = TransactionEntity::transaction(
                $accountDestination->getNumberAccount(),
                $transferFundsDto->getType(),
                $transferFundsDto->getValue()
            );
            $transDestination->setDescription($transferFundsDto->getType());
            $this->transactionRepository->save($transDestination);

            $this->unitOfWork->commit();
            return Response::success('Transferência realizada com sucesso!');
        } catch (\Exception $e) {

            $this->unitOfWork->rollback();

            // SALVA TRANSAÇÃO NA CONTA ORIGEM COM A EXCEÇÃO
            TransactionEntity::transaction(
                $transferFundsDto->getNumberAccountOrigin(),
                'transfer',
                $transferFundsDto->getValue(),
                $e->getMessage()
            );
            return Response::error('Erro ao realizar transferência!');
        }
    }

    private function origin(TransferFundsDto $transferFundsDto): AccountEntity {
        $accountOrigin = $this->accountRepository->findByAccount(
            $transferFundsDto->getNumberAccountOrigin()
        );

        $accountOrigin->balance->debit(
            $transferFundsDto->getValue(), 
            $accountOrigin->feeGenerate()
        );
        
        return new AccountEntity(
            $accountOrigin->numberAccount,
            $accountOrigin->balance,
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
            $accountDestination->createdAt,
        );
    }
}