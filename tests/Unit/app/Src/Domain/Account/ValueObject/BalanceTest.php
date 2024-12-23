<?php

use App\Src\Domain\Account\ValueObject\Balance;
use PHPUnit\Framework\TestCase;

class BalanceTest extends TestCase
{
    public function testInicializeBalance()
    {
        $value = 1500.50;
        $limitCredit = 1000.00;
        $balance = new Balance($value, $limitCredit);

        $this->assertSame($value, $balance->value);
        $this->assertSame($limitCredit, $balance->limitCredit);
        
        $this->assertIsFloat($balance->value);
        $this->assertIsFloat($balance->limitCredit);
    }

    public function testDebit()
    {
        $value = 1500.50;
        $limitCredit = 1000.00;
        $balance = new Balance($value, $limitCredit);

        $balance->debit(500.00);

        $this->assertSame(1000.50, $balance->value);
    }

    public function testDebitWithFee()
    {
        $value = 1500.50;
        $limitCredit = 1000.00;
        $balance = new Balance($value, $limitCredit);

        $balance->debit(500.00, 0.5);

        $this->assertSame(998.0, $balance->value);
    }

    public function testDebitWithFeeAndLimitCredit()
    {
        $value = 1000.00;
        $limitCredit = 500.00;
        $balance = new Balance($value, $limitCredit);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Saldo insuficiênte');

        $balance->debit(1000.00, 0.5);
        $balance->debit(500.00, 0.5);
    }

    public function testCredit()
    {
        $value = 1500.50;
        $limitCredit = 1000.00;
        $balance = new Balance($value, $limitCredit);

        $balance->credit(500.00);

        $this->assertSame(2000.50, $balance->value);
    }

    public function testLimitCredit()
    {
        $value = 1500.50;
        $limitCredit = 1000.00;
        $balance = new Balance($value, $limitCredit);

        $this->assertSame(2500.50, $balance->limitCredit());
    }

    public function testExceedLimitCredit()
    {
        $value = 1000.00;
        $limitCredit = 1000.00;
        $balance = new Balance($value, $limitCredit);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Limite de crédito excedido');

        $balance->exceedLimitCredit(-1000.00);
    }

    public function testCalculateFee()
    {
        $value = 1000.00;
        $limitCredit = 1000.00;
        $balance = new Balance($value, $limitCredit);

        $reflection = new \ReflectionMethod(Balance::class, 'calculateFee');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($balance, 100.0, 5.0);

        $this->assertSame(5.0, $result);
    }
}