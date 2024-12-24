<?php

use App\Src\Domain\Account\Entity\AccountEntity;
use App\Src\Domain\Account\ValueObject\Balance;
use Tests\TestCase;

class AccountEntityTest extends TestCase
{
    private $balanceMock;
    private $accountEntityMock;

    public function setUp(): void
    {
        $this->balanceMock = $this->createMock(Balance::class);
        $this->accountEntityMock = $this->createMock(AccountEntity::class);
    }

    public function testInicializeAccountEntity()
    {
        $numberAccount = 12345678;
        $balance = new Balance(1500.50, 1000.00);
        $createdAt = new \DateTime();
        $accountEntity = new AccountEntity(
            $numberAccount, 
            $balance, 
            $createdAt
        );

        $this->assertSame($numberAccount, $accountEntity->numberAccount);
        $this->assertSame($balance, $accountEntity->balance);
        $this->assertSame($createdAt, $accountEntity->createdAt);
        
        $this->assertIsInt($accountEntity->numberAccount);
        $this->assertInstanceOf(Balance::class, $accountEntity->balance);
        $this->assertInstanceOf(\DateTime::class, $accountEntity->createdAt);
    }

    public function testGetNumberAccount()
    {
        $this->accountEntityMock->method('getNumberAccount')->willReturn(12345678);
        $this->assertEquals(12345678, $this->accountEntityMock->getNumberAccount());
        $this->assertIsInt($this->accountEntityMock->getNumberAccount());
    }

    public function testFeeGenerate()
    {
        $this->accountEntityMock->method('feeGenerate')->willReturn(0.5);
        $this->assertEquals(0.5, $this->accountEntityMock->feeGenerate());
        $this->assertIsFloat($this->accountEntityMock->feeGenerate());
    }
}