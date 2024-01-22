<?php

namespace Tests;

use App\Entity\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testConstructorWallet()
    {
        $walletUSD = new Wallet('USD');
        $this->assertInstanceOf(Wallet::class, $walletUSD);
        $this->assertEquals(0, $walletUSD->getBalance());
        $this->assertEquals('USD', $walletUSD->getCurrency());

        $walletEUR = new Wallet('EUR');
        $this->assertEquals('EUR', $walletEUR->getCurrency());
    }
    public function testExecptionCurrency()
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('RUB');
    }
    public function testSetterWallet()
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(100);
        $this->assertEquals(100, $wallet->getBalance());
        $wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $wallet->getCurrency());
    }
    public function testSetBalanceException()
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->setBalance(-100);
    }
    public function testSetCurrencyException()
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->setCurrency('RUB');
    }
    public function testAddFund()
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $this->assertEquals(100, $wallet->getBalance());
        $wallet->addFund(300);
        $this->assertEquals(400, $wallet->getBalance());
    }
    public function testAddFundException()
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->addFund(-100);
    }
    public function testRemoveFund()
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $wallet->removeFund(50);
        $this->assertEquals(50, $wallet->getBalance());
    }
    public function testRemoveFundExceptionSoldeInsufficient()
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->removeFund(100);
    }
    public function testRemoveFundExceptionAmoutInvalid()
    {
        $this->expectException(\Exception::class);
        $wallet = new Wallet('USD');
        $wallet->removeFund(-100);
    }
}
