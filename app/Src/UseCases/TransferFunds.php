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

            $this->unitOfWork->commit();
            return Response::success('Transferência realizada com sucesso!');
        } catch (\Exception $e) {

            $this->unitOfWork->rollback();
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