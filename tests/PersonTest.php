<?php

namespace Tests;

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\Wallet;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    private Generator $faker;
    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }
    public function testConstructorPerson()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');
        $this->assertInstanceOf(Person::class, $person);

        $this->assertInstanceOf(Wallet::class, $person->getWallet());
        $this->assertEquals($name, $person->getName());
    }
    public function testSetterPerson()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');

        $name2 = $this->faker->name;
        $person->setName($name2);
        $this->assertEquals($name2, $person->getName());

        $wallet = new Wallet('EUR');
        $person->setWallet($wallet);
        $this->assertInstanceOf(Wallet::class, $person->getWallet());
        $this->assertEquals($wallet, $person->getWallet());
    }
    public function testHasFund()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');
        $this->assertFalse($person->hasFund());

        $person->getWallet()->addFund(100);
        $this->assertTrue($person->hasFund());
    }
    public function testTransfertFund()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');
        $person->getWallet()->addFund(100);
        $this->assertEquals(100, $person->getWallet()->getBalance());

        $person2 = new Person($name, 'USD');
        $person->transfertFund(50, $person2);

        $this->assertEquals(50, $person->getWallet()->getBalance());
        $this->assertEquals(50, $person2->getWallet()->getBalance());
    }
    public function testTransfertFundException()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');
        $person->getWallet()->addFund(100);
        $person2 = new Person($name, 'EUR');

        $this->expectException(\Exception::class);
        $person->transfertFund(50, $person2);

        $this->assertEquals(100, $person->getWallet()->getBalance());
        $this->assertEquals(0, $person2->getWallet()->getBalance());
    }
    public function testDivideWallet()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');
        $person->getWallet()->addFund(1000);
        $this->assertEquals(1000, $person->getWallet()->getBalance());

        $personArray = array();
        for ($i = 0; $i < 10; $i++) {
            $personArray[] = new Person($name, 'USD');
        }
        $person->divideWallet($personArray);
        $this->assertEquals(0, $person->getWallet()->getBalance());
        foreach ($personArray as $person) {
            $this->assertEquals(100, $person->getWallet()->getBalance());
        }
    }
    public function testBuyProduct()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');
        $person->getWallet()->addFund(1000);

        $name = $this->faker->name;
        $prices = [
            'USD' => $this->faker->numberBetween(50, 200),
            'EUR' => $this->faker->numberBetween(100, 200)
        ];
        $type = 'food';
        $product = new Product($name, $prices, $type);
        $person->buyProduct($product);
        $this->assertEquals(1000 - $prices['USD'], $person->getWallet()->getBalance());
    }

    public function testBuyProductException()
    {
        $name = $this->faker->name;
        $person = new Person($name, 'USD');
        $person->getWallet()->addFund(1000);

        $name = $this->faker->name;
        $prices = [
            'EUR' => $this->faker->numberBetween(100, 200)
        ];
        $type = 'food';
        $product = new Product($name, $prices, $type);
        $this->expectException(\Exception::class);
        $person->buyProduct($product);
    }
}
