<?php

namespace Tests;

use App\Entity\Product;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    private Generator $faker;
    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function testConstructorProduct()
    {
        $name = $this->faker->name;
        $prices = ['USD' => $this->faker->numberBetween(50, 200), 'EUR' => $this->faker->numberBetween(100, 200)];
        $type = 'food';

        $product = new Product($name, $prices, $type);
        $this->assertInstanceOf(Product::class, $product);

        $this->assertEquals($name, $product->getName());
        $this->assertEquals($prices, $product->getPrices());
        $this->assertEquals($type, $product->getType());
    }
    public function testSetter()
    {
        $name = $this->faker->name;
        $prices = ['USD' => $this->faker->numberBetween(50, 200), 'EUR' => $this->faker->numberBetween(100, 200)];
        $type = 'food';

        $product = new Product($name, $prices, $type);

        $name2 = $this->faker->name;
        $product->setName($name2);
        $this->assertEquals($name2, $product->getName());

        $prices2 = ['USD' => $this->faker->numberBetween(50, 200), 'EUR' => $this->faker->numberBetween(100, 200)];
        $product->setPrices($prices2);
        $this->assertEquals($prices2, $product->getPrices());

        $prices3 = ['USD' => -50];
        $product->setPrices($prices3);
        $this->assertEquals($prices2, $product->getPrices());

        $type2 = 'tech';
        $product->setType($type2);
        $this->assertEquals($type2, $product->getType());
    }

    public function testSetterException()
    {
        $this->expectException(\Exception::class);
        $name = $this->faker->name;
        $prices = ['USD' => $this->faker->numberBetween(50, 200), 'EUR' => $this->faker->numberBetween(100, 200)];
        $type = 'food';

        $product = new Product($name, $prices, $type);
        $this->expectException(\Exception::class);
        $product->setType('RUB');
    }
    public function testGetTVAFood()
    {
        $name = $this->faker->name;
        $prices = ['USD' => $this->faker->numberBetween(50, 200), 'EUR' => $this->faker->numberBetween(100, 200)];
        $type = 'food';

        $product = new Product($name, $prices, $type);
        $this->assertEquals(0.1, $product->getTVA());
    }
    public function testGetTVANotFood()
    {
        $name = $this->faker->name;
        $prices = ['USD' => $this->faker->numberBetween(50, 200), 'EUR' => $this->faker->numberBetween(100, 200)];
        $type = 'tech';

        $product = new Product($name, $prices, $type);
        $this->assertEquals(0.2, $product->getTVA());
    }
    public function testListCurrencies()
    {
        $name = $this->faker->name;
        $prices = ['USD' => $this->faker->numberBetween(50, 200)];
        $prices2 = [
            'USD' => $this->faker->numberBetween(50, 200),
            'EUR' => $this->faker->numberBetween(100, 200)
        ];
        $prices3 = [
            'USD' => $this->faker->numberBetween(50, 200),
            'EUR' => $this->faker->numberBetween(100, 200),
            'RUB' => $this->faker->numberBetween(100, 200)
        ];

        $type = 'food';

        $product = new Product($name, $prices, $type);
        $product2 = new Product($name, $prices2, $type);
        $product3 = new Product($name, $prices3, $type);
        $this->assertEquals(['USD'], $product->listCurrencies());
        $this->assertEquals(['USD', 'EUR'], $product2->listCurrencies());
        $this->assertEquals(['USD', 'EUR'], $product3->listCurrencies());
    }
    public function testGetPrice()
    {
        $name = $this->faker->name;
        $prices = [
            'USD' => $this->faker->numberBetween(50, 200),
            'EUR' => $this->faker->numberBetween(100, 200)
        ];
        $type = 'food';
        $product = new Product($name, $prices, $type);

        $this->assertEquals($prices['USD'], $product->getPrice('USD'));
        $this->assertEquals($prices['EUR'], $product->getPrice('EUR'));
    }

    public function testGetPriceExecptionCurrencyNotAvailable()
    {
        $name = $this->faker->name;
        $prices = [
            'USD' => $this->faker->numberBetween(50, 200)
        ];
        $type = 'food';
        $product = new Product($name, $prices, $type);

        $this->expectException(\Exception::class);
        $this->assertEquals($prices['USD'], $product->getPrice('EUR'));
    }
    public function testGetPriceExecptionInvalidCurrency()
    {
        $name = $this->faker->name;
        $prices = [
            'USD' => $this->faker->numberBetween(50, 200),
            'EUR' => $this->faker->numberBetween(100, 200)
        ];
        $type = 'food';
        $product = new Product($name, $prices, $type);

        $this->expectException(\Exception::class);
        $this->assertEquals($prices['USD'], $product->getPrice('RUB'));
    }
}
