<?php

use App\Src\Domain\Transaction\Entity\TransactionEntity;
use Tests\TestCase;

class TransactionEntityTest extends TestCase
{
    public function testInicializeTransactionEntity()
    {
        $numberAccount = 123456;
        $type = 'deposit';
        $value = 100.00;
        $description = 'Deposito realizado com sucesso!';
        $transaction = TransactionEntity::transaction(
            $numberAccount, 
            $type, 
            $value, 
            $description
        );

        $this->assertEquals($numberAccount, $transaction->numberAccount);
        $this->assertEquals($type, $transaction->type);
        $this->assertEquals($value, $transaction->value);
        $this->assertEquals($description, $transaction->description);
        
        $this->assertIsInt($transaction->numberAccount);
        $this->assertIsString($transaction->type);
        $this->assertIsFloat($transaction->value);
        $this->assertIsString($transaction->description);
        $this->assertInstanceOf(\DateTime::class, $transaction->createdAt);
    }

    public function testSetDescriptionTransactionEntity()
    {
        $numberAccount = 123456;
        $type = 'deposit';
        $value = 100.00;
        $transaction = TransactionEntity::transaction(
            $numberAccount, 
            $type, 
            $value
        );

        $transaction->setDescription($type);
        $this->assertEquals('Deposito realizado com sucesso!', $transaction->description);
    }

    public function testSetDescriptionTransactionEntityWithdraw()
    {
        $numberAccount = 123456;
        $type = 'withdraw';
        $value = 100.00;
        $transaction = TransactionEntity::transaction(
            $numberAccount, 
            $type, 
            $value
        );

        $transaction->setDescription($type);
        $this->assertEquals('Saque realizado com sucesso!', $transaction->description);
    }

    public function testSetDescriptionTransactionEntityEmpty()
    {
        $numberAccount = 123456;
        $type = 'deposit';
        $value = 100.00;
        $transaction = TransactionEntity::transaction(
            $numberAccount, 
            $type, 
            $value
        );

        $transaction->setDescription('transfer');
        $this->assertEquals('Transferência realizada com sucesso!', $transaction->description);
    }

    public function testSetDescriptionTransactionEntityDefault()
    {
        $numberAccount = 123456;
        $type = 'deposit';
        $value = 100.00;
        $transaction = TransactionEntity::transaction(
            $numberAccount, 
            $type, 
            $value
        );

        $transaction->setDescription('test');
        $this->assertEquals('Indefinido', $transaction->description);
    }
}